@extends('layouts.app')

@section('title', 'Track Your Order — Plantify Official')

@section('content')

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-20">
        
        <!-- Header -->
        <div class="text-center space-y-2 mb-8">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-forest/5 text-forest text-xs font-mono uppercase tracking-wider font-semibold">
                <svg class="w-3.5 h-3.5 text-ochre" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
                Real-Time Fulfillment Tracker
            </span>
            <h1 class="font-display font-extrabold text-3xl text-forest tracking-tight">Track Your Botanical Order</h1>
            <p class="text-xs sm:text-sm text-forest-3 max-w-md mx-auto">
                Enter your order number and customer email address to view fulfillment status, shipment tracking, or payment verification.
            </p>
        </div>

        <!-- Search Form Card -->
        <div class="bg-paper border border-hairline rounded-2xl p-6 sm:p-8 shadow-sm">
            <form method="POST" action="{{ route('order.track.post') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="order_number" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Order Number *</label>
                    <input type="text" id="order_number" name="order_number" required value="{{ old('order_number') }}" placeholder="e.g. ORD-20261026-A1B2C" class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest font-mono focus:outline-none focus:border-ochre" />
                    <p class="text-[11px] text-forest-4 mt-1">Found in your order confirmation email or checkout screen.</p>
                </div>

                <div>
                    <label for="customer_email" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Customer Email Address *</label>
                    <input type="email" id="customer_email" name="customer_email" required value="{{ old('customer_email') }}" placeholder="e.g. name@example.com" class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" />
                    <p class="text-[11px] text-forest-4 mt-1">The email entered when placing the order.</p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn btn-ochre w-full text-sm">
                        Track Order Status &rarr;
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-8 text-center text-xs text-forest-3">
            <p>Need assistance with your botanical shipment? <a href="{{ route('about') }}" class="text-ochre hover:underline font-semibold">Contact Nursery Desk</a></p>
        </div>

    </div>

@endsection
