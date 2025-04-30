<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    private function cleanCart()
    {
        $cart = Session::get('cart', []);
        // Remove items with quantity 0 or non-existent products
        foreach ($cart as $id => $quantity) {
            if ($quantity <= 0 || !Product::find($id)) {
                unset($cart[$id]);
            }
        }
        Session::put('cart', $cart);
        return $cart;
    }

    public function index()
    {
        $cart = $this->cleanCart();
        $products = [];
        $total = 0;

        foreach ($cart as $id => $quantity) {
            $product = Product::find($id);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity
                ];
                $total += $product->price * $quantity;
            }
        }

        return view('cart.index', compact('products', 'total'));
    }

    public function add(Product $product)
    {
        $cart = $this->cleanCart();
        
        if (isset($cart[$product->id])) {
            $cart[$product->id]++;
        } else {
            $cart[$product->id] = 1;
        }

        Session::put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart.');
    }

    public function update(Request $request, Product $product)
    {
        $cart = $this->cleanCart();
        
        $quantity = max(0, (int) $request->quantity);
        
        if ($quantity > 0) {
            $cart[$product->id] = $quantity;
        } else {
            unset($cart[$product->id]);
        }

        Session::put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function remove(Product $product)
    {
        $cart = $this->cleanCart();
        
        unset($cart[$product->id]);
        Session::put('cart', $cart);

        if (empty($cart)) {
            Session::forget('cart');
        }

        return redirect()->route('cart.index')->with('success', 'Product removed from cart.');
    }

    public function showCheckout()
    {
        $cart = $this->cleanCart();
        $items = [];
        $total = 0;

        foreach ($cart as $id => $quantity) {
            $product = Product::find($id);
            if ($product) {
                $items[] = (object)[
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity
                ];
                $total += $product->price * $quantity;
            }
        }

        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('checkout', compact('items', 'total'));
    }

    public function checkout(Request $request)
    {
        // Validate the cart is not empty
        $cart = $this->cleanCart();
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Redirect to the payment form
        return redirect()->route('cart.showCheckout');
    }

    public function processPayment(Request $request)
    {
        try {
            // Validate that we have items in cart
            $cart = $this->cleanCart();
            if (empty($cart)) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
            }

            // Initialize Stripe with the secret key
            $stripeSecretKey = config('services.stripe.secret');
            \Stripe\Stripe::setApiKey($stripeSecretKey);

            // Calculate total amount in cents
            $amount = (int)($this->calculateTotal() * 100);

            try {
                // Create payment intent
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => $amount,
                    'currency' => 'eur',
                    'payment_method' => $request->payment_method_id,
                    'confirmation_method' => 'manual',
                    'confirm' => true,
                    'description' => 'Purchase from Game Shop',
                    'metadata' => [
                        'order_id' => uniqid('order_'),
                    ],
                    'return_url' => route('cart.index'), // Add return URL for 3D Secure
                ]);

                Log::info('Payment Intent created:', ['id' => $paymentIntent->id, 'status' => $paymentIntent->status]);

                // Check payment status
                if ($paymentIntent->status === 'succeeded' || $paymentIntent->status === 'requires_capture') {
                    // Payment successful - clear the cart
                    Session::forget('cart');
                    Session::save(); // Force save the session
                    
                    Log::info('Payment successful, cart cleared');
                    
                    return redirect()->route('products.index')
                        ->with('success', 'Payment successful! Thank you for your purchase.');
                } 
                // Handle 3D Secure authentication if needed
                elseif ($paymentIntent->status === 'requires_action' && 
                       $paymentIntent->next_action && 
                       $paymentIntent->next_action->type === 'use_stripe_sdk') {
                    return redirect()->route('cart.showCheckout')
                        ->with('error', 'This card requires 3D Secure authentication. Please try again.');
                } else {
                    Log::warning('Payment failed with status: ' . $paymentIntent->status);
                    return redirect()->route('cart.showCheckout')
                        ->with('error', 'Payment failed. Please try again. Status: ' . $paymentIntent->status);
                }

            } catch (\Stripe\Exception\CardException $e) {
                Log::error('Card error: ' . $e->getMessage());
                return redirect()->route('cart.showCheckout')
                    ->with('error', 'Your card was declined: ' . $e->getError()->message);
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                Log::error('Stripe invalid request: ' . $e->getMessage());
                return redirect()->route('cart.showCheckout')
                    ->with('error', 'Invalid payment request. Please try again.');
            } catch (\Stripe\Exception\AuthenticationException $e) {
                Log::error('Stripe authentication error: ' . $e->getMessage());
                return redirect()->route('cart.showCheckout')
                    ->with('error', 'Payment system error. Please try again later.');
            } catch (\Stripe\Exception\ApiConnectionException $e) {
                Log::error('Stripe API connection error: ' . $e->getMessage());
                return redirect()->route('cart.showCheckout')
                    ->with('error', 'Network error. Please try again.');
            } catch (\Stripe\Exception\ApiErrorException $e) {
                Log::error('Stripe API error: ' . $e->getMessage());
                return redirect()->route('cart.showCheckout')
                    ->with('error', 'Payment processing error. Please try again.');
            }

        } catch (\Exception $e) {
            Log::error('Payment processing error: ' . $e->getMessage());
            return redirect()->route('cart.showCheckout')
                ->with('error', 'An error occurred while processing your payment. Please try again.');
        }
    }

    private function calculateTotal()
    {
        $cart = $this->cleanCart();
        $total = 0;

        foreach ($cart as $id => $quantity) {
            $product = Product::find($id);
            if ($product) {
                $total += $product->price * $quantity;
            }
        }

        return $total;
    }
}
