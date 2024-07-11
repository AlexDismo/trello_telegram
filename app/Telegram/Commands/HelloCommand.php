<?php

namespace App\Telegram\Commands;

trait HelloCommand
{
    public function hello(): void
    {
        $this->reply('Hello, world!');
    }
}
