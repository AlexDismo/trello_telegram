<?php

use App\Http\Controllers\Trello\TrelloController;
use App\Http\Controllers\Trello\TrelloWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/tasks', [TrelloController::class, 'getTasks']);
Route::get('/tasks/{userName}', [TrelloController::class, 'getMemberTasks']);
