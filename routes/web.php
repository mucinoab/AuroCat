<?php

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PusherNotificationController;
use App\Http\Controllers\TelegramUserController;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
  return Inertia::render('Welcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
  ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function() {
  return Inertia::render('Chat/Index');
})->name('dashboard');


Route::get('/test-chat', function() {
  return view('chat');
});

Route::post('/telegram-update', [PusherNotificationController::class, 'telegram_to_agent']);
Route::post('/send-telegram', [PusherNotificationController::class, 'agent_to_telegram']);
Route::get('/chats',[TelegramUserController::class,'index']);
Route::get('/conversation',[TelegramUserController::class,'conversation']);

