<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PusherNotificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;


use Illuminate\Http\Request;

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

Route::get('/', function () {
  return view('welcome');
});

Route::post('/telegram-update', [PusherNotificationController::class, 'telegram_a_agente']);
Route::post('/send-telegram', [PusherNotificationController::class, 'agente_a_telegram']);
Route::get('/test-chat', function () {
  return view('chat');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
// Route::view('login', 'login');
Route::view('login','auth/login')->name('login')->middleware('guest');
// Route::post('login', );
Route::post('login', [LoginController::class, 'index'])->name('login');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


