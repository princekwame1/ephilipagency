<?php

namespace Database\Seeders;

/**
 * Generates on-brand SVG illustrations (purple and gold) used as stand-in photos for the demo listings.
 * Replace them by uploading real photos in the dashboard.
 */
class Illustration
{
    private const SKIES = [
        ['#2a0f3f', '#7a3e8e', '#f0b46a'], // dusk
        ['#3b6fb0', '#8fb5df', '#f6dcae'], // morning
        ['#1f0b2e', '#4a2266', '#c9922f'], // night with gold horizon
        ['#5c2b7e', '#b07ab8', '#f7d9a8'], // sunset
        ['#2f5e8f', '#a7c6e6', '#fbefd6'], // clear day
    ];

    private int $state;

    public function __construct(int $seed)
    {
        $this->state = $seed * 7919 + 13;
    }

    private function rand(int $min, int $max): int
    {
        $this->state = ($this->state * 1103515245 + 12345) & 0x7fffffff;

        return $min + $this->state % ($max - $min + 1);
    }

    private function pick(array $items): mixed
    {
        return $items[$this->rand(0, count($items) - 1)];
    }

    /** Exterior scene. $kind: tower | villa | townhouse | office | hostel */
    public function exterior(string $kind): string
    {
        [$top, $mid, $bottom] = $sky = $this->pick(self::SKIES);
        $night = $top === '#1f0b2e' || $top === '#2a0f3f';
        $svg = $this->open().
            "<defs><linearGradient id='sky' x1='0' y1='0' x2='0' y2='1'><stop offset='0' stop-color='{$top}'/><stop offset='.6' stop-color='{$mid}'/><stop offset='1' stop-color='{$bottom}'/></linearGradient>".
            "<linearGradient id='wall' x1='0' y1='0' x2='1' y2='1'><stop offset='0' stop-color='#f7f1fa'/><stop offset='1' stop-color='#d9cbe4'/></linearGradient>".
            "<linearGradient id='glass' x1='0' y1='0' x2='0' y2='1'><stop offset='0' stop-color='".($night ? '#f3c96b' : '#7fa6cf')."'/><stop offset='1' stop-color='".($night ? '#c9922f' : '#3e5f8a')."'/></linearGradient></defs>".
            "<rect width='1200' height='900' fill='url(#sky)'/>";

        // Sun or moon
        $svg .= $night
            ? "<circle cx='{$this->rand(150, 1050)}' cy='{$this->rand(90, 200)}' r='34' fill='#fbefd6' opacity='.9'/>"
            : "<circle cx='{$this->rand(150, 1050)}' cy='{$this->rand(380, 470)}' r='90' fill='#fff3d6' opacity='.55'/>";

        // Distant skyline
        $x = -20;
        while ($x < 1200) {
            $w = $this->rand(50, 120);
            $h = $this->rand(80, 260);
            $svg .= "<rect x='{$x}' y='".(640 - $h)."' width='{$w}' height='{$h}' fill='#371a4c' opacity='.28'/>";
            $x += $w + $this->rand(4, 20);
        }

        $svg .= match ($kind) {
            'tower' => $this->tower(),
            'office' => $this->office(),
            'townhouse' => $this->townhouses(),
            'hostel' => $this->hostel(),
            default => $this->villa(),
        };

        // Ground, path and planting
        $svg .= "<rect y='760' width='1200' height='140' fill='#4f6b3a'/><rect y='760' width='1200' height='14' fill='#6d8a4c'/>".
            "<path d='M520 900 L600 760 L680 760 L760 900Z' fill='#e8dccb'/>";
        for ($i = 0; $i < 2; $i++) {
            $svg .= $this->palm($i === 0 ? $this->rand(40, 220) : $this->rand(980, 1150), 770, $this->rand(220, 300));
        }
        for ($i = 0; $i < 7; $i++) {
            $svg .= "<ellipse cx='{$this->rand(0, 1200)}' cy='{$this->rand(770, 800)}' rx='{$this->rand(30, 60)}' ry='{$this->rand(16, 26)}' fill='#3e5a2c'/>";
        }

        return $svg.'</svg>';
    }

