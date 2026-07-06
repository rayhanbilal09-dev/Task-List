<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('tasks', TaskController::class)->middleware('auth');
Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])->name('tasks.toggle')->middleware('auth');
Route::post('tasks/{task}/confirm', [TaskController::class, 'confirm'])->name('tasks.confirm')->middleware('auth');
Route::get('tasks/{task}/upload-photo', [TaskController::class, 'showUploadPhoto'])->name('tasks.upload-photo')->middleware('auth');
Route::post('tasks/{task}/store-photo', [TaskController::class, 'storePhoto'])->name('tasks.store-photo')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
