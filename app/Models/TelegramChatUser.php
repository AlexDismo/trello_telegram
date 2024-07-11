<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramChatUser extends Model
{
    use HasFactory;

    protected $table = 'telegram_chats_users';

    protected $fillable = [
        'chat_id',
        'user_id',
        'telegram_username'
    ];
}
