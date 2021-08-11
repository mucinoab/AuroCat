<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PusherNotificationController;
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

Route::post('/telegram-update', [PusherNotificationController::class, 'telegram_to_agent']);
Route::post('/send-telegram', [PusherNotificationController::class, 'agent_to_telegram']);
Route::get('/test-chat', function () {
  return view('chat');
});
