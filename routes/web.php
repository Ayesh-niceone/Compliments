<?php

use App\Http\Controllers\{
    CompletionTypeController,
    ComplimentController,
    DepartmentController,
    CustomerController,
    StatusController,
    UserController
};
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/', function(){ return redirect()->route('login'); });


Route::resource('compliments', ComplimentController::class);
Route::resource('departments', DepartmentController::class);
Route::resource('statuses', StatusController::class);
Route::resource('completion_types', CompletionTypeController::class);
Route::resource('users', UserController::class);

Route::put('/compliments/{compliment}/assign-care-user', [ComplimentController::class, 'assignCareUser'])
    ->name('compliments.assignCareUser');
require __DIR__.'/auth.php';
