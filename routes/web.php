<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ResourceController as AdminResourceController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PortfolioController;
use App\Http\Middleware\EnsureAdminAccess;
use App\Http\Middleware\TrackPageView;
use Illuminate\Support\Facades\Route;

Route::redirect('/login', '/admin/login')->name('login');
Route::get('/', [HomeController::class, 'index'])->middleware(TrackPageView::class)->name('home');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::post('/newsletter', [ContactController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/blog', [BlogController::class, 'index'])->middleware(TrackPageView::class)->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->middleware(TrackPageView::class)->name('blog.show');
Route::post('/blog/{slug}/comments', [BlogController::class, 'comment'])
    ->middleware('throttle:5,1')
    ->name('blog.comments.store');
Route::get('/karya', [PortfolioController::class, 'index'])->middleware(TrackPageView::class)->name('portfolio.index');
Route::get('/karya/{slug}', [PortfolioController::class, 'show'])->middleware(TrackPageView::class)->name('portfolio.show');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
    });

    Route::middleware(['auth', EnsureAdminAccess::class])->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');
        Route::redirect('/service-details', '/admin/services');
        Route::redirect('/blog-post-tags', '/admin/blog-posts');
        Route::redirect('/portfolio-images', '/admin/portofolios');
        Route::get('/{resource}', [AdminResourceController::class, 'index'])->name('resources.index');
        Route::get('/{resource}/create', [AdminResourceController::class, 'create'])->name('resources.create');
        Route::post('/{resource}', [AdminResourceController::class, 'store'])->name('resources.store');
        Route::get('/{resource}/{record}/edit', [AdminResourceController::class, 'edit'])->name('resources.edit');
        Route::patch('/{resource}/{record}/toggle/{field}', [AdminResourceController::class, 'toggle'])->name('resources.toggle');
        Route::put('/{resource}/{record}', [AdminResourceController::class, 'update'])->name('resources.update');
        Route::delete('/{resource}/{record}', [AdminResourceController::class, 'destroy'])->name('resources.destroy');
    });
});
