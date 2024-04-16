<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/post-login', [LoginController::class, 'postLogin']);
Route::get('/logout', [LoginController::class, 'logOut']);

// user urls
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

// projects url
Route::prefix('users')->as('users.')->middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('dashboard');
    Route::post('/update-profile/{user}', [UserController::class, 'updateProfile'])->name('update_profile');
});

// projects url
Route::prefix('projects')->as('projects.')->middleware(['auth'])->group(function () {
    Route::get('/index', [ProjectController::class, 'index'])->name('project_index');
    Route::post('/datatable', [ProjectController::class, 'datatable'])->name('project_datatable');
    Route::post('/add-website', [ProjectController::class, 'addWebsite'])->name('add_website');
});

// users url
Route::prefix('users')->as('users.')->middleware(['auth'])->group(function () {
    Route::get('/index', [UserController::class, 'index'])->name('user_index');
    Route::post('/datatable', [UserController::class, 'datatable'])->name('user_datatable');
    Route::post('/create-or-update/{user?}', [UserController::class, 'createOrUpdate'])
        ->name('create_or_update');
    Route::get('/delete/{user?}', [UserController::class, 'delete'])->name('user_delete');
});