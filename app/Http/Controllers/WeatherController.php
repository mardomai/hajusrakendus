<?php

namespace App\Http\Controllers;

use App\Models\WeatherCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
            Log::info('Weather request received', ['location' => $location]);

            // Check cache first
            $cachedWeather = WeatherCache::where('location', $location)
                ->where('expires_at', '>', now())
                ->first();

            if ($cachedWeather) {
                Log::info('Weather cache hit', [
                    'location' => $location,
                    'expires_at' => $cachedWeather->expires_at,
                    'cached_at' => $cachedWeather->created_at
                ]);
                return response()->json(json_decode($cachedWeather->weather_data, true));
            }

            Log::info('Weather cache miss', ['location' => $location]);

            // Log the API request
            Log::info('Weather API Request', [
                'location' => $location,
                'api_key' => substr($this->apiKey, 0, 8) . '...'  // Log only first 8 chars of API key
            ]);

            // If not in cache, fetch from API
            $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
                'q' => $location,
                'appid' => $this->apiKey,
                'units' => 'metric'
            ]);

            // Log the API response
            Log::info('Weather API Response', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                $weatherData = $response->json();
                
                // Cache the results
                WeatherCache::updateOrCreate(
                    ['location' => $location],
                    [
                        'weather_data' => json_encode($weatherData),
                        'expires_at' => now()->addMinute()
                    ]
                );

                Log::info('Weather data cached', [
                    'location' => $location,
                    'expires_at' => now()->addMinute()
                ]);

                return response()->json($weatherData);
            }

            return response()->json(['error' => 'City not found'], 404);
        } catch (\Exception $e) {
            Log::error('Weather API Error', [
                'message' => $e->getMessage(),
                'location' => $location
            ]);
            return response()->json(['error' => 'Unable to fetch weather data: ' . $e->getMessage()], 500);
        }
    }
}
