<?php

namespace App\Support;

use App\Models\Enquiry;
use App\Models\Setting;
use App\Models\Submission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/** Emails the team about new leads (Settings > "Notify email"). Failures are logged, never shown to the visitor. */
class LeadNotifier
{
    public static function enquiry(Enquiry $enquiry): void
    {
        $lines = [
            $enquiry->typeLabel().' from '.$enquiry->name,
            'Phone: '.($enquiry->phone ?: '-'),
            'Email: '.($enquiry->email ?: '-'),
        ];
        if ($enquiry->property) {
            $lines[] = 'Property: '.$enquiry->property->title.' ('.$enquiry->property->reference.')';
        }
        if ($enquiry->preferred_date) {
            $lines[] = 'Preferred viewing: '.$enquiry->preferred_date->format('D j M Y').' '.(Enquiry::TIMES[$enquiry->preferred_time] ?? '');
        }
        $lines[] = '';
        $lines[] = (string) $enquiry->message;
        $lines[] = '';
        $lines[] = 'Open in dashboard: '.route('admin.enquiries.show', $enquiry);

        self::send('New '.strtolower($enquiry->typeLabel()).': '.$enquiry->name, implode("\n", $lines));
    }

    public static function submission(Submission $submission): void
    {
        self::send('New property submission: '.$submission->location, implode("\n", [
            $submission->name.' wants to list a property in '.$submission->location.'.',
            'Phone: '.$submission->phone,
            '',
            'Review it: '.route('admin.submissions.show', $submission),
        ]));
    }

    private static function send(string $subject, string $body): void
    {
        $to = trim(Setting::get('notify_email'));
        if ($to === '' || ! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        try {
            Mail::raw($body, fn ($m) => $m->to($to)->subject($subject));
        } catch (Throwable $e) {
            Log::warning('Lead notification email failed: '.$e->getMessage());
        }
    }
}