    /** Interior scene. $room: living | bedroom | kitchen */
    public function interior(string $room): string
    {
        $wall = $this->pick(['#f4eef7', '#f6efe6', '#efe8f3']);
        $accent = $this->pick(['#5c2b7e', '#713899', '#4a2266']);
        $svg = $this->open()."<rect width='1200' height='900' fill='{$wall}'/>".
            "<rect y='640' width='1200' height='260' fill='#c9a77c'/><rect y='640' width='1200' height='10' fill='#b48d5f'/>";
        for ($x = 0; $x < 1200; $x += 150) {
            $svg .= "<rect x='{$x}' y='650' width='2' height='250' fill='#b48d5f' opacity='.5'/>";
        }
        // Window with view
        $svg .= "<rect x='700' y='120' width='380' height='420' rx='8' fill='#fff'/><rect x='716' y='136' width='348' height='388' fill='#9fc3e6'/>".
            "<rect x='716' y='380' width='348' height='144' fill='#6d8a4c'/><rect x='888' y='136' width='4' height='388' fill='#fff'/>".
            "<rect x='690' y='110' width='40' height='450' fill='{$accent}' opacity='.85'/><rect x='1070' y='110' width='40' height='450' fill='{$accent}' opacity='.85'/>";

        $svg .= match ($room) {
            'bedroom' => "<rect x='120' y='300' width='460' height='120' rx='14' fill='{$accent}'/><rect x='100' y='480' width='500' height='170' rx='16' fill='#fff'/>".
                "<rect x='100' y='420' width='500' height='90' rx='20' fill='#efe3f5'/><rect x='140' y='430' width='160' height='60' rx='18' fill='#fff'/><rect x='320' y='430' width='160' height='60' rx='18' fill='#fff'/>".
                "<rect x='100' y='560' width='500' height='90' rx='10' fill='#dbab47'/><rect x='610' y='520' width='70' height='130' rx='6' fill='#8a5621'/><circle cx='645' cy='480' r='30' fill='#f3c96b'/>",
            'kitchen' => "<rect x='60' y='420' width='600' height='230' fill='#fff'/><rect x='60' y='410' width='600' height='22' fill='#2b1838'/>".
                "<rect x='90' y='460' width='160' height='160' rx='6' fill='#ece4f2'/><rect x='270' y='460' width='160' height='160' rx='6' fill='#ece4f2'/><rect x='450' y='460' width='180' height='160' rx='6' fill='#ece4f2'/>".
                "<rect x='60' y='150' width='600' height='150' fill='#fff'/><rect x='80' y='170' width='170' height='110' rx='4' fill='#ece4f2'/><rect x='270' y='170' width='170' height='110' rx='4' fill='#ece4f2'/><rect x='460' y='170' width='180' height='110' rx='4' fill='#ece4f2'/>".
                "<circle cx='340' cy='60' r='0'/><rect x='220' y='320' width='4' height='60' fill='#2b1838'/><path d='M190 380h64l-10 30h-44z' fill='#dbab47'/><rect x='480' y='320' width='4' height='60' fill='#2b1838'/><path d='M450 380h64l-10 30h-44z' fill='#dbab47'/>",
            default => "<rect x='80' y='470' width='520' height='130' rx='26' fill='{$accent}'/><rect x='80' y='400' width='520' height='110' rx='30' fill='{$accent}' opacity='.9'/>".
                "<rect x='60' y='450' width='60' height='150' rx='22' fill='{$accent}'/><rect x='560' y='450' width='60' height='150' rx='22' fill='#4a2266'/>".
                "<rect x='150' y='420' width='110' height='80' rx='16' fill='#dbab47'/><rect x='420' y='420' width='110' height='80' rx='16' fill='#efd89e'/>".
                "<ellipse cx='340' cy='740' rx='300' ry='50' fill='#e5c06a' opacity='.5'/><rect x='240' y='660' width='200' height='16' rx='8' fill='#2b1838'/><rect x='260' y='676' width='10' height='50' fill='#2b1838'/><rect x='410' y='676' width='10' height='50' fill='#2b1838'/>".
                "<rect x='200' y='170' width='280' height='170' rx='6' fill='#dbab47'/><rect x='214' y='184' width='252' height='142' fill='#713899'/><circle cx='300' cy='240' r='40' fill='#f3c96b' opacity='.8'/>".
                "<rect x='640' y='520' width='8' height='120' fill='#2b1838'/><path d='M600 520h88l-20-90h-48z' fill='#f7eccf'/>",
        };

        // Plant
        $svg .= "<rect x='1110' y='590' width='60' height='60' rx='8' fill='#8a5621'/><ellipse cx='1140' cy='560' rx='50' ry='70' fill='#4f6b3a'/>";

        return $svg.'</svg>';
    }

