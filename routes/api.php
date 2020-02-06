<?php

use App\Announcement;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CoinDealController;
use App\Http\Controllers\CoinHistoryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LifeController;
use App\Http\Controllers\NairaHistoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\TransactionController;
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

Route::get('/', function () {
    return 200;
});

//Open Auth Routes

Route::post('/register', [AuthenticationController::class, 'register'])->name('user.register');

Route::post('/verify-email/{token}', [AuthenticationController::class, 'validateEmail'])->name('user.verify');

Route::post('/verify-email-resend', [AuthenticationController::class, 'resendValidateEmail'])->name('user.reverify');

Route::post('/forgot-password', [AuthenticationController::class, 'forgotPassword'])->name('user.forgot-password');

Route::post('/reset-password/{token}', [AuthenticationController::class, 'resetPassword'])->name('user.reset-password');


Route::post('/login', [AuthenticationController::class, 'login'])->name('user.login');

Route::group(['middleware' => ['encrypt', 'jwt.verify', 'email.verify']], function () {

    // Route::get('/activity', [AuthenticationController::class, 'getUser'])->name('user.get');

    Route::get('/user', [AuthenticationController::class, 'getUser'])->name('user.get');
    Route::get('/profile', [AuthenticationController::class, 'getUserProfile'])->name('profile.get');

    Route::name('item.')->prefix('item')->middleware('store')->group(function () {
        Route::post('/buy', [InventoryController::class, 'buyItem'])->name('buy');
        Route::post('/use', [InventoryController::class, 'useItem'])->name('use');

        Route::post('/add', [InventoryController::class, 'addItem'])->name('add');

        Route::get('/getall', [ItemController::class, 'getAll'])->name('getall');

        Route::get('/inventory', [InventoryController::class, 'getInventory'])->name('inventory');
    });

    Route::name('lifes.')->prefix('lifes')->group(function () {
        Route::post('/use', [LifeController::class, 'useLife'])->name('use');
        Route::post('/add', [LifeController::class, 'addLife'])->name('add');

        Route::get('/get', [LifeController::class, 'getLife'])->name('get');
    });

    Route::name('notification.')->prefix('notification')->group(function () {
        Route::get('/get', [NotificationController::class, 'get'])->name('get');
        Route::post('/read', [NotificationController::class, 'read'])->name('read');
    });

    Route::name('history.')->prefix('history')->group(function () {
        Route::get('/coin/get', [CoinHistoryController::class, 'getUser'])->name('coin.get');
        Route::get('/naira/get', [NairaHistoryController::class, 'getUser'])->name('naira.get');
    });

    Route::name('announcement.')->prefix('announcement')->group(function () {
        Route::get('/getall', [Announcement::class, 'getAll'])->name('getall');
    });

    Route::name('points.')->prefix('points')->group(function () {
        Route::get('/getall', [PointController::class, 'getAll'])->name('getall');

        Route::get('/get', [PointController::class, 'get'])->name('get');

        Route::post('/add', [PointController::class, 'add'])->name('add');
    });

    Route::name('coin.')->prefix('coin')->group(function () {
        Route::get('/get', [CoinDealController::class, 'get'])->name('get');

        Route::post('/buy', [CoinDealController::class, 'buy'])->name('buy');
    });

    Route::name('transactions.')->prefix('transactions')->group(function () {
        Route::get('/get', [TransactionController::class, 'get'])->name('get');
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

        Route::name('history.')->prefix('history')->group(function () {
            Route::get('/coin/get', [CoinHistoryController::class, 'getAll'])->name('coin.get');
            Route::get('/naira/get', [NairaHistoryController::class, 'getAll'])->name('naira.get');
        });

        Route::name('notification.')->prefix('notification')->group(function () {
            Route::post('/create', [NotificationController::class, 'create'])->name('create');
            Route::get('/getall', [NotificationController::class, 'getAll'])->name('getall');
        });

        Route::name('announcement.')->prefix('announcement')->group(function () {
            Route::post('/create', [Announcement::class, 'create'])->name('create');
            Route::get('/getall', [Announcement::class, 'getAll'])->name('getall');
        });

        Route::name('points.')->prefix('points')->group(function () {
            Route::get('/getall', [PointController::class, 'getAllAdmin'])->name('getall');
        });

        Route::name('coin.')->prefix('coin')->group(function () {
            Route::get('/get', [CoinDealController::class, 'get'])->name('get');

            Route::post('/create', [CoinDealController::class, 'create'])->name('create');
        });

        Route::name('transactions.')->prefix('transactions')->group(function () {
            Route::get('/getAll', [TransactionController::class, 'getAll'])->name('get');
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

Route::post('/paystack/update', [TransactionController::class, 'paystackWebhook'])->name('paystack.webhook');

Route::group(['middleware' => ['jwt.fresh']], function () {
    Route::get('/refresh-token', [AuthenticationController::class, 'refreshToken'])->name('user.refresh');
});
