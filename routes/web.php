<?php

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\DarkModeController;
use App\Http\Controllers\DashboardController;
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
})->name('inicio');

// Main routes
Route::middleware(['auth:sanctum', 'verified'])->get('/messenger', function() {
  return Inertia::render('Chat/Index');
})->name('messenger');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard.html', function() {
})->name('stats.index');

Route::get('/test-chat', function() {
  return view('chat');
});


Route::post('/telegram-update', [PusherNotificationController::class, 'telegram_to_agent']);
Route::post('/send-telegram', [PusherNotificationController::class, 'agent_to_telegram']);
Route::get('/chats',[TelegramUserController::class,'index']);
Route::get('/conversation',[TelegramUserController::class,'conversation']);
Route::get('/lastGame',[TelegramUserController::class,'game']);
Route::get('/game',[TelegramUserController::class,'lastGame']);

//Get game stats
Route::get('/rates/{option}',[DashboardController::class,'index']);
// Get the value of darkMode session
Route::get('/dark-mode',[DarkModeController::class,'index']);
// Change the value of DarkMode session
Route::get('/change-dark-mode',[DarkModeController::class,'changeColor']);

