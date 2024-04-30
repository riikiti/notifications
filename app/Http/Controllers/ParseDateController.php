<?php

namespace App\Http\Controllers;


use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ParseDateController extends Controller
{

    private string $URL ;
    private array $AUTH_HEADERS;


    public function __construct()
    {
        $this->URL = env('FUSIONBRAIN_URL');
        $this->AUTH_HEADERS = [
            'X-Key' => 'Key ' . env('FUSIONBRAIN_API'),
            'X-Secret' => 'Secret ' . env('FUSIONBRAIN_SECRET'),
            'Content-Type' => 'application/json',
        ];
    }
    public function __invoke()
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
        ])->get('https://kakoysegodnyaprazdnik.ru/');

        $crawler = new Crawler($response->body());

        $holidays = $crawler->filter('span[itemprop="text"]')->each(function (Crawler $node) {
            return $node->text();
        });

        $events = $crawler->filter('div.event')->each(function (Crawler $node) {
            return $node->text();
        });
        $pattern = '/(\d+)\s*(года|лет|год)?$/';
        $replacement = '';
        $new_str = preg_replace($pattern, $replacement, $events[0]);
        // dd($holidays);


        $client = new Client();
        $response = $client->request('GET', $this->URL . 'key/api/v1/models', [
            'headers' => $this->AUTH_HEADERS,
            'json' => [
                "type" => "GENERATE",
                "style" => "string",
                "width" => 1024,
                "height" => 1024,
                "num_images" => 1,
                //"negativePromptUnclip" => "яркие цвета, кислотность, высокая контрастность",
                "generateParams" => [
                    "query" => "Пушистый кот в очках",
                ],
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        dd($data);
        return $data[0]['id'];
    }
}
