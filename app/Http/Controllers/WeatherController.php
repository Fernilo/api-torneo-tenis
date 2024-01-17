<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function getWeather()
    {
        $city = 'Buenos Aires';
     
        $apiKey = config('services.openweather.api_key');
        $date = date('Y-m-d');

        $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q={$city}&date={$date}&appid={$apiKey}&units=metric";

        $response = Http::get($apiUrl);
        $data = $response->json();

        return response()->json($data);
    }
}
