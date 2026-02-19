<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ResourceCategoryController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MyBiachController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/**
 * Public Routes
 */
Route::get('/', function () {
    return view('welcome');
})->name('home');

/**
 * Guest Routes (unauthenticated users)
 */
Route::middleware('guest')->group(function () {
    Route::get('/resources/public', [ResourceController::class, 'index'])->name('resources.public');
});

/**
 * Authenticated Routes
 */
Route::middleware(['auth', 'verified'])->group(function () {
    
    /**
     * Dashboard Routes
     */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    /**
     * User Profile Routes
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * Reservation Routes
     */
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('index');
        Route::get('/create', [ReservationController::class, 'create'])->name('create');
        Route::post('/', [ReservationController::class, 'store'])->name('store');
        Route::get('/{reservation}', [ReservationController::class, 'show'])->name('show');
        Route::get('/{reservation}/edit', [ReservationController::class, 'edit'])->name('edit');
        Route::patch('/{reservation}', [ReservationController::class, 'update'])->name('update');
        Route::delete('/{reservation}', [ReservationController::class, 'destroy'])->name('destroy');
        
        // Additional reservation routes
        Route::get('/status/pending', [ReservationController::class, 'pending'])->name('pending');
        Route::get('/status/approved', [ReservationController::class, 'approved'])->name('approved');
    });

    /**
     * Resource Routes
     */
    Route::prefix('resources')->name('resources.')->group(function () {
        Route::get('/', [ResourceController::class, 'index'])->name('index');
        
        // Admin/Manager routes - spécifiques en premier
        Route::middleware(['auth'])->group(function () {
            Route::get('/create', [ResourceController::class, 'create'])->name('create');
            Route::post('/', [ResourceController::class, 'store'])->name('store');
            Route::get('/{resource}/edit', [ResourceController::class, 'edit'])->name('edit');
            Route::patch('/{resource}', [ResourceController::class, 'update'])->name('update');
            Route::delete('/{resource}', [ResourceController::class, 'destroy'])->name('destroy');
            Route::patch('/{resource}/maintenance', [ResourceController::class, 'setMaintenance'])->name('maintenance');
            Route::patch('/{resource}/available', [ResourceController::class, 'setAvailable'])->name('available');
        });
        
        // Routes génériques - après les routes spécifiques
        Route::get('/category/{category}', [ResourceController::class, 'byCategory'])->name('by-category');
        Route::get('/{resource}', [ResourceController::class, 'show'])->name('show');
    });

    /**
     * Resource Category Routes
     */
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [ResourceCategoryController::class, 'index'])->name('index');
        
        // Admin routes - spécifiques en premier
        Route::middleware(['auth'])->group(function () {
            Route::get('/create', [ResourceCategoryController::class, 'create'])->name('create');
            Route::post('/', [ResourceCategoryController::class, 'store'])->name('store');
            Route::get('/{category}/edit', [ResourceCategoryController::class, 'edit'])->name('edit');
            Route::patch('/{category}', [ResourceCategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [ResourceCategoryController::class, 'destroy'])->name('destroy');
        });
        
        // Routes génériques - après les routes spécifiques
        Route::get('/{category}', [ResourceCategoryController::class, 'show'])->name('show');
    });

    /**
     * Incident Routes
     */
    Route::prefix('incidents')->name('incidents.')->group(function () {
        Route::get('/', [IncidentController::class, 'index'])->name('index');
        Route::get('/create', [IncidentController::class, 'create'])->name('create');
        Route::post('/', [IncidentController::class, 'store'])->name('store');
        Route::get('/{incident}', [IncidentController::class, 'show'])->name('show');
        
        // Manager/Admin routes
        Route::middleware(['auth'])->group(function () {
            Route::patch('/{incident}/resolve', [IncidentController::class, 'resolve'])->name('resolve');
            Route::patch('/{incident}/close', [IncidentController::class, 'close'])->name('close');
        });
        
        // Additional routes
        Route::get('/filter/open', [IncidentController::class, 'open'])->name('open');
        Route::get('/filter/critical', [IncidentController::class, 'critical'])->name('critical');
    });

    /**
     * Admin Routes
     */
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'admin'])->name('dashboard');
        Route::get('/approve-reservations', [ReservationController::class, 'index'])->name('reservations');
        Route::patch('/reservations/{reservation}/approve', [ReservationController::class, 'approve'])->name('approve-reservation');
        Route::patch('/reservations/{reservation}/decline', [ReservationController::class, 'decline'])->name('decline-reservation');

        // Activity logging routes
        Route::prefix('activity')->name('activity.')->group(function () {
            Route::get('/', [ActivityLogController::class, 'index'])->name('index');
            Route::get('/{log}', [ActivityLogController::class, 'show'])->name('show');
            Route::get('/user/{userId}', [ActivityLogController::class, 'userActivity'])->name('user');
            Route::get('/export/csv', [ActivityLogController::class, 'export'])->name('export');
            Route::post('/clear-old', [ActivityLogController::class, 'clearOldLogs'])->name('clear-old');
        });
    });

});

/**
 * Authentication Routes
 */
require __DIR__.'/auth.php';

/**
 * Development Routes
 */
Route::get('/db-test', function () {
    try {
        DB::connection()->getPdo();
        return view('dbconn', [
            'dbName' => DB::connection()->getDatabaseName()
        ]);
    } catch (\Exception $e) {
        return "Database connection failed: " . $e->getMessage();
    }
});

Route::get('/mybiach', [MyBiachController::class, 'index'])->name('mybiach');
