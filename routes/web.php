<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

// ---------------- Public website ----------------
Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/about', [SiteController::class, 'about'])->name('about');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::post('/contact', [SiteController::class, 'sendContact'])->name('contact.send')->middleware('throttle:5,1');
Route::get('/blog', [SiteController::class, 'blog'])->name('blog');
Route::get('/blog/{post}', [SiteController::class, 'post'])->name('blog.show');
Route::get('/list-your-property', [SiteController::class, 'listProperty'])->name('submit');
Route::post('/list-your-property', [SiteController::class, 'storeSubmission'])->name('submit.store')->middleware('throttle:3,10');
Route::get('/review', [SiteController::class, 'review'])->name('review');
Route::post('/review', [SiteController::class, 'storeReview'])->name('review.store')->middleware('throttle:3,10');
Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('sitemap');

Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/saved', [PropertyController::class, 'saved'])->name('properties.saved');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');
Route::post('/properties/{property}/enquire', [PropertyController::class, 'enquire'])->name('properties.enquire')->middleware('throttle:5,1');

// Old site URLs keep working (links shared on social media, search results).
Route::get('/props/category/{slug}', fn (string $slug) => redirect()->route('properties.index', ['category' => $slug], 301));
Route::get('/props/offer_type/{type}', fn (string $type) => redirect()->route('properties.index', ['offer' => ['for-sale' => 'sale', 'rent' => 'rent', 'lease' => 'lease'][$type] ?? null], 301));
Route::get('/props/prop-details/{slug}', fn (string $slug) => redirect('/properties/'.$slug, 301));
Route::redirect('/services', '/about', 301);
Route::redirect('/login', '/admin/login');

// ---------------- Admin dashboard ----------------
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [Admin\AuthController::class, 'show'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'login'])->middleware('throttle:10,1');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'logout'])->name('logout');
        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        Route::resource('properties', Admin\PropertyController::class)->except('show');
        Route::post('properties/{property}/duplicate', [Admin\PropertyController::class, 'duplicate'])->name('properties.duplicate');
        Route::patch('properties/{property}/quick', [Admin\PropertyController::class, 'quick'])->name('properties.quick');
        Route::post('properties/{property}/images/{image}/cover', [Admin\PropertyController::class, 'makeCover'])->name('properties.images.cover');
        Route::delete('properties/{property}/images/{image}', [Admin\PropertyController::class, 'destroyImage'])->name('properties.images.destroy');

        Route::get('enquiries/export', [Admin\EnquiryController::class, 'export'])->name('enquiries.export');
        Route::resource('enquiries', Admin\EnquiryController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::resource('submissions', Admin\SubmissionController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::post('submissions/{submission}/convert', [Admin\SubmissionController::class, 'convert'])->name('submissions.convert');

        Route::resource('categories', Admin\CategoryController::class)->except('show');
        Route::resource('amenities', Admin\AmenityController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('agents', Admin\AgentController::class)->except('show');
        Route::resource('posts', Admin\PostController::class)->except('show');
        Route::resource('testimonials', Admin\TestimonialController::class)->except('show');

        Route::get('content/{page?}', [Admin\ContentController::class, 'edit'])->name('content');
        Route::put('content/{page}', [Admin\ContentController::class, 'update'])->name('content.update');
        Route::post('content/{page}/reset', [Admin\ContentController::class, 'reset'])->name('content.reset');
        Route::post('testimonials/{testimonial}/publish', [Admin\TestimonialController::class, 'publish'])->name('testimonials.publish');

        Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings');
        Route::put('settings', [Admin\SettingController::class, 'update']);
        Route::get('account', [Admin\AccountController::class, 'edit'])->name('account');
        Route::put('account', [Admin\AccountController::class, 'update'])->middleware('throttle:6,1');
    });
});
