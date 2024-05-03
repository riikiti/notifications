<?php

namespace App\Http\Actions\GenerateImage;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Storage;

class GenerateImageAction
{
    private string $url;
    private string $apiKey;
    private string $secretKey;
    private Client $client;
    private string $appUrl;

    public function __construct()
    {
        $this->appUrl = config('app.url');
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

    public function generateImage(string $prompt): string
    {
        $uuid = $this->generate($prompt, $this->getModel());
        $image = $this->checkGeneration($uuid);
        $image_data = base64_decode($image);
        $image_name = 'image_' . time() . '.png';

        Storage::disk('public')->put($image_name, $image_data);
        return $image_name;
    }

    public function getModel()
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

    public function checkGeneration($requestId)
    {
        try {
            do {
                $response = $this->client->get("key/api/v1/text2image/status/{$requestId}");
                $data = json_decode($response->getBody(), true);
                if ($data['status'] == 'DONE') {
                    return $data['images'][0];
                }
                usleep(50000);
            } while (true);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                echo $e->getResponse()->getBody();
            } else {
                echo "Request failed: " . $e->getMessage();
            }
        }
    }

}
