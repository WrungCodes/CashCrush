<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Open Auth Routes

Route::post('/register', [AuthenticationController::class, 'register'])->name('user.register');

Route::post('/verify-email/{token}', [AuthenticationController::class, 'validateEmail'])->name('user.verify');

Route::post('/verify-email-resend', [AuthenticationController::class, 'resendValidateEmail'])->name('user.reverify');

Route::post('/forgot-password', [AuthenticationController::class, 'forgotPassword'])->name('user.forgot-password');

Route::post('/reset-password/{token}', [AuthenticationController::class, 'resetPassword'])->name('user.reset-password');


Route::post('/login', [AuthenticationController::class, 'login'])->name('user.login');

Route::group(['middleware' => ['jwt.verify', 'email.verify']], function () {
    Route::get('/user', [AuthenticationController::class, 'getUser'])->name('user.get');
    Route::get('/profile', [AuthenticationController::class, 'getUserProfile'])->name('profile.get');

    Route::name('item.')->prefix('item')->middleware('store')->group(function () {
        Route::post('/buy', [InventoryController::class, 'buyItem'])->name('item.buy');
        Route::post('/use', [InventoryController::class, 'useItem'])->name('item.use');

        Route::get('/getall', [ItemController::class, 'getAll'])->name('item.getall');

        Route::get('/inventory', [InventoryController::class, 'getInventory'])->name('item.inventory');
    });
});

Route::group(['middleware' => ['jwt.verify', 'admin']], function () {
    Route::name('admin.')->prefix('admin')->group(function () {

        Route::name('item.')->prefix('item')->group(function () {
            Route::post('/create', [ItemController::class, 'create'])->name('item.create');
            Route::patch('/edit/{uid}', [ItemController::class, 'edit'])->name('item.edit');
            Route::get('/get/{uid}', [ItemController::class, 'get'])->name('item.get');
            Route::get('/getall', [ItemController::class, 'getAll'])->name('item.getall');
            Route::delete('/delete/{uid}', [ItemController::class, 'delete'])->name('item.delete');
        });

        Route::name('user.')->prefix('user')->group(function () {
        });
    });
});

Route::group(['middleware' => ['jwt.verify', 'super.admin']], function () {
    Route::name('super.')->prefix('super')->group(function () {

        Route::name('admin.')->prefix('admin')->group(function () {
            Route::post('/register', [AuthenticationController::class, 'createAdmin'])->name('user.register');
        });
    });
});



Route::group(['middleware' => ['jwt.fresh']], function () {
    Route::get('/refresh-token', [AuthenticationController::class, 'refreshToken'])->name('user.refresh');
});
