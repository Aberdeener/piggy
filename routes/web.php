<?php

use App\Http\Controllers\ProfileController;
use App\Http\Resources\AccountResource;
use App\Http\Resources\CreditCardResource;
use App\Http\Resources\GoalResource;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'netWorth' => request()->user()->netWorth(),
        'accounts' => AccountResource::collection(request()->user()->accounts),
        'creditCards' => CreditCardResource::collection(request()->user()->creditCards),
        'goals' => GoalResource::collection(request()->user()->goals),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('accounts', \App\Http\Controllers\AccountController::class);
    Route::patch('/accounts/{account}/balance', [\App\Http\Controllers\AccountBalanceController::class, 'update'])->name('accounts.balance.update');
    Route::resource('credit-cards', \App\Http\Controllers\CreditCardController::class);
    Route::patch('/credit-cards/{creditCard}/balance', [\App\Http\Controllers\CreditCardBalanceController::class, 'update'])->name('credit-cards.balance.update');
    Route::resource('goals', \App\Http\Controllers\GoalController::class);
});

require __DIR__.'/auth.php';
