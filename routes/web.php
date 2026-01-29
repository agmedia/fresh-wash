<?php

use App\Http\Controllers\Admin\Catalog\ServiceController;
use App\Http\Controllers\Admin\Catalog\TariffController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Settings\ProfileSettingsController;
use App\Http\Controllers\Admin\Settings\RolePermissionController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PageCategoryController;
use App\Http\Controllers\Admin\PageTagController;
use App\Http\Controllers\Admin\NotificationLogController as AdminNotificationLogController;
use App\Http\Controllers\Admin\DeviceController as AdminDeviceController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\LocationController as AdminLocationController;
use App\Http\Controllers\Admin\LockerController as AdminLockerController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Illuminate\Http\Request;
use App\Http\Controllers\Front\{App\AccessController,
    AppController,
    DashboardController as FrontDashboardController,
    Info\LocationsController,
    ProfileController as FrontProfileController,
    OrderController,
    InfoController,
    BlogController,
    LegalController};

// PWA homepage
Route::get('/', [AppController::class, 'home'])->name('home');

Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    // For non-admin users, the "dashboard" is the PWA homepage.
    // Admins should use /admin/dashboard.
    return redirect('/');
})->name('dashboard');


// INFO WEB
Route::prefix('info')->as('info.')->group(function () {
    Route::get('/', [InfoController::class, 'home'])->name('home');
    Route::get('/kako-radi', [InfoController::class, 'how'])->name('how');
    Route::get('/lokacije', [LocationsController::class, 'index'])->name('locations.index');
    Route::get('/cjenik', [InfoController::class, 'pricing'])->name('pricing');
    Route::get('/faq', [InfoController::class, 'faq'])->name('faq');
    Route::get('/kontakt', [InfoController::class, 'contact'])->name('contact');
    
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('/blog/kategorija/{slug}', [BlogController::class, 'category'])->name('blog.category');
    Route::get('/blog/tag/{slug}', [BlogController::class, 'tag'])->name('blog.tag');
    
    Route::get('/uvjeti', [LegalController::class, 'terms'])->name('terms');
    Route::get('/privatnost', [LegalController::class, 'privacy'])->name('privacy');
});

// Auth PWA
Route::middleware(['auth','verified'])->prefix('app')->as('app.')->group(function () {
    Route::get('/', [FrontDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [FrontProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil', [FrontProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profil', [FrontProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/narudzbe', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/narudzbe/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/nova-narudzba', [OrderController::class, 'create'])->name('orders.create');

    Route::get('/access', [AccessController::class, 'show']);
    Route::post('/access/open', [AccessController::class, 'open']);
    Route::get('/access/command/{command}', [AccessController::class, 'commandStatus']);
});

/**
 *
 */
Route::get('/menu-main.html', function () {
    return response()
        ->view('front.menu-main')
        ->header('Content-Type', 'text/html; charset=UTF-8');
})->name('front.menu-main');


Route::middleware([
    'auth',
    'verified',
    'can:access-admin', // Gate ili Bouncer ability
])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::resource('locations', AdminLocationController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'show']);
        
        Route::resource('lockers', AdminLockerController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'show']);
        
        Route::resource('reservations', AdminReservationController::class)
            ->only(['index','create','store','edit','update','show']);
        
        Route::resource('orders', AdminOrderController::class)
            ->only(['index','show','edit','update']);
        
        Route::resource('payments', AdminPaymentController::class)
            ->only(['index','show','edit','update']);
        
        Route::resource('users', AdminUserController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'show']);
        
        /**
         *
         */
        Route::prefix('catalog')->as('catalog.')->group(function () {
            Route::resource('services', ServiceController::class)
                ->only(['index','create','store','show','edit','update']);
            
            Route::resource('tariffs', TariffController::class)
                ->only(['index','create','store','show','edit','update']);
        });
        
        Route::resource('devices', AdminDeviceController::class)
            ->only(['index','create','store','edit','update','show']);
        
        Route::resource('notifications', AdminNotificationLogController::class)
            ->only(['index', 'show']);
        
        Route::resource('page-categories', PageCategoryController::class)->except(['show','destroy']);
        Route::resource('page-tags', PageTagController::class)->except(['show','destroy']);
        Route::resource('pages', PageController::class)->only(['index','create','store','edit','update','show']);
        /**
         *
         */
        Route::prefix('settings')->as('settings.')->group(function () {
            Route::get('roles', [RolePermissionController::class, 'index'])
                ->name('roles.index');
            
            Route::post('roles/matrix', [RolePermissionController::class, 'storeMatrix'])
                ->name('roles.matrix');
            
            Route::get('/profile', [ProfileSettingsController::class, 'edit'])
                ->name('settings.profile.edit');
            Route::put('/profile', [ProfileSettingsController::class, 'update'])
                ->name('settings.profile.update');
        });
    });

require __DIR__.'/settings.php';
