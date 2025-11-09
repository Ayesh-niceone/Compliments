<?php

use App\Http\Controllers\{
    CompletionTypeController,
    ComplimentController,
    DepartmentController,
    PermissionController,
    SettingController,
    StatusController,
    UserController,
    WorkerController,
    RoleController
};
use App\Http\Controllers\ProfileController;
use Hamcrest\Core\Set;
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

    Route::get('/compliments/export/excel', [ComplimentController::class, 'export'])->name('compliments.export');

require __DIR__.'/auth.php';
Route::get('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
Route::resource('workers', WorkerController::class);


Route::get('/compliments/customer/create', [ComplimentController::class, 'createCustomer'])->name('compliments.createCustomer');
Route::post('/compliments/customer/store', [ComplimentController::class, 'storeCustomer'])->name('compliments.storeCustomer');

Route::get('/compliments/worker/create', [ComplimentController::class, 'createWorker'])->name('compliments.createWorker');
Route::post('/compliments/worker/store', [ComplimentController::class, 'storeWorker'])->name('compliments.storeWorker');


Route::resource('roles', RoleController::class);

Route::resource('permissions', PermissionController::class);
