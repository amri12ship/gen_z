<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\AffiliateClickController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [CatalogController::class, 'index'])->name('products.index');
Route::get('/produk/{slug}', [CatalogController::class, 'show'])->name('products.show');
Route::post('/produk/{product}/review', [CatalogController::class, 'storeReview'])->name('reviews.store');
Route::get('/go/{product}', [AffiliateClickController::class, 'go'])->name('affiliate.go');

// Cadangan: layani file dari public/uploads lewat Laravel bila web server
// tidak mengarah langsung ke folder public. Tanpa perlu `storage:link`.
Route::get('/uploads/{path}', function (string $path) {
    $full = public_path('uploads/'.ltrim($path, '/'));

    abort_unless(is_file($full), 404);

    return response()->file($full);
})->where('path', '.*')->name('uploads.serve');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
    });

    Route::middleware('admin')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('products', ProductController::class)->except('show');
        Route::resource('categories', CategoryController::class)->except('show');

        Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::patch('reviews/{review}/toggle', [ReviewController::class, 'toggle'])->name('reviews.toggle');
        Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

        Route::get('statistics', [StatisticsController::class, 'index'])->name('statistics.index');

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
