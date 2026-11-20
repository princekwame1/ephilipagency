<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Property;
use App\Models\Submission;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // Leads per day for the last 30 days (zero-filled), for the bar chart.
        $since = now()->subDays(29)->startOfDay();
        $counts = Enquiry::where('created_at', '>=', $since)
            ->get(['created_at'])
            ->countBy(fn ($e) => $e->created_at->toDateString());
        $chart = collect(range(0, 29))->map(function ($i) use ($since, $counts) {
            $day = $since->copy()->addDays($i);

            return ['date' => $day, 'total' => $counts[$day->toDateString()] ?? 0];
        });

        return view('admin.dashboard', [
            'stats' => [
                'published' => Property::published()->count(),
                'drafts' => Property::where('status', 'draft')->count(),
                'available' => Property::published()->where('availability', 'available')->count(),
                'views' => (int) Property::sum('views'),
                'newLeads' => Enquiry::where('status', 'new')->count(),
                'leadsMonth' => Enquiry::where('created_at', '>=', now()->startOfMonth())->count(),
                'pendingSubmissions' => Submission::where('status', 'pending')->count(),
            ],
            'chart' => $chart,
            'recentLeads' => Enquiry::with('property:id,title,slug')->latest()->take(6)->get(),
            'upcomingViewings' => Enquiry::with('property:id,title,slug')
                ->where('type', 'viewing')
                ->whereIn('status', ['new', 'contacted', 'scheduled'])
                ->whereDate('preferred_date', '>=', Carbon::today())
                ->orderBy('preferred_date')
                ->take(5)
                ->get(),
            'topProperties' => Property::published()->with('cover')->withCount('enquiries')->orderByDesc('views')->take(5)->get(),
            'byOffer' => Property::published()->selectRaw('offer_type, count(*) as total')->groupBy('offer_type')->pluck('total', 'offer_type'),
        ]);
    }
}
