<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CleanerController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SocialLinksController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\TermController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\VisitController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\VisitsListController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Website\WebsiteController;
use App\Http\Controllers\DahboardController;
use App\Http\Controllers\DashboardFilterController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\SecureVendorController;
use App\Http\Controllers\InstallationGuideController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Set the landing page as the default route
Route::get('/', [LandingController::class, 'index'])->name('home');

Route::get('/link', function () {
    $targetFolder = storage_path('app/public');
    $linkFolder = $_SERVER['DOCUMENT_ROOT'] . '/public/storage'; // Added a slash (/)

    if (!file_exists($linkFolder)) {
        symlink($targetFolder, $linkFolder);
        return 'Symlink created successfully.';
    } else {
        return 'Symlink already exists.';
    }
});

Route::get('/opt', function () {
    Artisan::call('route:clear');
    Artisan::call('optimize:clear');
    return back();
})->name('refresh');

Route::middleware('guest')->group(function () {
    // login routes
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::middleware(['auth', 'setLocale'])->group(function () {
    // dashboard routes
    Route::get('/dashboard', [DahboardController::class, 'index'])->name('dashboard');
    Route::get('/visits-list', [VisitsListController::class, 'index'])->name('index');

    // profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');
    Route::post('/profile/update-picture', [ProfileController::class, 'updatePicture'])->name('profile.update.picture');

    // locale routes
    Route::post('locale', [VisitsListController::class, 'setLocale'])->name('setLocale');

    // users routes
    Route::resource('users', UserController::class)->except('show');



    // roles routes
    Route::resource('roles', RoleController::class)->except('show');


    // logout routes
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');



    // ====================================================== Website Routes ======================================================

    Route::middleware(['setLocale'])->group(function () {
        // locale routes
        Route::post('locale', [WebsiteController::class, 'setLocale'])->name('setLocale');
        Route::get('/website', [WebsiteController::class, 'index'])->name('website.index');

    });




    // Admin Backup and Cache Management Routes
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('settings', 'App\Http\Controllers\Admin\SettingController@index')->name('settings.index');
        Route::get('settings/edit', 'App\Http\Controllers\Admin\SettingController@edit')->name('settings.edit');
        Route::post('settings/update', 'App\Http\Controllers\Admin\SettingController@update')->name('settings.update');
        Route::get('settings/backup', 'App\Http\Controllers\Admin\BackupController@index')->name('settings.backup');
        Route::post('settings/backup/create', 'App\Http\Controllers\Admin\BackupController@create')->name('settings.backup.create');
        Route::post('settings/backup/delete/{filename}', 'App\Http\Controllers\Admin\BackupController@delete')->name('settings.backup.delete');
        Route::get('settings/backup/download/{filename}', 'App\Http\Controllers\Admin\BackupController@download')->name('settings.backup.download');

        Route::get('settings/cache', 'App\Http\Controllers\Admin\CacheController@index')->name('settings.cache');
        Route::post('settings/cache/clear', 'App\Http\Controllers\Admin\CacheController@clear')->name('settings.cache.clear');
        Route::post('settings/cache/optimize', 'App\Http\Controllers\Admin\CacheController@optimize')->name('settings.cache.optimize');
    });

    // Product management
    Route::middleware(['auth', 'license.permission:products-list'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/create', [ProductController::class, 'create'])->middleware('license.permission:products-create')->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->middleware('license.permission:products-create')->name('products.store');
        Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->middleware('license.permission:products-edit')->name('products.edit');
        Route::put('products/{product}', [ProductController::class, 'update'])->middleware('license.permission:products-edit')->name('products.update');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->middleware('license.permission:products-delete')->name('products.destroy');
        Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->middleware('license.permission:products-toggle-status')->name('products.toggle-status');
    });

    // Category management
    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('categories', [CategoryController::class, 'index'])->middleware('license.permission:categories-list')->name('categories.index');
        Route::post('categories', [CategoryController::class, 'store'])->middleware('license.permission:categories-create')->name('categories.store');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->middleware('license.permission:categories-edit')->name('categories.update');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->middleware('license.permission:categories-delete')->name('categories.destroy');
    });

    // License management
    Route::middleware(['auth', 'license.permission:licenses-list'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('licenses', [LicenseController::class, 'index'])->name('licenses.index');
        Route::get('licenses/create', [LicenseController::class, 'create'])->middleware('license.permission:licenses-create')->name('licenses.create');
        Route::post('licenses', [LicenseController::class, 'store'])->middleware('license.permission:licenses-create')->name('licenses.store');
        Route::get('licenses/{license}', [LicenseController::class, 'show'])->name('licenses.show');
        Route::get('licenses/{license}/edit', [LicenseController::class, 'edit'])->middleware('license.permission:licenses-edit')->name('licenses.edit');
        Route::put('licenses/{license}', [LicenseController::class, 'update'])->middleware('license.permission:licenses-edit')->name('licenses.update');
        Route::delete('licenses/{license}', [LicenseController::class, 'destroy'])->middleware('license.permission:licenses-delete')->name('licenses.destroy');
        Route::post('licenses/{license}/regenerate-code', [LicenseController::class, 'regenerateActivationCode'])->middleware('license.permission:licenses-regenerate-code')->name('licenses.regenerate-code');
        Route::post('licenses/{license}/regenerate-activation-code', [LicenseController::class, 'regenerateActivationCode'])->middleware('license.permission:licenses-regenerate-code')->name('licenses.regenerate-activation-code');
        Route::post('licenses/{license}/activate', [LicenseController::class, 'activate'])->middleware('license.permission:licenses-activate')->name('licenses.activate');
        Route::post('licenses/{license}/deactivate', [LicenseController::class, 'deactivate'])->middleware('license.permission:licenses-deactivate')->name('licenses.deactivate');
        Route::patch('licenses/{license}/revoke', [LicenseController::class, 'revoke'])->middleware('license.permission:licenses-revoke')->name('licenses.revoke');
    });
});

// Documentation route with middleware
Route::get('/integration-docs', function () {
    return view('integration-docs');
})->middleware(['auth', 'license.permission:license-docs-access'])->name('integration-docs');

Route::get('/secure-connection-demo', function () {
    return view('secure-connection-demo');
})->name('secure-connection-demo');

Route::get('/simple-integration-guide', function () {
    return view('simple-integration-guide');
})->name('simple-integration-guide');

Route::get('/secure-vendor-guide', [SecureVendorController::class, 'index'])->name('secure-vendor.guide');

Route::get('/secure-vendor-integration', [App\Http\Controllers\SecureVendorController::class, 'index'])
    ->middleware(['auth'])
    ->name('secure-vendor-integration');

Route::get('/installation-guide', [InstallationGuideController::class, 'index'])
    ->middleware(['auth'])
    ->name('installation.guide');

// Package development guide route
Route::get('/package-development', function () {
    return view('package-development');
})->middleware(['auth'])->name('package.development');
