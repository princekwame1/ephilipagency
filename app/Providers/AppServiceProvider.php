<?php

namespace App\Providers;

use App\Models\Enquiry;
use App\Models\Submission;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::preventLazyLoading(! $this->app->isProduction());

        // Sidebar badges in the dashboard.
        View::composer('components.layouts.admin', function ($view) {
            $view->with('badges', [
                'leads' => Enquiry::where('status', 'new')->count(),
                'submissions' => Submission::where('status', 'pending')->count(),
                'reviews' => Testimonial::where('is_published', false)->count(),
            ]);
        });
    }
}
