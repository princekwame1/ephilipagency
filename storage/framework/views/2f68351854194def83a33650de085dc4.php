
<?php ($name = \App\Models\Setting::get('business_name')); ?>
<style>
    #preloader { display: none; }
    .js #preloader {
        display: grid; place-items: center; position: fixed; inset: 0; z-index: 9999;
        background: radial-gradient(60% 50% at 50% 42%, rgb(201 146 47 / .16), transparent 70%), linear-gradient(160deg, #4a2266 0%, #1f0b2e 55%, #12061b 100%);
        /* slide with transform (GPU-composited) instead of clip-path, which repaints the full-screen gradient every frame */
        transform: translate3d(0, 0, 0); will-change: transform; backface-visibility: hidden;
        transition: transform .9s cubic-bezier(.76, 0, .24, 1), opacity .35s ease .55s;
    }
    /* lock scroll but keep the scrollbar's space reserved, so the page behind doesn't jump sideways when it unlocks */
    .js:not(.preloaded) { scrollbar-gutter: stable; }
    .js:not(.preloaded) body { overflow: hidden; }
    #preloader.is-done { transform: translate3d(0, -100%, 0); opacity: 0; pointer-events: none; }
    #preloader .pl-stage { display: grid; justify-items: center; gap: 1.4rem; transition: transform .85s cubic-bezier(.76, 0, .24, 1), opacity .5s; }
    #preloader.is-done .pl-stage { transform: translateY(-40px); opacity: 0; }

    /* Emblem */
    #preloader svg { width: 112px; height: 112px; overflow: visible; filter: drop-shadow(0 6px 18px rgb(219 171 71 / .25)); }
    #preloader .pl-draw { fill: none; stroke: url(#pl-gold); stroke-linecap: round; stroke-linejoin: round; stroke-dasharray: 1; stroke-dashoffset: 1; animation: pl-draw 1s cubic-bezier(.65, 0, .35, 1) forwards; }
    #preloader .pl-roof { stroke-width: 3.6; }
    #preloader .pl-swoosh { stroke-width: 2.2; animation-delay: .55s; }
    #preloader .pl-pane { fill: url(#pl-gold); opacity: 0; transform: scale(.3); transform-box: fill-box; transform-origin: center; animation: pl-pop .35s cubic-bezier(.34, 1.56, .64, 1) forwards; }
    #preloader .pl-key { transform-box: view-box; transform-origin: 48px 16px; opacity: 0; animation: pl-swing 1.1s cubic-bezier(.34, 1.4, .64, 1) .45s forwards; }
    #preloader .pl-key .pl-draw { stroke-width: 2.6; animation-delay: .5s; }
    #preloader .pl-spark { fill: #fff6dc; opacity: 0; transform-box: fill-box; transform-origin: center; animation: pl-spark 1.4s ease-in-out 1.1s infinite; }

    /* Wordmark and progress line */
    #preloader .pl-name { font: 600 1.35rem/1 'Fraunces Variable', 'Fraunces Fallback', Georgia, serif; letter-spacing: .02em; color: #fff; opacity: 0; transform: translateY(8px); animation: pl-rise .7s cubic-bezier(.2, .7, .2, 1) .7s forwards; }
    #preloader .pl-name span { display: block; margin-top: .55rem; font: 600 .62rem/1 'Inter Variable', 'Inter Fallback', Arial, sans-serif; letter-spacing: .42em; text-transform: uppercase; text-align: center; color: #e5c06a; }
    #preloader .pl-bar { width: 140px; height: 2px; border-radius: 2px; background: rgb(255 255 255 / .1); overflow: hidden; opacity: 0; animation: pl-rise .5s ease .9s forwards; }
    #preloader .pl-bar::after { content: ''; display: block; height: 100%; width: 45%; border-radius: inherit; background: linear-gradient(90deg, transparent, #e5c06a, #f7eccf, #e5c06a, transparent); animation: pl-sweep 1.15s ease-in-out infinite; }

    @keyframes pl-draw { to { stroke-dashoffset: 0; } }
    @keyframes pl-pop { to { opacity: 1; transform: scale(1); } }
    @keyframes pl-swing { 0% { opacity: 0; transform: translateY(-14px) rotate(-38deg); } 35% { opacity: 1; } 100% { opacity: 1; transform: none; } }
    @keyframes pl-spark { 0%, 100% { opacity: 0; transform: scale(.4) rotate(0deg); } 50% { opacity: 1; transform: scale(1) rotate(45deg); } }
    @keyframes pl-rise { to { opacity: 1; transform: none; } }
    @keyframes pl-sweep { from { transform: translateX(-110%); } to { transform: translateX(250%); } }

    @media (prefers-reduced-motion: reduce) {
        #preloader *, #preloader *::after { animation-duration: .01ms !important; animation-delay: 0s !important; animation-iteration-count: 1 !important; }
        .js #preloader { transition: opacity .3s; }
        #preloader.is-done { transform: none; }
    }
    .pl-skip #preloader { display: none !important; }
