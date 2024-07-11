<?php

namespace App\Telegram\Commands;

use App\Models\TelegramChatUser;
use App\Models\User;

trait TrelloCommand
{
    public function connectToTrello(string $userName): void
    {
        $userInfo = $this->getUserInfo();

        if ($userInfo['telegramChatUser']) {
            $userInfo['telegramChatUser']->trello_username = $userName;
            $userInfo['telegramChatUser']->save();

            $this->chat->html("Successfully connected trelloUsername $userName to telegramId {$userInfo['senderId']}")->send();} else {
            $this->chat->html("User not found")->send();
        }
    }

    public function getAllTasks(): void
    {
        $userInfo = $this->getUserInfo();

        if ($userInfo['telegramChatUser']) {
            $formattedTasks = $this->formatterService->formatTasks((array)$this->trelloController->getTasks());
            $this->chat->html("Getting all tasks:\n\n$formattedTasks")->send();
        } else {
            $this->chat->html("User not found")->send();
        }
    }

    public function getTasksByTrelloUsername(string $trelloUsername): void
    {
        $userInfo = $this->getUserInfo();

        if ($userInfo['telegramChatUser']) {
            $formattedTasks = $this->formatterService->formatTasks((array)$this->trelloController->getMemberTasks($trelloUsername));
            $this->chat->html("Getting tasks for trelloUsername $trelloUsername:\n\n$formattedTasks")->send();
        } else {
            $this->chat->html("User not found")->send();
        }
    }

    private function getUserInfo (){
        $chatId = $this->chat->id;
        $senderId = $this->message->from()->id();

        $user = User::where('telegram_id', $senderId)->first();

        if (!$user) {
            $this->chat->html("User not found")->send();
            return null;
        }

        $telegramChatUser = TelegramChatUser::where('user_id', $user->id)
            ->where('chat_id', $chatId)
            ->first();

        if (!$telegramChatUser) {
            $this->chat->html("User not found")->send();
            return null;
        }
        return ['chatId' => $chatId, 'senderId' => $senderId, 'telegramChatUser' => $telegramChatUser];
    }
}
