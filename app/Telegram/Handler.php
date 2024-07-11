<?php

namespace App\Telegram;


use App\Http\Controllers\Trello\TrelloController;
use App\Services\FormatterService;
use App\Telegram\Commands\HelloCommand;
use App\Telegram\Commands\SystemCommand;
use App\Telegram\Commands\TrelloCommand;
use DefStudio\Telegraph\Handlers\WebhookHandler;

class Handler extends WebhookHandler
{
    private TrelloController $trelloController;

    private FormatterService $formatterService;
    public function __construct()
    {
        parent::__construct();
        $this->trelloController = new TrelloController();
        $this->formatterService = new FormatterService();
    }
    use SystemCommand;
    use HelloCommand;
    use TrelloCommand;

}
