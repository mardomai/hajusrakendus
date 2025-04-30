<?php

namespace App\Http\Controllers;

use App\Models\WeatherCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class WeatherController extends Controller
{
    private $apiKey;
    
    public function __construct()
    {
        $this->apiKey = env('OPENWEATHERMAP_API_KEY');
    }

    public function index()
    {
        return view('weather.index');
    }

    public function show($location)
    {
        try {
            // Check cache first
            $cachedWeather = WeatherCache::where('location', $location)
                ->where('expires_at', '>', now())
                ->first();

            if ($cachedWeather) {
                return response()->json(json_decode($cachedWeather->weather_data, true));
            }

            // If not in cache, fetch from API
            $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
                'q' => $location,
                'appid' => $this->apiKey,
                'units' => 'metric'
            ]);

            if ($response->successful()) {
                $weatherData = $response->json();
                
                // Cache the results
                WeatherCache::updateOrCreate(
                    ['location' => $location],
                    [
                        'weather_data' => json_encode($weatherData),
                        'expires_at' => now()->addMinutes(30)
                    ]
                );

                return response()->json($weatherData);
            }

            return response()->json(['error' => 'City not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch weather data: ' . $e->getMessage()], 500);
        }
    }
}
