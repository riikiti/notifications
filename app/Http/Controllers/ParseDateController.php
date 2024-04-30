<?php

namespace App\Http\Controllers;


use GuzzleHttp\Client;
use http\Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;

class ParseDateController extends Controller
{
    private $url;
    private $apiKey;
    private $secretKey;
    private Client $client;

    public function __construct()
    {
        $this->url = env('FUSIONBRAIN_URL');
        $this->apiKey = env('FUSIONBRAIN_API');
        $this->secretKey = env('FUSIONBRAIN_SECRET');
        $this->client = new Client([
            'base_uri' => $this->url,
            'headers' => [
                'X-Key' => 'Key ' . $this->apiKey,
                'X-Secret' => 'Secret ' . $this->secretKey,
            ],
        ]);
    }


    public function index()
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
         //dd($holidays);

        $uuid = $this->generate('Дeнь жapeныx кpeвeтoк cкaмпи ', $this->getModel());

        $image = $this->checkGeneration($uuid);;
        $image_data = base64_decode($image);
        $image_name = 'image_' . time() . '.png'; // generate a unique name for the image

        Storage::disk('local')->put($image_name, $image_data);
        dd($image);
    }


    public function getModel(): int
    {
        try {
            $response = $this->client->get('key/api/v1/models');
            $data = json_decode($response->getBody(), true);
            return $data[0]['id'];
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                echo $e->getResponse()->getBody();
            } else {
                echo "Request failed: " . $e->getMessage();
            }
        }
    }

    public function generate($prompt, $model, $images = 1, $width = 1024, $height = 1024)
    {
        try {
            $response = $this->client->request('POST', 'key/api/v1/text2image/run', [
                'multipart' => [
                    [
                        'name' => 'params',
                        'contents' => json_encode([
                            'type' => 'GENERATE',
                            'style' => 'string',
                            'width' => $width,
                            'height' => $height,
                            'num_images' => $images,
                            'generateParams' => [
                                'query' => $prompt
                            ]
                        ]),
                        'headers' => [
                            'Content-Type' => 'application/json'
                        ]
                    ],
                    [
                        'name' => 'model_id',
                        'contents' => $model
                    ]
                ]
            ]);

            $data = $response->getBody()->getContents();
            return json_decode($data, true)['uuid'];
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                echo $e->getResponse()->getBody();
            } else {
                echo "Request failed: " . $e->getMessage();
            }
        }
    }

    public function checkGeneration($requestId, $attempts = 10, $delay = 10)
    {
        try {
            sleep(55);
            $response = $this->client->get("key/api/v1/text2image/status/{$requestId}");
            $data = json_decode($response->getBody(), true);
            if ($data['status'] == 'DONE') {
                return $data['images'][0];
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                echo $e->getResponse()->getBody();
            } else {
                echo "Request failed: " . $e->getMessage();
            }
        }
    }


}
