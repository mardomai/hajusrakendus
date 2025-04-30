<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
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

    public function checkout()
    {
        // Here you would integrate with Stripe for payment processing
        Session::forget('cart');
        return redirect()->route('cart.index')->with('success', 'Thank you for your purchase!');
    }
}
