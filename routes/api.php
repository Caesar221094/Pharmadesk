<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TicketApiController;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\TicketModuleController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Ticket CRUD API
    Route::apiResource('tickets', TicketApiController::class);

    // Master data APIs
    Route::get('ticket-categories', [TicketCategoryController::class, 'index']);
    Route::get('ticket-modules', [TicketModuleController::class, 'index']);
    Route::get('pharmacies', [PharmacyController::class, 'index']);
    Route::get('users', [UserController::class, 'index']);
});