    /** Large hero skyline for the home page. */
    public static function hero(): string
    {
        $self = new self(42);
        $svg = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1920 1080' preserveAspectRatio='xMidYMid slice'>".
            "<defs><linearGradient id='s' x1='0' y1='0' x2='0' y2='1'><stop offset='0' stop-color='#1f0b2e'/><stop offset='.55' stop-color='#5c2b7e'/><stop offset='.85' stop-color='#c9922f'/><stop offset='1' stop-color='#efd89e'/></linearGradient>".
            "<radialGradient id='g' cx='.7' cy='.78' r='.5'><stop offset='0' stop-color='#f3c96b' stop-opacity='.7'/><stop offset='1' stop-color='#f3c96b' stop-opacity='0'/></radialGradient></defs>".
            "<rect width='1920' height='1080' fill='url(#s)'/><rect width='1920' height='1080' fill='url(#g)'/>".
            "<circle cx='1350' cy='800' r='120' fill='#fbefd6' opacity='.55'/>";
        for ($i = 0; $i < 60; $i++) {
            $svg .= "<circle cx='{$self->rand(0, 1920)}' cy='{$self->rand(0, 450)}' r='".($self->rand(5, 16) / 10)."' fill='#fff' opacity='.".$self->rand(3, 8)."'/>";
        }
        foreach ([[0.35, 380, '#3a1c52'], [0.7, 260, '#2a1240'], [1, 160, '#1f0b2e']] as [$opacity, $maxH, $color]) {
            $x = -40;
            while ($x < 1920) {
                $w = $self->rand(70, 170);
                $h = $self->rand(120, $maxH + 200);
                $y = 1080 - $h;
                $svg .= "<rect x='{$x}' y='{$y}' width='{$w}' height='{$h}' fill='{$color}' opacity='{$opacity}'/>";
                if ($opacity > 0.5) {
                    for ($wy = $y + 24; $wy < 1060; $wy += 34) {
                        for ($wx = $x + 14; $wx < $x + $w - 18; $wx += 26) {
                            if ($self->rand(0, 9) < 3) {
                                $svg .= "<rect x='{$wx}' y='{$wy}' width='10' height='14' fill='#f3c96b' opacity='.".$self->rand(5, 9)."'/>";
                            }
                        }
                    }
                }
                $x += $w + $self->rand(6, 30);
            }
        }

        return $svg.'</svg>';
    }

    public static function placeholder(): string
    {
        return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 900'><rect width='1200' height='900' fill='#f0e7f8'/>".
            "<path d='M450 470 600 340l150 130' fill='none' stroke='#dbab47' stroke-width='24' stroke-linecap='round' stroke-linejoin='round'/>".
            "<path d='M500 450v140h200V450' fill='none' stroke='#5c2b7e' stroke-width='20' stroke-linejoin='round'/></svg>";
    }

    private function open(): string
    {
        return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 900' preserveAspectRatio='xMidYMid slice'>";
    }

    private function windows(int $x, int $y, int $w, int $h, int $cols, int $rows, int $gap = 14): string
    {
        $svg = '';
        $cw = ($w - $gap * ($cols + 1)) / $cols;
        $rh = ($h - $gap * ($rows + 1)) / $rows;
        for ($r = 0; $r < $rows; $r++) {
            for ($c = 0; $c < $cols; $c++) {
                $wx = round($x + $gap + $c * ($cw + $gap), 1);
                $wy = round($y + $gap + $r * ($rh + $gap), 1);
                $svg .= "<rect x='{$wx}' y='{$wy}' width='".round($cw, 1)."' height='".round($rh, 1)."' rx='3' fill='url(#glass)' opacity='.".$this->rand(75, 99)."'/>";
            }
        }

        return $svg;
    }

    private function tower(): string
    {
        $x = $this->rand(380, 480);
        $floors = $this->rand(8, 12);
        $top = 760 - $floors * 52;
        $svg = "<rect x='{$x}' y='{$top}' width='340' height='".(760 - $top)."' fill='url(#wall)'/>".
            "<rect x='".($x - 10)."' y='".($top - 18)."' width='360' height='22' fill='#4a2266'/>";
        for ($f = 0; $f < $floors; $f++) {
            $fy = $top + 10 + $f * 52;
            $svg .= $this->windows($x + 10, $fy, 320, 40, 4, 1, 10)."<rect x='".($x - 6)."' y='".($fy + 38)."' width='352' height='6' fill='#dbab47' opacity='.9'/>";
        }
        $svg .= "<rect x='".($x + 360)."' y='".($top + 140)."' width='160' height='".(620 - $top)."' fill='#e7dcef'/>".$this->windows($x + 360, $top + 150, 160, 600 - $top, 2, max(3, $floors - 3));

        return $svg;
    }

