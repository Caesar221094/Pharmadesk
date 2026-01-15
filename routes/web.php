<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\TicketModuleController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('tickets', TicketController::class);
    Route::post('tickets/{ticket}/activities', [TicketController::class, 'addActivity'])->name('tickets.activities.store');
    Route::get('attachments/{attachment}', [TicketController::class, 'showAttachment'])->name('attachments.show');
    
    Route::middleware('role:admin')->group(function () {
        Route::resource('ticket-categories', TicketCategoryController::class)->except(['show']);
        Route::resource('ticket-modules', TicketModuleController::class)->except(['show']);
        Route::resource('users', UserController::class);
    });

    // Admin dan Team Expert dapat mengelola data apotek
    Route::middleware('role:admin,team_expert')->group(function () {
        Route::resource('pharmacies', PharmacyController::class);
    });
});

require __DIR__.'/auth.php';
