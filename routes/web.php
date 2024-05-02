<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('splade')->group(function () {
    // Registers routes to support the interactive components...
    Route::spladeWithVueBridge();

    // Registers routes to support password confirmation in Form and Link components...
    Route::spladePasswordConfirmation();

    // Registers routes to support Table Bulk Actions and Exports...
    Route::spladeTable();

    // Registers routes to support async File Uploads with Filepond...
    Route::spladeUploads();

    Route::get('/', function () {
        return view('welcome');
    });

    

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->middleware(['verified'])->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('user', function()
        {
            return view('deposits');
        });

        Route::get('money', function()
        {
            return view('withdrawals');
        });

        Route::get('transfers', function()
        {
            return view('transferring');
        });

        Route::get('wallets', function()
        {
            return view('newWallet');
        });

        Route::get('card', function()
        {
            return view('allWallets');
        });

        Route::get('transferoptions', function()
        {
            return view('chooseWallet');
        });

        Route::get('walletToWallet', function()
        {
            return view('transferWallet');
        });
        

        Route::post('user', [UsersController::class, 'depositMoneyToWallet'])->name('deposite');

        Route::post('money', [UsersController::class, 'withdrawMoneyFromWallet'])->name('withdrawn');

        Route::post('transfers', [UsersController::class, 'transferMoneyToOtherUsers'])->name('trans');

        Route::post('wallets', [UsersController::class, 'createNewWallet'])->name('wal');

        Route::get('card', [UsersController::class, 'viewAllWallets'])->name('viewWals');

        Route::post('walletToWallet', [UsersController::class, 'walletToWalletMoneyTransfer'])->name('makeTransfer');
        });
        

    require __DIR__.'/auth.php';
});
