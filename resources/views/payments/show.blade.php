@extends('layouts.app')

@section('title', 'Payment - Nail Art Studio')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid md:grid-cols-2 gap-8">
        <!-- Appointment Summary -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <h2 class="text-2xl font-serif font-bold text-gray-900 mb-6">Appointment Summary</h2>

            <!-- Service -->
            <div class="mb-6">
                <div class="text-4xl mb-3">💅</div>
                <h3 class="text-xl font-bold text-gray-900">{{ $appointment->service->name }}</h3>
                <p class="text-gray-600">{{ $appointment->service->description }}</p>
            </div>

            <!-- Details -->
            <div class="space-y-4 py-6 border-y border-gray-200">
                <div class="flex justify-between">
                    <span class="text-gray-600">Date</span>
                    <span class="font-bold text-gray-900">{{ $appointment->date->format('l, d F Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Time</span>
                    <span class="font-bold text-gray-900">{{ $appointment->time }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Duration</span>
                    <span class="font-bold text-gray-900">{{ $appointment->service->duration }} minutes</span>
                </div>
            </div>

            <!-- Price -->
            <div class="mt-6 p-4 bg-gradient-to-r from-rose/10 to-pink-300/10 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-900">Total Price</span>
                    <span class="text-3xl font-bold text-rose">€{{ number_format($appointment->service->price, 2) }}</span>
                </div>
                <div class="mt-3 border-t border-rose/20 pt-3 space-y-1 text-sm text-gray-700">
                    <div class="flex justify-between">
                        <span>Deposit due now</span>
                        <span class="font-semibold">€{{ number_format($payment->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Remaining after service</span>
                        <span class="font-semibold">€{{ number_format(max(0, $appointment->service->price - $payment->amount), 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-6">
                <h2 class="text-2xl font-serif font-bold text-gray-900 mb-6">Payment Method</h2>

                <form action="{{ route('payments.process', $appointment) }}" method="POST" id="payment-form">
                    @csrf

                    <!-- Deposit Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                            <div>
                                <p class="font-semibold text-blue-900">Deposit Amount</p>
                                <p class="text-sm text-blue-800">€{{ number_format($payment->amount, 2) }} to confirm your appointment. The remaining balance is paid after the service.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="space-y-4 mb-8">
                        <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-rose transition-colors has-[:checked]:border-rose has-[:checked]:bg-rose/5">
                            <input type="radio" name="payment_method" value="card" class="w-4 h-4 text-rose" checked>
                            <div class="ml-4">
                                <p class="font-semibold text-gray-900">Debit / Credit Card</p>
                                <p class="text-sm text-gray-600">Visa, Mastercard, American Express</p>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-olive transition-colors has-[:checked]:border-olive has-[:checked]:bg-olive/5">
                            <input type="radio" name="payment_method" value="transfer" class="w-4 h-4 text-olive">
                            <div class="ml-4">
                                <p class="font-semibold text-gray-900">Bank Transfer</p>
                                <p class="text-sm text-gray-600">We'll send you bank details</p>
                            </div>
                        </label>
                    </div>

                    @error('payment_method')
                        <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
                    @enderror

                    <!-- Submit -->
                    <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-rose to-pink-400 text-white rounded-lg hover:shadow-lg transition-all duration-300 font-semibold text-lg">
                        <i class="fas fa-lock mr-2"></i> Pay Deposit
                    </button>

                    <p class="text-xs text-gray-500 text-center mt-4">
                        Your payment is secure and encrypted. This is a simulated payment for demo purposes.
                    </p>
                </form>
            </div>

            <!-- Back Link -->
            <a href="{{ route('home') }}" class="block text-center px-6 py-3 text-gray-700 hover:text-gray-900 transition-colors font-medium">
                ← Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