</style>
<script>
    // Only play when arriving at the site (new tab, link from Google/WhatsApp, typed address) or on reload.
    // Clicking around inside the site, filters, pagination, form submits and Back/Forward skip it.
    (function () {
        try {
            var nav = performance.getEntriesByType('navigation')[0], type = nav ? nav.type : 'navigate', internal = false;
            try { internal = !!document.referrer && new URL(document.referrer).origin === location.origin; } catch (e) {}
            if (type === 'back_forward' || (internal && type !== 'reload')) document.documentElement.classList.add('pl-skip', 'preloaded');
        } catch (e) {}
    })();
</script>
<div id="preloader" aria-hidden="true">
    <div class="pl-stage">
        <svg viewBox="6 4 50 52">
            <defs>
                <linearGradient id="pl-gold" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0" stop-color="#f7eccf"/>
                    <stop offset=".4" stop-color="#e5c06a"/>
                    <stop offset=".7" stop-color="#c9922f"/>
                    <stop offset="1" stop-color="#efd89e"/>
                </linearGradient>
            </defs>
            <path class="pl-draw pl-roof" pathLength="1" d="M10 37 30 18.5 45.5 33"/>
            <rect class="pl-pane" style="animation-delay:.55s" x="25.5" y="27.5" width="3.8" height="3.8" rx=".5"/>
            <rect class="pl-pane" style="animation-delay:.65s" x="30.7" y="27.5" width="3.8" height="3.8" rx=".5"/>
            <rect class="pl-pane" style="animation-delay:.75s" x="25.5" y="32.7" width="3.8" height="3.8" rx=".5"/>
            <rect class="pl-pane" style="animation-delay:.85s" x="30.7" y="32.7" width="3.8" height="3.8" rx=".5"/>
            <g class="pl-key">
                <circle class="pl-draw" pathLength="1" cx="48" cy="16" r="6.7"/>
                <path class="pl-draw" pathLength="1" d="M48 22.7V50.5M48 40.5h5M51 43.6h2.2M48 46h5"/>
            </g>
            <path class="pl-draw pl-swoosh" pathLength="1" d="M8.5 44.8C20 40.6 33 40.4 43.5 42.6"/>
            <path class="pl-spark" d="M53 5l.9 3.1 3.1.9-3.1.9-.9 3.1-.9-3.1-3.1-.9 3.1-.9z"/>
        </svg>
        <p class="pl-name"><?php echo e(Str::beforeLast($name, ' ') ?: $name); ?><?php if(Str::contains($name, ' ')): ?><span><?php echo e(Str::afterLast($name, ' ')); ?></span><?php endif; ?></p>
        <div class="pl-bar"></div>
    </div>
</div>

<script>
    (function () {
        var el = document.getElementById('preloader');
        if (!el || document.documentElement.classList.contains('preloaded')) { el && el.remove(); return; }
        var start = performance.now(), done = false;
        function finish() {
            if (done) return; done = true;
            // let the full emblem + name finish (key/swoosh end at ~1.55 s) and stay on screen a beat
            var min = matchMedia('(prefers-reduced-motion: reduce)').matches ? 300 : 1900;
            var wait = Math.max(0, min - (performance.now() - start));
            setTimeout(function () {
                el.classList.add('is-done');
                document.documentElement.classList.add('preloaded');
                setTimeout(function () { el.remove(); }, 1000);
            }, wait);
        }
        if (document.readyState === 'complete') finish(); else window.addEventListener('load', finish);
        window.addEventListener('pageshow', function (e) { if (e.persisted) { el.remove(); document.documentElement.classList.add('preloaded'); } });
        setTimeout(finish, 3500); // never hold the page longer than this (plus the 1.9 s minimum)
    })();
</script>
<?php /**PATH C:\Users\princ\OneDrive\Desktop\Personal\luwoap-new\resources\views/components/preloader.blade.php ENDPATH**/ ?>