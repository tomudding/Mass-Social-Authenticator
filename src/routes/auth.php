<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('auth.login');

// Do not use the `guest` middleware, otherwise the route is not accessible once logged in.
Route::get('auth/{provider}', [AuthenticatedSessionController::class, 'initialise'])
    ->where('provider', '(facebook|twitter)')
    ->name('auth.initialise');

// Do not use the `guest` middleware, otherwise the route is not accessible once logged in.
Route::get('auth/{provider}/callback', [AuthenticatedSessionController::class, 'store'])
    ->where('provider', '(facebook|twitter)')
    ->name('auth.store');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('auth.logout');
