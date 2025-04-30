@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Shopping Cart</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (count($products) > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $item)
                                        <tr>
                                            <td>
                                                <img src="{{ $item['product']->image_path }}" alt="{{ $item['product']->name }}" class="img-thumbnail" style="max-width: 50px">
                                                {{ $item['product']->name }}
                                            </td>
                                            <td>${{ number_format($item['product']->price, 2) }}</td>
                                            <td>
                                                <form action="{{ route('cart.update', $item['product']) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control" style="width: 80px" onchange="this.form.submit()">
                                                </form>
                                            </td>
                                            <td>${{ number_format($item['subtotal'], 2) }}</td>
                                            <td>
                                                <form action="{{ route('cart.remove', $item['product']) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td colspan="2"><strong>${{ number_format($total, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Continue Shopping</a>
                            <form action="{{ route('cart.checkout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">Proceed to Checkout</button>
                            </form>
                        </div>
                    @else
                        <div class="text-center">
                            <p>Your cart is empty.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-dark">Start Shopping</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 