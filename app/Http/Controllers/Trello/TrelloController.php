<?php

namespace App\Http\Controllers\Trello;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class TrelloController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getTasks()
    {
        $cards = $this->getCards();

        $tasks = array_map(function ($card) {
            $members = $this->getCardMembers($card);

            $listResponse = $this->client->request('GET', 'https://api.trello.com/1/lists/' . $card['idList'], [
                'query' => [
                    'key' => env('TRELLO_API_KEY'),
                    'token' => env('TRELLO_API_TOKEN'),
                ]
            ]);

            $list = json_decode($listResponse->getBody()->getContents(), true);

            return [
                'id' => $card['id'],
                'name' => $card['name'],
                'desc' => $card['desc'],
                'list' => $list['name'],
                'members' => implode(', ', $members)
            ];
        }, $cards);

        return $tasks;
    }

    public function getMemberTasks(string $username)
    {
        $cards = $this->getCards();

        $filteredCards = array_filter($cards, function ($card) use ($username) {
            $members = $this->getCardMembers($card);
            return in_array($username, $members);
        });

        $tasks = array_map(function ($card) {
            $listResponse = $this->client->request('GET', 'https://api.trello.com/1/lists/' . $card['idList'], [
                'query' => [
                    'key' => env('TRELLO_API_KEY'),
                    'token' => env('TRELLO_API_TOKEN'),
                ]
            ]);

            $list = json_decode($listResponse->getBody()->getContents(), true);

            return [
                'id' => $card['id'],
                'name' => $card['name'],
                'desc' => $card['desc'],
                'list' => $list['name'],
            ];
        }, $filteredCards);

        return $tasks;
    }

    public function getCards()
    {
        $response = $this->client->request('GET', 'https://api.trello.com/1/boards/' . env('ACTIVE_BOARD_ID') . '/cards', [
            'query' => [
                'key' => env('TRELLO_API_KEY'),
                'token' => env('TRELLO_API_TOKEN'),
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getCardMembers(array $card)
    {
        $members = [];
        if (!empty($card['idMembers'])) {
            foreach ($card['idMembers'] as $memberId) {
                $memberResponse = $this->client->request('GET', 'https://api.trello.com/1/members/' . $memberId, [
                    'query' => [
                        'key' => env('TRELLO_API_KEY'),
                        'token' => env('TRELLO_API_TOKEN'),
                    ]
                ]);

                $member = json_decode($memberResponse->getBody()->getContents(), true);
                $members[] = $member['username'];
            }
        }

        return $members;
    }
}
