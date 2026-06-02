<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\MediaLibraryController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\PortfolioItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Frontend Routes
|--------------------------------------------------------------------------
| Frontend page design will be added later.
| For now, this only loads the default welcome page.
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Dashboard Home
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'dashboard.access'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| CMS Backend Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'dashboard.access'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Breeze Default Profile Routes
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Content Routes
    |--------------------------------------------------------------------------
    | super_admin, admin, contributor can access these sections.
    | Contributor can create/update content, but delete permission can be limited.
    */

    Route::middleware('role:super_admin,admin,contributor')->group(function () {

        // Media Library
        Route::resource('media', MediaLibraryController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        // Articles
        Route::resource('articles', ArticleController::class)
            ->except(['show', 'destroy']);

        // Gallery
        Route::resource('galleries', GalleryController::class)
            ->except(['show', 'destroy']);

        // Videos
        Route::resource('videos', VideoController::class)
            ->except(['show', 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Delete Routes
    |--------------------------------------------------------------------------
    | Only super_admin and admin can delete content.
    */

    Route::middleware('role:super_admin,admin')->group(function () {

        Route::delete('articles/{article}', [ArticleController::class, 'destroy'])
            ->name('articles.destroy');

        Route::delete('galleries/{gallery}', [GalleryController::class, 'destroy'])
            ->name('galleries.destroy');

        Route::delete('videos/{video}', [VideoController::class, 'destroy'])
            ->name('videos.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Management Routes
    |--------------------------------------------------------------------------
    | Only super_admin and admin can manage categories, tags, portfolio,
    | settings, and contact messages.
    */

    Route::middleware('role:super_admin,admin')->group(function () {

        // Categories
        Route::resource('categories', CategoryController::class)
            ->except(['show']);

        // Tags
        Route::resource('tags', TagController::class)
            ->except(['show']);

        // Portfolio Main Info
        Route::get('/portfolio', [PortfolioController::class, 'edit'])
            ->name('portfolio.edit');

        Route::post('/portfolio', [PortfolioController::class, 'update'])
            ->name('portfolio.update');

        // Portfolio / CV Items
        Route::resource('portfolio-items', PortfolioItemController::class)
            ->only(['store', 'update', 'destroy']);

        // Gallery image management can be handled inside GalleryController.
        // If later you create a separate GalleryImageController, we can add routes for it.

        // Site Settings
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

        // Contact Messages
        Route::resource('contact-messages', ContactMessageController::class)
            ->only(['index', 'show', 'update', 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Super Admin Only Routes
    |--------------------------------------------------------------------------
    | User management should be controlled by super_admin only.
    */

    Route::middleware('role:super_admin')->group(function () {

        Route::resource('users', UserController::class)
            ->except(['show']);
    });
});

require __DIR__ . '/auth.php';