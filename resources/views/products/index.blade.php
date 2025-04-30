@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <h2 class="text-dark mb-0">Game Store</h2>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        @foreach ($products as $product)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="position-relative">
                        <img src="{{ $product->image_path }}" 
                             class="card-img-top" 
                             alt="{{ $product->name }}"
                             style="height: 250px; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 p-2">
                            <span class="badge bg-dark">
                                ${{ number_format($product->price, 2) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">{{ $product->name }}</h5>
                        <p class="card-text text-muted">
                            {{ $product->description }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="badge bg-light text-dark">
                                Stock: {{ $product->stock }}
                            </span>
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-dark {{ $product->stock > 0 ? '' : 'disabled' }}"
                                        {{ $product->stock > 0 ? '' : 'disabled' }}>
                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('styles')
<style>
    .card {
        transition: transform 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .btn-dark {
        padding: 0.5rem 1.5rem;
    }
</style>
@endpush
@endsection 