<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ParseDateController extends Controller
{
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
        dd($events);

    }
}
