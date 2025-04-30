<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function getWeather($location)
    {
        $apiKey = config('services.openweathermap.key');
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
            'q' => $location,
            'appid' => $apiKey,
            'units' => 'metric'
        ]);

        return response()->json($response->json());
    }

    public function getStripeBalance()
    {
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $balance = \Stripe\Balance::retrieve();
            
            return response()->json([
                'success' => true,
                'balance' => $balance->available[0]->amount / 100, // Convert from cents to euros
                'currency' => $balance->available[0]->currency,
                'pending' => isset($balance->pending[0]) ? $balance->pending[0]->amount / 100 : 0
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getStripeTransactions()
    {
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $payments = \Stripe\PaymentIntent::all(['limit' => 10]);
            
            $transactions = [];
            foreach ($payments->data as $payment) {
                $transactions[] = [
                    'id' => $payment->id,
                    'amount' => $payment->amount / 100, // Convert from cents to euros
                    'currency' => $payment->currency,
                    'status' => $payment->status,
                    'created' => date('Y-m-d H:i:s', $payment->created),
                    'description' => $payment->description
                ];
            }

            return response()->json([
                'success' => true,
                'transactions' => $transactions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getMonsters()
    {
        try {
            $response = Http::get('https://hajusrakendused.tak22parnoja.itmajakas.ee/current/public/index.php/api/monsters');
            
            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch monsters data'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 