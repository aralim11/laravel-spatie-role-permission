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

    // Permission Group Route
    Route::group(['middleware' => ['role_or_permission:permission.group.view']], function () {
        Route::get('/permission-group', [PermissionGroupController::class, 'index']); // Show All User
        Route::post('/permission-group-store', [PermissionGroupController::class, 'storePermissionGroup']); // Store Permission group Name to Database
        Route::get('/permission-group-view', [PermissionGroupController::class, 'viewPermissionGroup']); // View Permission Group List
        Route::get('/permission-group-edit/{id}', [PermissionGroupController::class, 'editPermissionGroup']); // Edit Permission Group List
        Route::post('/permission-group-update', [PermissionGroupController::class, 'updatePermissionGroup']); // Update Permission Group List
    });

    // Permission Route
    Route::group(['middleware' => ['role_or_permission:permission.view']], function () {
        Route::get('/permission', [PermissionController::class, 'index']); // Show All Permission
        Route::post('/permission-store', [PermissionController::class, 'storePermission']); // Store Permission Name to Database
        Route::get('/permission-view', [PermissionController::class, 'viewPermission']); // View Permission List
        Route::get('/permission-edit/{id}', [PermissionController::class, 'editPermission']); // Edit Permission List
        Route::post('/permission-update', [PermissionController::class, 'updatePermission']); // Update Permission
    });

    // Category Route
    Route::group(['middleware' => ['role_or_permission:category.view']], function () {
        Route::get('/category', [CategoryController::class, 'index']); // Show All Category
        Route::post('/category-store', [CategoryController::class, 'storeCategory']); // Store Category Name to Database
        Route::get('/category-view', [CategoryController::class, 'viewCategory']); // View Category Name
        Route::get('/category-edit/{id}', [CategoryController::class, 'editCategory']); // Edit Category Name
        Route::post('/category-update', [CategoryController::class, 'updateCategory']); // Update Category Name
    });

    // Blog Route
    Route::group(['middleware' => ['role_or_permission:blog.view']], function () {
        Route::get('/blog', [BlogPostController::class, 'index']); // Show All Blog Post
        Route::post('/blog-store', [BlogPostController::class, 'storeBlog']); // Store Blog to Database
        Route::get('/blog-view', [BlogPostController::class, 'viewBlog']); // View Blog
        Route::get('/blog-edit/{id}', [BlogPostController::class, 'editBlog']); // Edit Blog
        Route::post('/blog-update', [BlogPostController::class, 'updateBlog']); // Update Blog
        Route::delete('/blog-delete/{id}', [BlogPostController::class, 'deleteBlog']); // Delete Blog
    });

    // Role Route
    Route::group(['middleware' => ['role_or_permission:role.view']], function () {
        Route::get('/role', [RoleController::class, 'index']); // Show All Role
        Route::post('/role-store', [RoleController::class, 'roleStore']); // Store Role Name to Database
        Route::get('/role-view', [RoleController::class, 'viewRole']); // View Role
        Route::get('/role-edit/{id}', [RoleController::class, 'editRole']); // Edit Role
        Route::post('/role-update', [RoleController::class, 'editUpdate']); // Update Role
    });

    //User Route
    Route::group(['middleware' => ['role_or_permission:user.view']], function () {
        Route::get('/users', [UserController::class, 'index']); // Show All User View
        Route::get('/user-view', [UserController::class, 'showUser']); // Show All Users Ajax request
        Route::post('/user-store', [UserController::class, 'storeUser']); // Store New User To Database
        Route::get('/user-edit/{id}', [UserController::class, 'editUser']); // Edit User Data View
        Route::post('/user-update', [UserController::class, 'updateUser']); // Update User
    });
});
