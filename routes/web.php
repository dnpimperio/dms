<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Redirect to appropriate dashboard based on role
    Route::get('/dashboard', function () {
        switch (auth()->user()->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'tenant':
                return redirect()->route('tenant.dashboard');
            case 'staff':
                return redirect()->route('staff.dashboard');
            default:
                return redirect()->route('login');
        }
    })->name('dashboard');

    // Admin routes
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.dashboard');
        Route::resource('room-assignments', \App\Http\Controllers\RoomAssignmentController::class);
        Route::get('room-assignments/check-availability', [\App\Http\Controllers\RoomAssignmentController::class, 'checkAvailability'])
            ->name('room-assignments.check-availability');
        Route::patch('room-assignments/{roomAssignment}/end', [\App\Http\Controllers\RoomAssignmentController::class, 'end'])
            ->name('room-assignments.end');
        Route::resource('rooms', \App\Http\Controllers\RoomController::class);
        Route::resource('tenants', \App\Http\Controllers\TenantController::class);
    });

    // Tenant routes
    Route::middleware(['role:tenant'])->group(function () {
        Route::get('/tenant/dashboard', [App\Http\Controllers\TenantDashboardController::class, 'index'])->name('tenant.dashboard');
        Route::resource('maintenance-requests', \App\Http\Controllers\MaintenanceRequestController::class);
    });

    // Staff routes
    Route::middleware(['role:staff'])->group(function () {
        Route::get('/staff/dashboard', [App\Http\Controllers\StaffDashboardController::class, 'index'])->name('staff.dashboard');
        Route::patch('/maintenance-tasks/{task}/status', [App\Http\Controllers\MaintenanceRequestController::class, 'updateStatus'])
            ->name('maintenance-tasks.update-status');
    });
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin routes
    Route::middleware(['admin'])->name('admin.')->prefix('admin')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::patch('users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::resource('tenants', \App\Http\Controllers\Admin\TenantController::class);
        Route::resource('rooms', \App\Http\Controllers\Admin\RoomController::class);
        Route::patch('rooms/{room}/update-status', [\App\Http\Controllers\Admin\RoomController::class, 'updateStatus'])->name('rooms.update-status');
    });
});

require __DIR__.'/auth.php';