    private function villa(): string
    {
        $x = $this->rand(300, 380);
        $roof = $this->pick(['#4a2266', '#5c2b7e', '#2b1838']);

        return "<rect x='{$x}' y='470' width='560' height='290' fill='url(#wall)'/>".
            "<rect x='".($x + 300)."' y='360' width='320' height='170' fill='#efe6f4'/>".
            "<rect x='".($x - 20)."' y='450' width='600' height='26' fill='{$roof}'/><rect x='".($x + 280)."' y='340' width='360' height='24' fill='{$roof}'/>".
            $this->windows($x + 20, 500, 260, 150, 2, 1, 18).$this->windows($x + 320, 380, 280, 120, 3, 1, 14).
            "<rect x='".($x + 340)."' y='560' width='120' height='200' rx='4' fill='#8a5621'/><circle cx='".($x + 440)."' cy='660' r='6' fill='#dbab47'/>".
            "<rect x='".($x + 480)."' y='560' width='70' height='120' rx='3' fill='url(#glass)'/>".
            "<rect x='".($x - 60)."' y='700' width='690' height='60' fill='#e8dccb'/><rect x='".($x - 60)."' y='690' width='690' height='14' fill='#dbab47'/>";
    }

    private function townhouses(): string
    {
        $svg = '';
        $x = $this->rand(140, 220);
        for ($i = 0; $i < 4; $i++) {
            $h = 300 + ($i % 2) * 40;
            $top = 760 - $h;
            $svg .= "<rect x='{$x}' y='{$top}' width='210' height='{$h}' fill='url(#wall)'/>".
                "<path d='M".($x - 10)." {$top} L".($x + 105).' '.($top - 70).' L'.($x + 220)." {$top}Z' fill='".($i % 2 ? '#5c2b7e' : '#4a2266')."'/>".
                $this->windows($x + 10, $top + 20, 190, 110, 2, 1, 16).
                "<rect x='".($x + 70)."' y='620' width='70' height='140' rx='3' fill='#8a5621'/><rect x='".($x + 20)."' y='600' width='170' height='8' fill='#dbab47'/>";
            $x += 225;
        }

        return $svg;
    }

    private function office(): string
    {
        $x = $this->rand(300, 420);

        return "<rect x='{$x}' y='200' width='520' height='560' fill='#d6cae0'/>".$this->windows($x + 10, 210, 500, 480, 6, 8, 8).
            "<rect x='{$x}' y='180' width='520' height='24' fill='#2b1838'/><rect x='".($x + 180)."' y='690' width='160' height='70' fill='url(#glass)'/>".
            "<rect x='".($x + 170)."' y='680' width='180' height='10' fill='#dbab47'/>";
    }

    private function hostel(): string
    {
        $x = $this->rand(220, 320);
        $svg = "<rect x='{$x}' y='340' width='720' height='420' fill='url(#wall)'/><rect x='".($x - 10)."' y='320' width='740' height='24' fill='#4a2266'/>";
        for ($f = 0; $f < 4; $f++) {
            $fy = 360 + $f * 100;
            $svg .= $this->windows($x + 10, $fy, 700, 70, 7, 1, 16)."<rect x='{$x}' y='".($fy + 76)."' width='720' height='10' fill='#dbab47' opacity='.85'/>";
        }

        return $svg;
    }

    private function palm(int $x, int $base, int $height): string
    {
        $top = $base - $height;
        $svg = "<path d='M{$x} {$base} Q".($x + 20).' '.($base - $height / 2)." ".($x + 8)." {$top}' stroke='#5a3b1f' stroke-width='14' fill='none' stroke-linecap='round'/>";
        foreach ([[-120, 40], [-80, -10], [0, -40], [80, -10], [120, 40], [-40, 60], [50, 60]] as [$dx, $dy]) {
            $svg .= "<path d='M".($x + 8)." {$top} Q".($x + 8 + $dx / 2).' '.($top + $dy - 50).' '.($x + 8 + $dx).' '.($top + $dy)."' stroke='#2f4d23' stroke-width='16' fill='none' stroke-linecap='round'/>";
        }

        return $svg;
    }
}
