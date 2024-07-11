<?php

use App\Http\Controllers\Telegram\TelegramBotController;
use App\Http\Controllers\Trello\TrelloWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register-commands/{botId}', [TelegramBotController::class, 'registerCommands']);
Route::get('/register-webhook-telegram', [TelegramBotController::class, 'registerWebhookTelegram']);
Route::get('/register-trello-webhook', [TrelloWebhookController::class, 'registerWebhook']);
Route::post('/trello-webhook', [TrelloWebhookController::class, 'handle']);
Route::get('/trello-webhook', function () {
    return response('', 200);
});
