<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Livewire\Login;
use App\Livewire\Register;
use App\Livewire\PatientOnboarding;
use App\Livewire\Chatbot;

/*
|--------------------------------------------------------------------------
| Livewire Asset Handling
| (WAJIB kalau pakai subfolder / asset_prefix)
|--------------------------------------------------------------------------
*/
Livewire::setUpdateRoute(function ($handle) {
    return Route::post(
        config('app.asset_prefix') . '/livewire/update',
        $handle
    );
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(
        config('app.asset_prefix') . '/livewire/livewire.js',
        $handle
    );
});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| GUEST ROUTES (BELUM LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT
|--------------------------------------------------------------------------
| Setelah login:
| - Jika BELUM isi data pasien → onboarding
| - Jika SUDAH → chatbot
*/
Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!$user->patient) {
        return redirect()->route('onboarding');
    }

    return redirect()->route('chat');
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ONBOARDING PASIEN (WAJIB PERTAMA)
    |--------------------------------------------------------------------------
    */
    Route::get('/onboarding', PatientOnboarding::class)
        ->name('onboarding');

    /*
    |--------------------------------------------------------------------------
    | CHATBOT (HARUS SUDAH ADA DATA PASIEN)
    |--------------------------------------------------------------------------
    */
    Route::get('/chat', Chatbot::class)
        ->middleware('has.patient')
        ->name('chat');
});
