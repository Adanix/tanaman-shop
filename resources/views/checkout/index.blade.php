@extends('layouts.app')

@section('title', 'Checkout — Plantify Official')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-16"
         x-data="{
             fulfillment: '{{ old('fulfillment_type', 'delivery') }}',
             paymentMethod: '{{ old('payment_method', 'online_stripe') }}',
             isGift: {{ old('is_gift') ? 'true' : 'false' }},
             subtotal: {{ $subtotal }},
             deliveryFee: 15.00,
             taxRate: 0.08,
             get shipping() {
                 return this.fulfillment === 'delivery' ? this.deliveryFee : 0;
             },
             get tax() {
                 return Math.round((this.subtotal + this.shipping) * this.taxRate * 100) / 100;
             },
             get total() {
                 return Math.round((this.subtotal + this.shipping + this.tax) * 100) / 100;
             }
         }">

        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-forest-3 font-mono mb-6" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-ochre">Home</a>
            <span>/</span>
            <a href="{{ route('cart.index') }}" class="hover:text-ochre">Cart</a>
            <span>/</span>
            <span class="text-forest font-semibold">Checkout</span>
        </nav>

        <div class="mb-8">
            <h1 class="font-display font-extrabold text-3xl sm:text-4xl text-forest tracking-tight">Botanical Checkout</h1>
            <p class="text-xs sm:text-sm text-forest-3 mt-1.5">
                Complete your customer details, select fulfillment, and choose your payment method.
            </p>
        </div>

        <form method="POST" action="{{ route('checkout.store') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-8 xl:gap-12 items-start">
            @csrf

            <!-- Left Form Area (col-span-8) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- 1. Customer Information -->
                <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-4">
                    <h2 class="font-display font-bold text-base text-forest flex items-center gap-2 pb-3 border-b border-hairline">
                        <span class="w-6 h-6 rounded-full bg-forest text-glass text-xs font-mono flex items-center justify-center font-bold">1</span>
                        <span>Customer Information</span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="customer_name" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Full Name *</label>
                            <input type="text" id="customer_name" name="customer_name" required value="{{ old('customer_name', $user->name ?? '') }}" class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" placeholder="e.g. Jane Doe" />
                        </div>

                        <div>
                            <label for="customer_email" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Email Address *</label>
                            <input type="email" id="customer_email" name="customer_email" required value="{{ old('customer_email', $user->email ?? '') }}" class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" placeholder="jane@example.com" />
                            <p class="text-[11px] text-forest-4 mt-1 font-mono">Order confirmation and tracking links will be sent here.</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="customer_phone" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Phone Number</label>
                            <input type="tel" id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $user->phone ?? '') }}" class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" placeholder="+1 (555) 000-0000" />
                        </div>
                    </div>
                </div>

                <!-- 2. Fulfillment Method -->
                <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-4">
                    <h2 class="font-display font-bold text-base text-forest flex items-center gap-2 pb-3 border-b border-hairline">
                        <span class="w-6 h-6 rounded-full bg-forest text-glass text-xs font-mono flex items-center justify-center font-bold">2</span>
                        <span>Fulfillment Method</span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="p-4 rounded-xl border cursor-pointer transition-all flex flex-col justify-between"
                               :class="fulfillment === 'delivery' ? 'border-forest bg-forest/5' : 'border-hairline bg-glass/50 hover:border-forest-3'">
                            <div class="flex items-start justify-between">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <input type="radio" name="fulfillment_type" value="delivery" x-model="fulfillment" class="text-ochre focus:ring-ochre" />
                                        <span class="font-display font-bold text-sm text-forest">Climate-Safe Delivery</span>
                                    </div>
                                    <p class="text-xs text-forest-3">Delivered to your doorstep with insulated thermal root protection.</p>
                                </div>
                                <span class="font-mono text-xs font-bold text-forest">$15.00</span>
                            </div>
                        </label>

                        <label class="p-4 rounded-xl border cursor-pointer transition-all flex flex-col justify-between"
                               :class="fulfillment === 'store_pickup' ? 'border-forest bg-forest/5' : 'border-hairline bg-glass/50 hover:border-forest-3'">
                            <div class="flex items-start justify-between">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <input type="radio" name="fulfillment_type" value="store_pickup" x-model="fulfillment" class="text-ochre focus:ring-ochre" />
                                        <span class="font-display font-bold text-sm text-forest">Store Pickup</span>
                                    </div>
                                    <p class="text-xs text-forest-3">Pick up directly at our physical nursery desk with your secure pickup code.</p>
                                </div>
                                <span class="font-mono text-xs font-bold text-stock">Free ($0.00)</span>
                            </div>
                        </label>
                    </div>

                    <!-- Shipping Address Inputs (If Delivery) -->
                    <div x-show="fulfillment === 'delivery'" class="space-y-4 pt-3 border-t border-hairline">
                        <h3 class="text-xs font-mono uppercase tracking-wider text-forest-3 font-semibold">Shipping Address</h3>
                        
                        <div>
                            <label for="shipping_address" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Street Address *</label>
                            <input type="text" id="shipping_address" name="shipping_address" :required="fulfillment === 'delivery'" value="{{ old('shipping_address', $user->address ?? '') }}" class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" placeholder="123 Botanical Avenue, Apt 4B" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="shipping_city" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">City *</label>
                                <input type="text" id="shipping_city" name="shipping_city" :required="fulfillment === 'delivery'" value="{{ old('shipping_city', $user->city ?? '') }}" class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" placeholder="Jakarta Selatan" />
                            </div>
                            <div>
                                <label for="shipping_postal_code" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Postal Code *</label>
                                <input type="text" id="shipping_postal_code" name="shipping_postal_code" :required="fulfillment === 'delivery'" value="{{ old('shipping_postal_code', $user->postal_code ?? '') }}" class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" placeholder="12340" />
                            </div>
                        </div>
                    </div>

                    <!-- Nursery Desk Details (If Store Pickup) -->
                    <div x-show="fulfillment === 'store_pickup'" x-cloak class="p-4 rounded-xl bg-glass border border-hairline space-y-2 text-xs">
                        <div class="flex items-center gap-2 font-semibold text-forest">
                            <svg class="w-4 h-4 text-ochre" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $storeInfo['name'] }} Pickup Location</span>
                        </div>
                        <p class="text-forest-2">{{ $storeInfo['address'] }}, {{ $storeInfo['city'] }}</p>
                        <p class="text-forest-3 font-mono text-[11px]">Hours: {{ $storeInfo['hours'] }}</p>
                        <p class="text-forest-4 text-[11px] leading-relaxed">A unique 6-character Pickup Code will be generated for your order. Present this code at our POS desk to collect your plants.</p>
                    </div>
                </div>

                <!-- 3. Gift Order Option -->
                <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_gift" value="1" x-model="isGift" class="rounded border-hairline text-ochre focus:ring-ochre w-4 h-4" />
                            <span class="font-display font-bold text-sm text-forest">This order is a botanical gift for someone else</span>
                        </label>
                        <span class="badge badge-stock font-mono">Complimentary Unboxing Page</span>
                    </div>

                    <div x-show="isGift" x-cloak class="space-y-4 pt-4 border-t border-hairline">
                        <p class="text-xs text-forest-3 leading-relaxed">
                            When marked as a gift, physical prices will be hidden. The recipient receives a personalized unboxing link upon delivery where they can adopt the plants into their own Digital Garden!
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="recipient_name" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Recipient Name *</label>
                                <input type="text" id="recipient_name" name="recipient_name" :required="isGift" value="{{ old('recipient_name') }}" class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" placeholder="e.g. Alex Smith" />
                            </div>
                            <div>
                                <label for="recipient_email" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Recipient Email *</label>
                                <input type="email" id="recipient_email" name="recipient_email" :required="isGift" value="{{ old('recipient_email') }}" class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" placeholder="alex@example.com" />
                            </div>
                        </div>

                        <div>
                            <label for="gift_message" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Personal Gift Message</label>
                            <textarea id="gift_message" name="gift_message" rows="3" class="w-full text-xs p-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" placeholder="Write your heartfelt greeting here...">{{ old('gift_message') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- 4. Payment Method -->
                <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-4">
                    <h2 class="font-display font-bold text-base text-forest flex items-center gap-2 pb-3 border-b border-hairline">
                        <span class="w-6 h-6 rounded-full bg-forest text-glass text-xs font-mono flex items-center justify-center font-bold">3</span>
                        <span>Payment Method</span>
                    </h2>

                    <div class="space-y-3">
                        
                        <!-- Stripe Online -->
                        <label class="p-4 rounded-xl border cursor-pointer transition-all flex items-start justify-between"
                               :class="paymentMethod === 'online_stripe' ? 'border-forest bg-forest/5' : 'border-hairline bg-glass/50 hover:border-forest-3'">
                            <div class="flex items-start gap-3">
                                <input type="radio" name="payment_method" value="online_stripe" x-model="paymentMethod" class="mt-0.5 text-ochre focus:ring-ochre" />
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-display font-bold text-sm text-forest">Online Stripe Checkout</span>
                                        <span class="badge badge-stock text-[10px]">Instant Confirmation</span>
                                    </div>
                                    <p class="text-xs text-forest-3 mt-0.5">Pay securely with Credit Card, Debit Card, or Apple Pay. Hosted session expires in 31 minutes.</p>
                                </div>
                            </div>
                        </label>

                        <!-- Bank Transfer -->
                        <label class="p-4 rounded-xl border cursor-pointer transition-all flex items-start justify-between"
                               :class="paymentMethod === 'bank_transfer' ? 'border-forest bg-forest/5' : 'border-hairline bg-glass/50 hover:border-forest-3'">
                            <div class="flex items-start gap-3">
                                <input type="radio" name="payment_method" value="bank_transfer" x-model="paymentMethod" class="mt-0.5 text-ochre focus:ring-ochre" />
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-display font-bold text-sm text-forest">Manual Bank Transfer</span>
                                        <span class="badge badge-low text-[10px]">12-Hour Grace Period</span>
                                    </div>
                                    <p class="text-xs text-forest-3 mt-0.5">Transfer directly to our nursery bank accounts and upload proof. Verified by store staff within 2 hours.</p>
                                </div>
                            </div>
                        </label>

                        <!-- Bank Account Details (When Bank Transfer Selected) -->
                        <div x-show="paymentMethod === 'bank_transfer'" x-cloak class="p-4 rounded-xl bg-glass border border-hairline space-y-3 text-xs">
                            <span class="font-mono text-[11px] font-semibold text-forest uppercase tracking-wider block">Official Nursery Bank Accounts:</span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($bankAccounts as $bank)
                                    <div class="p-3 bg-paper rounded-lg border border-hairline">
                                        <p class="font-bold text-forest text-xs">{{ $bank->bank_name }}</p>
                                        <p class="font-mono text-sm font-semibold text-ochre mt-1">{{ $bank->account_number }}</p>
                                        <p class="text-forest-4 text-[11px] mt-0.5">a.n. {{ $bank->account_holder }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Pay in Store (Only available if Store Pickup) -->
                        <div x-show="fulfillment === 'store_pickup'" x-cloak>
                            <label class="p-4 rounded-xl border cursor-pointer transition-all flex items-start justify-between"
                                   :class="paymentMethod === 'pay_in_store' ? 'border-forest bg-forest/5' : 'border-hairline bg-glass/50 hover:border-forest-3'">
                                <div class="flex items-start gap-3">
                                    <input type="radio" name="payment_method" value="pay_in_store" x-model="paymentMethod" class="mt-0.5 text-ochre focus:ring-ochre" />
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-display font-bold text-sm text-forest">Pay in Store (Pickup Desk)</span>
                                            <span class="badge badge-stock text-[10px]">Cash / POS</span>
                                        </div>
                                        <p class="text-xs text-forest-3 mt-0.5">Pay in cash or credit card directly at our nursery counter when picking up your items.</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                    </div>
                </div>

            </div>

            <!-- Right Column: Order Review & Submit (col-span-4) -->
            <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
                <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-4">
                    <h2 class="font-display font-bold text-base text-forest pb-3 border-b border-hairline">
                        Order Summary
                    </h2>

                    <!-- Items Preview -->
                    <div class="space-y-3 max-h-60 overflow-y-auto divide-y divide-hairline pr-1">
                        @foreach($items as $item)
                            <div class="pt-2 first:pt-0 flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-2 truncate">
                                    <span class="font-mono font-bold text-forest">{{ $item['quantity'] }}x</span>
                                    <span class="text-forest truncate">{{ $item['plant']->name }}</span>
                                </div>
                                <span class="font-mono font-semibold text-forest flex-shrink-0">${{ number_format($item['subtotal'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Breakdown Calculation -->
                    <div class="pt-4 border-t border-hairline space-y-2 text-xs text-forest-2">
                        <div class="flex justify-between items-center">
                            <span>Subtotal</span>
                            <span class="font-mono font-semibold text-forest">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Shipping</span>
                            <span class="font-mono font-semibold text-forest" x-text="'$' + shipping.toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Sales Tax (8%)</span>
                            <span class="font-mono font-semibold text-forest" x-text="'$' + tax.toFixed(2)"></span>
                        </div>
                    </div>

                    <!-- Total Amount -->
                    <div class="pt-4 border-t border-hairline flex justify-between items-baseline">
                        <span class="font-display font-bold text-base text-forest">Grand Total</span>
                        <span class="font-mono font-bold text-2xl text-forest" x-text="'$' + total.toFixed(2)"></span>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-3">
                        <button type="submit" class="btn btn-ochre w-full text-sm !py-3">
                            Confirm &amp; Place Order &rarr;
                        </button>
                    </div>

                    <p class="text-[11px] text-forest-4 text-center leading-relaxed font-mono">
                        By placing your order, you agree to our 14-day nursery health guarantee and store terms.
                    </p>
                </div>
            </div>

        </form>

    </div>

@endsection
