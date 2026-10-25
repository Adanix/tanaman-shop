@extends('layouts.app')

@section('title', 'Shopping Cart — Plantify Official')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-16">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-forest-3 font-mono mb-6" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-ochre">Home</a>
            <span>/</span>
            <span class="text-forest font-semibold">Shopping Cart</span>
        </nav>

        <div class="mb-8">
            <h1 class="font-display font-extrabold text-3xl sm:text-4xl text-forest tracking-tight">Your Botanical Cart</h1>
            <p class="text-xs sm:text-sm text-forest-3 mt-1.5">
                Review your selected nursery specimens before proceeding to fulfillment and payment.
            </p>
        </div>

        @if(empty($items))
            <!-- Empty Cart State -->
            <div class="bg-paper border border-hairline rounded-3xl p-10 sm:p-16 text-center max-w-xl mx-auto shadow-sm">
                <div class="w-20 h-20 rounded-full bg-glass flex items-center justify-center text-forest-3 mx-auto mb-5">
                    <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path d="M6 7h12l-1 13H7L6 7Z"/>
                        <path d="M9 7a3 3 0 0 1 6 0"/>
                    </svg>
                </div>
                <h2 class="font-display font-bold text-xl sm:text-2xl text-forest">Your Cart Is Empty</h2>
                <p class="text-xs sm:text-sm text-forest-3 mt-2 leading-relaxed">
                    You haven't added any living plants or ceramics yet. Discover our curated greenhouse specimens ready for immediate adoption.
                </p>
                <div class="mt-8 flex justify-center">
                    <a href="{{ route('shop') }}" class="btn btn-primary text-xs sm:text-sm px-6">
                        Explore Botanical Catalog
                    </a>
                </div>
            </div>
        @else
            <!-- Filled Cart State -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 xl:gap-12 items-start">
                
                <!-- Left Column: Items (col-span-8) -->
                <div class="lg:col-span-8 space-y-4">
                    <div class="bg-paper border border-hairline rounded-2xl p-4 sm:p-6 shadow-sm divide-y divide-hairline">
                        <div class="flex items-center justify-between pb-4">
                            <span class="font-display font-bold text-base text-forest">
                                {{ $count }} Item(s) Selected
                            </span>
                            <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Are you sure you want to clear your cart?');">
                                @csrf
                                <button type="submit" class="text-xs text-sale hover:underline font-mono">
                                    Clear Cart
                                </button>
                            </form>
                        </div>

                        @foreach($items as $plantId => $item)
                            @php
                                $plant = $item['plant'];
                            @endphp
                            <div class="py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('product.show', ['slug' => $plant->slug]) }}" class="w-20 h-20 rounded-xl overflow-hidden bg-glass flex-shrink-0 border border-hairline">
                                        <img src="{{ $plant->primary_image_url }}" alt="{{ $plant->name }}" class="w-full h-full object-cover" />
                                    </a>
                                    <div class="space-y-1">
                                        <span class="text-[11px] font-mono uppercase text-forest-4">{{ $plant->category->name ?? 'Houseplant' }}</span>
                                        <h3 class="font-semibold text-sm text-forest hover:text-ochre">
                                            <a href="{{ route('product.show', ['slug' => $plant->slug]) }}">{{ $plant->name }}</a>
                                        </h3>
                                        <p class="text-xs text-forest-3 italic font-mono">{{ $plant->botanical_name }}</p>
                                        <p class="font-mono text-xs font-semibold text-forest sm:hidden">
                                            ${{ number_format($item['unit_price'], 2) }} each
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto">
                                    <!-- Stepper Form -->
                                    <form method="POST" action="{{ route('cart.update') }}" class="flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="plant_id" value="{{ $plantId }}" />
                                        <div class="flex items-center border border-hairline rounded-full bg-glass px-2 py-1">
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}" class="w-6 h-6 rounded-full hover:bg-paper flex items-center justify-center text-xs font-bold text-forest">
                                                &minus;
                                            </button>
                                            <span class="w-8 text-center font-mono font-bold text-xs text-forest">
                                                {{ $item['quantity'] }}
                                            </span>
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" class="w-6 h-6 rounded-full hover:bg-paper flex items-center justify-center text-xs font-bold text-forest" {{ $item['quantity'] >= $plant->stock ? 'disabled' : '' }}>
                                                &plus;
                                            </button>
                                        </div>
                                    </form>

                                    <!-- Subtotal -->
                                    <div class="text-right min-w-[80px]">
                                        <span class="font-mono font-bold text-sm text-forest block">
                                            ${{ number_format($item['subtotal'], 2) }}
                                        </span>
                                        <span class="text-[11px] font-mono text-forest-4 hidden sm:block">
                                            ${{ number_format($item['unit_price'], 2) }} / ea
                                        </span>
                                    </div>

                                    <!-- Remove Item -->
                                    <form method="POST" action="{{ route('cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="plant_id" value="{{ $plantId }}" />
                                        <button type="submit" class="btn-icon !w-8 !h-8 text-forest-4 hover:text-sale" title="Remove item">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Digital Garden Notice -->
                    <div class="p-4 rounded-2xl bg-paper border border-hairline flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-stock-wash text-stock flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 21V9" />
                                <path d="M12 13c0-3.3 2.7-6 6-6 0 3.3-2.7 6-6 6Z" />
                                <path d="M12 16c0-2.8-2.2-5-5-5 0 2.8 2.2 5 5 5Z" />
                            </svg>
                        </div>
                        <div class="text-xs text-forest-2 leading-relaxed">
                            <strong class="font-semibold text-forest">Digital Garden Auto-Adoption:</strong> Each physical plant unit in this order will receive its own individual tracking identity in your Digital Garden upon delivery or store handover.
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Summary (col-span-4) -->
                <div class="lg:col-span-4 space-y-4">
                    <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-4">
                        <h2 class="font-display font-bold text-lg text-forest pb-3 border-b border-hairline">
                            Order Summary
                        </h2>

                        <div class="space-y-2.5 text-xs text-forest-2">
                            <div class="flex justify-between items-center">
                                <span>Items Subtotal</span>
                                <span class="font-mono font-semibold text-forest">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Estimated Shipping</span>
                                <span class="font-mono text-forest-3">Calculated next step</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Sales Tax (8%)</span>
                                <span class="font-mono text-forest-3">Calculated next step</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-hairline flex justify-between items-baseline">
                            <span class="font-display font-bold text-base text-forest">Estimated Total</span>
                            <span class="font-mono font-bold text-xl text-forest">
                                ${{ number_format($subtotal, 2) }}
                            </span>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('checkout.index') }}" class="btn btn-ochre w-full text-sm">
                                Proceed to Checkout &rarr;
                            </a>
                        </div>

                        <div class="pt-2 text-center">
                            <a href="{{ route('shop') }}" class="text-xs text-forest-3 hover:text-ochre">
                                &larr; Continue Shopping
                            </a>
                        </div>
                    </div>

                    <!-- Payment Security Trust Badge -->
                    <div class="p-4 rounded-2xl bg-paper border border-hairline text-xs text-forest-3 space-y-2">
                        <div class="flex items-center gap-2 font-semibold text-forest">
                            <svg class="w-4 h-4 text-stock" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span>Accepted Payment Methods</span>
                        </div>
                        <p class="leading-relaxed text-[11px]">
                            Stripe Credit/Debit Card (31-min secure session), Manual Bank Transfer (verified within 2h), or Pay in Store at our physical nursery desk.
                        </p>
                    </div>
                </div>

            </div>
        @endif

    </div>

@endsection
