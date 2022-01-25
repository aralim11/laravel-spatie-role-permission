<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionGroupController;


Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    // Home Route
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // User Route
    Route::get('/user', [UserController::class, 'index']); // Show All User

    // Permission Group Route
    Route::get('/permission-group', [PermissionGroupController::class, 'index']); // Show All User
    Route::post('/permission-group-store', [PermissionGroupController::class, 'storePermissionGroup']); // Store Permission group Name to Database
    Route::get('/permission-group-view', [PermissionGroupController::class, 'viewPermissionGroup']); // View Permission Group List
    Route::get('/permission-group-edit/{id}', [PermissionGroupController::class, 'editPermissionGroup']); // Edit Permission Group List
    Route::post('/permission-group-update', [PermissionGroupController::class, 'updatePermissionGroup']); // Update Permission Group List

    // Permission Route
    Route::get('/permission', [PermissionController::class, 'index']); // Show All Permission
    Route::post('/permission-store', [PermissionController::class, 'storePermission']); // Store Permission Name to Database
    Route::get('/permission-view', [PermissionController::class, 'viewPermission']); // View Permission List
    Route::get('/permission-edit/{id}', [PermissionController::class, 'editPermission']); // Edit Permission List
    Route::post('/permission-update', [PermissionController::class, 'updatePermission']); // Update Permission

    // Category Route
    Route::get('/category', [CategoryController::class, 'index']); // Show All Category

    // Blog Route
    Route::get('/blog', [BlogPostController::class, 'index']); // Show All Blog Post

    // Role Route
    Route::get('/role', [RoleController::class, 'index']); // Show All Role
    Route::post('/role-store', [RoleController::class, 'roleStore']); // Store Role Name to Database
    Route::get('/role-view', [RoleController::class, 'viewRole']); // View Role
    Route::get('/role-edit/{id}', [RoleController::class, 'editRole']); // Edit Role
    Route::post('/role-update', [RoleController::class, 'editUpdate']); // Update Role

    //User Route
    Route::get('/register', [RegisterController::class, 'index']); // Show All User
});