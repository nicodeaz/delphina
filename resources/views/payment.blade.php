@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-beige-50 to-olive-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-light text-gray-900 mb-4">Payment for Booking</h1>
            <p class="text-xl text-gray-600">Confirm your booking with a €15 deposit</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Payment Form -->
            <div class="lg:col-span-2">
                <div class="bg-white p-8 rounded-2xl shadow-xl">
                    <div class="mb-8">
                        <h2 class="text-2xl font-medium text-gray-900 mb-6">Payment Information</h2>

                        <!-- Progress Steps -->
                        <div class="flex items-center justify-center mb-8">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-olive-600 rounded-full flex items-center justify-center text-white font-medium">1</div>
                                <span class="ml-2 text-sm text-gray-600">Booking</span>
                            </div>
                            <div class="w-16 h-0.5 bg-gray-300 mx-4"></div>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-olive-600 rounded-full flex items-center justify-center text-white font-medium">2</div>
                                <span class="ml-2 text-sm font-medium text-olive-600">Payment</span>
                            </div>
                            <div class="w-16 h-0.5 bg-gray-300 mx-4"></div>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-medium">3</div>
                                <span class="ml-2 text-sm text-gray-600">Confirmation</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('payment.process', $appointment->id) }}" method="POST" id="paymentForm" class="space-y-6">
                        @csrf

                        <!-- Payment Method Selection -->
                        <div class="mb-6">
                            <label class="block text-lg font-medium text-gray-900 mb-4">Payment Method</label>
                            <div class="grid md:grid-cols-3 gap-4">
                                <div class="payment-method border-2 border-olive-500 bg-olive-50 p-4 rounded-xl cursor-pointer" data-method="card">
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                                            <span class="text-2xl">💳</span>
                                        </div>
                                        <p class="font-medium text-gray-900">Credit Card</p>
                                        <p class="text-sm text-gray-600">Visa, Mastercard</p>
                                    </div>
                                    <input type="radio" name="payment_method" value="card" class="hidden" checked>
                                </div>
                                <div class="payment-method border-2 border-gray-200 p-4 rounded-xl cursor-pointer hover:border-gray-300 transition-all" data-method="paypal">
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                                            <span class="text-white font-bold text-sm">P</span>
                                        </div>
                                        <p class="font-medium text-gray-900">PayPal</p>
                                        <p class="text-sm text-gray-600">Secure payment</p>
                                    </div>
                                    <input type="radio" name="payment_method" value="paypal" class="hidden">
                                </div>
                                <div class="payment-method border-2 border-gray-200 p-4 rounded-xl cursor-pointer hover:border-gray-300 transition-all" data-method="cash">
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                                            <span class="text-2xl">💵</span>
                                        </div>
                                        <p class="font-medium text-gray-900">Cash</p>
                                        <p class="text-sm text-gray-600">At the salon</p>
                                    </div>
                                    <input type="radio" name="payment_method" value="cash" class="hidden">
                                </div>
                            </div>
                        </div>

                        <!-- Card Payment Form -->
                        <div id="cardForm" class="space-y-6">
                            <div class="border-2 border-gray-200 rounded-xl p-6 bg-gray-50">
                                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                    <span class="w-8 h-8 bg-olive-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm">💳</span>
                                    </span>
                                    Card Details
                                </h3>

                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Card Number</label>
                                        <div class="relative">
                                            <input type="text" name="card_number" value="4242 4242 4242 4242" placeholder="1234 5678 9012 3456"
                                                   class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 text-lg focus:ring-2 focus:ring-olive-500 focus:border-olive-500 transition-all"
                                                   maxlength="19" required>
                                            <div class="absolute right-3 top-3 text-gray-400">
                                                <span class="text-sm">🔒</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                                        <input type="text" name="expiry" value="12/25" placeholder="MM/YY"
                                               class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 text-lg focus:ring-2 focus:ring-olive-500 focus:border-olive-500 transition-all"
                                               maxlength="5" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                                        <div class="relative">
                                            <input type="text" name="cvv" value="123" placeholder="123"
                                                   class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 text-lg focus:ring-2 focus:ring-olive-500 focus:border-olive-500 transition-all"
                                                   maxlength="4" required>
                                            <div class="absolute right-3 top-3 text-gray-400">
                                                <span class="text-sm">?</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Name on Card</label>
                                        <input type="text" name="card_name" value="John Doe" placeholder="As it appears on the card"
                                               class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 text-lg focus:ring-2 focus:ring-olive-500 focus:border-olive-500 transition-all"
                                               required>
                                    </div>
                                </div>
                            </div>

                            <!-- Billing Address -->
                            <div class="border-2 border-gray-200 rounded-xl p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Billing Address</h3>
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                        <input type="text" name="address" placeholder="Street, number, floor"
                                               class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-olive-500 focus:border-olive-500 transition-all">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                        <input type="text" name="city" placeholder="City"
                                               class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-olive-500 focus:border-olive-500 transition-all">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                                        <input type="text" name="postal_code" placeholder="12345"
                                               class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-olive-500 focus:border-olive-500 transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PayPal Form (Hidden by default) -->
                        <div id="paypalForm" class="hidden">
                            <div class="border-2 border-gray-200 rounded-xl p-6 bg-blue-50">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span class="text-white font-bold text-xl">P</span>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Pay with PayPal</h3>
                                    <p class="text-gray-600 mb-4">You will be redirected to PayPal to complete the payment securely</p>
                                    <button type="button" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                        Continue with PayPal
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Cash Form (Hidden by default) -->
                        <div id="cashForm" class="hidden">
                            <div class="border-2 border-gray-200 rounded-xl p-6 bg-green-50">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span class="text-2xl">💵</span>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Cash Payment</h3>
                                    <p class="text-gray-600 mb-4">Payment will be made directly at the salon at the time of service</p>
                                    <div class="bg-yellow-100 border border-yellow-300 rounded-lg p-4">
                                        <p class="text-sm text-yellow-800">
                                            <strong>Note:</strong> The booking will be confirmed once payment is made at the salon.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="submitBtn" class="w-full bg-olive-600 text-white py-4 px-6 rounded-xl hover:bg-olive-700 transition-all transform hover:scale-105 shadow-lg font-medium text-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                            <span class="flex items-center justify-center">
                                <span id="btnText">Pay €15.00</span>
                                <svg id="btnSpinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-2xl shadow-xl sticky top-6">
                    <h3 class="text-xl font-medium text-gray-900 mb-6">Order Summary</h3>

                    <div class="space-y-4">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-olive-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-lg">💅</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $appointment->service->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $appointment->date->format('d/m/Y') }} at {{ $appointment->time }}</p>
                                <p class="text-sm text-gray-500">{{ $appointment->service->duration }} minutes</p>
                            </div>
                        </div>

                        <div class="border-t pt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Service</span>
                                <span class="font-medium">€{{ $appointment->service->price }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Required deposit</span>
                                <span class="font-medium text-green-600">-€{{ $appointment->service->price - 15 }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold text-gray-900 border-t pt-2">
                                <span>Total to pay today</span>
                                <span>€15.00</span>
                            </div>
                        </div>

                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center mr-2">
                                    <span class="text-white text-xs">✓</span>
                                </div>
                                <span class="text-sm text-green-800 font-medium">Secure payment guaranteed</span>
                            </div>
                        </div>

                        <div class="text-xs text-gray-500 space-y-1">
                            <p>• Deposit is refundable up to 24h before</p>
                            <p>• The rest is paid upon completion of service</p>
                            <p>• You will receive email confirmation</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('.payment-method');
    const cardForm = document.getElementById('cardForm');
    const paypalForm = document.getElementById('paypalForm');
    const cashForm = document.getElementById('cashForm');
    const paymentForm = document.getElementById('paymentForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');

    // Payment method selection
    paymentMethods.forEach(method => {
        method.addEventListener('click', function() {
            // Remove selected class from all methods
            paymentMethods.forEach(m => {
                m.classList.remove('border-olive-500', 'bg-olive-50');
                m.querySelector('input[type="radio"]').checked = false;
            });

            // Add selected class
            this.classList.add('border-olive-500', 'bg-olive-50');
            this.querySelector('input[type="radio"]').checked = true;

            // Show corresponding form
            const methodType = this.dataset.method;
            cardForm.classList.add('hidden');
            paypalForm.classList.add('hidden');
            cashForm.classList.add('hidden');

            if (methodType === 'card') {
                cardForm.classList.remove('hidden');
            } else if (methodType === 'paypal') {
                paypalForm.classList.remove('hidden');
            } else if (methodType === 'cash') {
                cashForm.classList.remove('hidden');
            }
        });
    });

    // Card number formatting
    const cardNumberInput = document.querySelector('input[name="card_number"]');
    cardNumberInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue;
    });

    // Expiry date formatting
    const expiryInput = document.querySelector('input[name="expiry"]');
    expiryInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
    });

    // Form submission
    paymentForm.addEventListener('submit', function(e) {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!selectedMethod) {
            e.preventDefault();
            alert('Please select a payment method');
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        btnText.textContent = 'Processing payment...';
        btnSpinner.classList.remove('hidden');
    });
});
</script>
@endsection