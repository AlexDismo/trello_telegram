<?php

namespace App\Http\Controllers\Telegram;

use DefStudio\Telegraph\Models\TelegraphBot;

class TelegramBotController
{
    private $bot;
    public function registerCommands($botId): string
    {
        $bot=TelegraphBot::find($botId);
        $bot->registerCommands([
            'command1' => 'command 1 description',
            'command2' => 'command 2 description'
        ])->send();
        return 'Commands registered successfully';
    }

    public function registerWebhookTelegram(): void
    {
        $bot=TelegraphBot::find(1);
        $bot->registerWebhook()->send();
    }


}
