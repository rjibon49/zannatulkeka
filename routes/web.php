<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ResumeItemController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\MediaLibraryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\PortfolioItemController;

Route::get('/', function () {
    return view('welcome');
});

// ড্যাশবোর্ড রাউট
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'dashboard.access'])->name('dashboard');


// =======================================================
// CMS Backend Routes
// =======================================================
Route::middleware(['auth', 'dashboard.access'])->group(function () {
    
    // Breeze Default Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ---------------------------------------------------
    // Media Library & Articles (Super Admin, Admin এবং Contributor উভয়েই এক্সেস পাবে)
    // ---------------------------------------------------
    Route::middleware('role:super_admin,admin,contributor')->group(function () {
        Route::resource('media', MediaLibraryController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('articles', ArticleController::class)->except(['destroy']);
    });

    // শুধু Super Admin এবং Admin আর্টিকেল ডিলিট করতে পারবে
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::delete('articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    });

    // ---------------------------------------------------
    // Core Settings, Categories, Users & Portfolio (শুধুমাত্র Super Admin ও Admin এক্সেস পাবে)
    // ---------------------------------------------------
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('resumes', ResumeItemController::class); // সিভি-র অন্যান্য আইটেম (যেমন: Experience/Education) এর জন্য
        
        // --- নতুন Portfolio (CV/Resume Info) 라উট ---
        Route::get('/portfolio', [PortfolioController::class, 'edit'])->name('portfolio.edit');
        Route::post('/portfolio', [PortfolioController::class, 'update'])->name('portfolio.update');

        Route::resource('portfolio-items', PortfolioItemController::class)->only(['store', 'destroy']);
        
        Route::resource('settings', SettingController::class);
        Route::resource('users', UserController::class);
        Route::resource('galleries', GalleryController::class);
        
        // Route::resource('user-profile', UserProfileController::class); // এটি আর লাগছে না, তাই রিমুভ করা হলো
    });

});

require __DIR__.'/auth.php';