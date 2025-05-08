@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Checkout</h1>
                <a href="{{ route('cart.index') }}" class="btn btn-outline-dark">
                    <i class="fas fa-arrow-left"></i> Back to Cart
                </a>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Order Summary</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th class="text-end">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-end">${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2">Total</th>
                                <th class="text-end">${{ number_format($total, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Customer Information</h5>
                    <form id="payment-form" action="{{ route('payment.process') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                    id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                    id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                    id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                    id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="card-title mb-3">Payment Details</h5>
                        <div class="mb-4">
                            <div id="card-element" class="form-control p-3"></div>
                            <div id="card-errors" class="text-danger mt-2"></div>
                        </div>
                        
                        <button type="submit" id="submit-button" class="btn btn-dark btn-lg w-100">
                            <span id="button-text">Pay ${{ number_format($total, 2) }}</span>
                            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    console.log('Stripe Key:', '{{ $key }}');
    // Initialize Stripe with the publishable key
    const stripe = Stripe('{{ $key }}', {
        locale: 'en'
    });
    const elements = stripe.elements();
    
    const style = {
        base: {
            fontSize: '16px',
            color: '#32325d',
            fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif',
            '::placeholder': {
                color: '#aab7c4'
            },
            ':-webkit-autofill': {
                color: '#32325d'
            }
        },
        invalid: {
            color: '#dc3545',
            iconColor: '#dc3545',
            ':-webkit-autofill': {
                color: '#dc3545'
            }
        }
    };
    
    const card = elements.create('card', {
        style: style,
        hidePostalCode: true
    });
    card.mount('#card-element');
    
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    const errorElement = document.getElementById('card-errors');
    
    let paymentInProgress = false;
    
    // Handle real-time validation errors
    card.addEventListener('change', function(event) {
        if (event.error) {
            errorElement.textContent = event.error.message;
        } else {
            errorElement.textContent = '';
        }
    });
    
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        if (paymentInProgress) {
            return;
        }
        
        paymentInProgress = true;
        submitButton.disabled = true;
        spinner.classList.remove('d-none');
        buttonText.textContent = 'Processing...';
        errorElement.textContent = '';
        
        try {
            const firstName = document.getElementById('first_name').value;
            const lastName = document.getElementById('last_name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;

            const result = await stripe.createPaymentMethod({
                type: 'card',
                card: card,
                billing_details: {
                    name: `${firstName} ${lastName}`,
                    email: email,
                    phone: phone,
                },
            });

            if (result.error) {
                handleError(result.error.message);
            } else {
                // Payment method created successfully
                const hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'payment_method_id');
                hiddenInput.setAttribute('value', result.paymentMethod.id);
                form.appendChild(hiddenInput);
                
                // Submit the form
                form.submit();
            }
        } catch (error) {
            console.error('Error:', error);
            handleError('An unexpected error occurred. Please try again.');
        }
    });
    
    function handleError(message) {
        paymentInProgress = false;
        errorElement.textContent = message;
        submitButton.disabled = false;
        spinner.classList.add('d-none');
        buttonText.textContent = 'Pay ${{ number_format($total, 2) }}';
    }
</script>
@endpush
@endsection 