<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\LoansController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'index')->name('login');
    Route::post('post-login', 'postLogin')->name('login.post');
    Route::get('registration', 'registration')->name('register');
    Route::post('post-registration', 'postRegistration')->name('register.post');
    Route::get('logout', 'logout')->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::controller(BooksController::class)->group(function () {
        Route::get('books', 'showBooks')->name('books');
        Route::get('books/add', 'addBooks')->name('addBooks');
        Route::post('books/add/create', 'postAddBook')->name('postAddBook');
        Route::delete('/books/{book}', 'destroy')->name('books.destroy');
        Route::get('/books/{book}/edit', 'edit')->name('books.edit');
        Route::put('/books/{book}', 'update')->name('books.update');
        Route::get('/books/{book}', 'show')->name('books.show');
    });

    Route::controller(LoansController::class)->group(function () {
        Route::get('loans', 'index')->name('loans');
    });

    Route::controller(UsersController::class)->group(function () {
        Route::get('users', 'index')->name('users');
    });
});
