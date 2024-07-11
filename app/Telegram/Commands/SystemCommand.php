<?php

namespace App\Telegram\Commands;


use App\Models\TelegramChatUser;
use App\Models\User;
use DefStudio\Telegraph\DTO\User as TelegramUser;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait SystemCommand
{

    public function start(string $userName): void
    {
        $this->chat->markdown("*Hi* {$this->message->from()->firstName()}, happy to be here! Send /help to give commands.")->send();
    }

    public function help(): void
    {
        $this->chat->markdown("Here are the available commands:
        - /connectToTrello {trello username}: Start the bot (active)
        - /getTasksByTrelloUsername {trello username}: Get all tasks (active)
        - /getAllTasks: Get all tasks (active)
        ")->send();
    }
    protected function handleChatMemberJoined(TelegramUser $member): void
    {
        $this->chat->html("Welcome {$member->firstName()}")->send();

        $user=User::firstOrCreate(
            ['telegram_id' => $member->id()],
            [
                'firstName' => $member->firstName() ?? null,
                'lastName' => $member->lastName() ?? null,
                'username' => $member->username() ?? null,
                'isPremium' => $member->isPremium() ?? false,
            ]
        );

        TelegramChatUser::create([
            'chat_id' => $this->chat->id,
            'user_id' => $user->id,
        ]);
    }
    protected function handleChatMemberLeft(TelegramUser $member): void
    {
        $this->chat->html("{$member->firstName()} just left")->send();

        $user = User::where('telegram_id', $member->id())->first();

        if ($user) {
            TelegramChatUser::where('chat_id', $this->chat->id)
                ->where('user_id', $user->id)
                ->delete();
        }
    }

    protected function handleUnknownCommand(Stringable|\Illuminate\Support\Stringable $text): void
    {
        $this->chat->html("I can't understand your command: $text")->send();
    }

    protected function onFailure(Throwable|\Throwable $throwable): void
    {
        if ($throwable instanceof NotFoundHttpException) {
            throw $throwable;
        }

        report($throwable);

        $this->reply('sorry man, I failed');
    }
}
