<?php

namespace App\Http\Actions\ParseDate;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ParseDateHolidays implements ParseDate
{
    public Crawler $crawler;

    public function __construct()
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
        ])->get(env('PARSE_DATE_HOLIDAYS', 'https://kakoyprazdnik.com/'));
        $this->crawler = new Crawler($response->body());
    }

    public function parse()
    {
        $holidays = $this->crawler->filter('h4')->each(function (Crawler $node) {
            return [$node->text()];
        });
        return response()->json( $holidays );
    }
}
