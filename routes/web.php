<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PasswordController;

// Home route
Route::get('/customer', function () {
    return view('customer/index');
})->name('main');


// Route for pembayaran
Route::get('/leaderboard', function () {
    return view('customer/leaderboard');
})->name('leaderboard');

Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

Route::get('/ticket/next', [PegawaiController::class, 'getNextTicket'])->name('ticket.next');

Route::post('/print-ticket', [PrintController::class, 'printTicket'])->name('print.ticket');

Route::get('/tickets/leaderboard', [LeaderboardController::class, 'getLatestTickets']);

Route::get('/admin/tickets', [PegawaiController::class, 'getTodayTicketCount'])->name('admin.tickets');

Route::post('/ticket', [TicketController::class, 'store'])->name('ticket.store');

Route::post('/update-status/{id}', [TicketController::class, 'updateStatus'])->name('ticket.updateStatus');

Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');




Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [PasswordController::class, 'showChangePasswordForm'])->name('change-password.form');
    Route::post('/change-password', [PasswordController::class, 'updatePassword'])->name('change-password.update');
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout');
    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai');
    Route::get('/admin/user', [AdminController::class, 'indexUser'])->name('user');
    Route::get('/admin/role', [AdminController::class, 'indexRole'])->name('role');
    Route::get('/admin/runningtext', [AdminController::class, 'indexRunningText'])->name('runningtext');
    Route::get('/admin/carousel', [AdminController::class, 'indexCarousel'])->name('carousel');
    Route::delete('/admin/user/{id}', [AdminController::class, 'destroyUser'])->name('user.delete');
    Route::post('/admin/user', [AdminController::class, 'storeUser'])->name('user.store');
    Route::put('/admin/user/update/{id}', [AdminController::class, 'updateUser'])->name('user.update');
    Route::delete('/admin/role/{id}', [AdminController::class, 'destroyRole'])->name('role.delete');
    Route::post('/admin/role', [AdminController::class, 'storeRole'])->name('role.store');
    Route::put('/admin/role/update/{id}', [AdminController::class, 'updateRole'])->name('role.update');
    Route::delete('/admin/runningtext/{id}', [AdminController::class, 'destroyRunningText'])->name('runningtext.delete');
    Route::post('/admin/runningtext', [AdminController::class, 'storeRunningText'])->name('runningtext.store');
    Route::put('/admin/runningtext/update/{id}', [AdminController::class, 'updateRunningText'])->name('runningtext.update');
    Route::post('admin/carousel', [AdminController::class, 'storeCarousel'])->name('carousel.store');
    Route::delete('/admin/carousel/{id}', [AdminController::class, 'destroyCarousel'])->name('carousel.delete');
    Route::post('/admin/runningtext/toggle-status/{id}', [AdminController::class, 'toggleStatusRunningText'])->name('runningtext.toggle-status');
    Route::post('/admin/carousel/toggle-status/{id}', [AdminController::class, 'toggleStatusCarousel'])->name('carousel.toggle-status');

    
});







