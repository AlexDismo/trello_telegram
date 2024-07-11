<?php

namespace App\Http\Controllers\Trello;

use App\Http\Controllers\Controller;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrelloWebhookController extends Controller
{
    private $bot;

    public function handle(Request $request)
    {

        $payload = $request->all();

        $eventInfo = $this->extractEventInfo($payload);
        $chat=TelegraphChat::find(1);
        $chat->message($eventInfo)->send();
    }

    private function extractEventInfo(array $payload): string
    {
        $eventInfo = "New event from Trello:\n";

        if (isset($payload['action']['type'])) {
            $eventInfo .= "Event type: {$payload['action']['type']}\n";
        }

        if (isset($payload['action']['data']['card']['name'])) {
            $eventInfo .= "Card name: {$payload['action']['data']['card']['name']}\n";
        }

        return $eventInfo;
    }

    public function registerWebhook()
    {
        $client = new \GuzzleHttp\Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);

        $data = [
            'key' => env('TRELLO_API_KEY'),
            'callbackURL' => env('APP_URL') . '/trello-webhook',
            'idModel' => env('ACTIVE_BOARD_WEBHOOK_ID'),
            'description' => 'My first webhook',
        ];

        $response = $client->request('POST', 'https://api.trello.com/1/tokens/' . env('TRELLO_API_TOKEN') . '/webhooks/', [
            'body' => json_encode($data)
        ]);

        if ($response->getStatusCode() == 200) {
            echo "Webhook created successfully";
        } else {
            echo "Failed to create webhook";
        }
    }
}
