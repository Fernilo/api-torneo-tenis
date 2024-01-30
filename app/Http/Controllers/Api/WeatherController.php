<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

        activity()->causedBy(Auth::user())->withProperties(['from' => 'foreign api'])->log('weather');
        return response()->json($data);
    }
}
