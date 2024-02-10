<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WheatherTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $city = 'Buenos Aires';
     
        $apiKey = '3c6a4442e3659ded1d6e876419f1e252';
        $date = date('Y-m-d');

        $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q={$city}&date={$date}&appid={$apiKey}&units=metric";

        $response = Http::get($apiUrl);

        $response->getStatusCode();

        $this->assertEquals(200,$response->getStatusCode());
    }
}
