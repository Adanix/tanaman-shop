@extends('layouts.app')

@section('title', 'Order Confirmation #' . $order->order_number . ' — Plantify Official')

@section('content')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
        
        <!-- Header confirmation badge -->
        <div class="text-center space-y-3 mb-8">
            <div class="w-16 h-16 rounded-full bg-stock-wash text-stock flex items-center justify-center mx-auto shadow-sm">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="font-display font-extrabold text-3xl text-forest tracking-tight">Order Received!</h1>
            <p class="text-xs sm:text-sm text-forest-3 max-w-lg mx-auto">
                Thank you, <strong>{{ $order->customer_name }}</strong>. Your botanical order has been registered with Order Number <span class="font-mono font-bold text-forest">{{ $order->order_number }}</span>.
            </p>
        </div>

        <div class="space-y-6">
            
            <!-- Fulfillment Special Banner -->
            @if($order->isStorePickup())
                <div class="bg-forest text-glass rounded-2xl p-6 shadow-md flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="space-y-1 text-center sm:text-left">
                        <span class="text-xs font-mono uppercase text-ochre font-semibold tracking-wider">Store Pickup Desk</span>
                        <h2 class="font-display font-bold text-xl text-glass">Your Pickup Code</h2>
                        <p class="text-xs text-glass/70">Show this code at our nursery counter when picking up your plants.</p>
                    </div>
                    <div class="px-6 py-3 bg-glass text-forest rounded-xl font-mono font-extrabold text-2xl tracking-widest border border-ochre shadow-inner">
                        {{ $order->pickup_code }}
                    </div>
                </div>
            @endif

            <!-- Order Details Card -->
            <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-6">
                
                <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-hairline">
                    <div>
                        <span class="text-xs font-mono text-forest-4 uppercase">Tracking Reference</span>
                        <p class="font-mono text-xs text-forest-2 truncate max-w-xs">{{ $order->tracking_token }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="badge {{ $order->isPaid() ? 'badge-stock' : 'badge-low' }}">
                            Payment: {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                        </span>
                        <span class="badge badge-stock">
                            Status: {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                        </span>
                    </div>
                </div>

                <!-- Items Ordered -->
                <div class="space-y-3">
                    <h3 class="font-display font-bold text-sm text-forest">Ordered Botanicals</h3>
                    <div class="divide-y divide-hairline">
                        @foreach($order->items as $item)
                            <div class="py-3 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-glass overflow-hidden flex-shrink-0 border border-hairline">
                                        <img src="{{ $item->plant->primary_image_url ?? '' }}" alt="{{ $item->plant_name }}" class="w-full h-full object-cover" />
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-forest">{{ $item->plant_name }}</h4>
                                        <p class="font-mono text-forest-4">{{ $item->quantity }}x @ ${{ number_format($item->unit_price, 2) }}</p>
                                    </div>
                                </div>
                                <span class="font-mono font-bold text-forest">${{ number_format($item->subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Financial Breakdown -->
                <div class="pt-4 border-t border-hairline space-y-2 text-xs text-forest-2">
                    <div class="flex justify-between items-center">
                        <span>Items Subtotal</span>
                        <span class="font-mono font-semibold text-forest">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Fulfillment ({{ ucfirst(str_replace('_', ' ', $order->fulfillment_type)) }})</span>
                        <span class="font-mono font-semibold text-forest">${{ number_format($order->shipping_fee, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Sales Tax</span>
                        <span class="font-mono font-semibold text-forest">${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    <div class="pt-2 border-t border-hairline flex justify-between items-baseline font-bold text-sm text-forest">
                        <span>Total Paid / Payable</span>
                        <span class="font-mono text-lg">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>

            </div>

            <!-- Next Steps & Action Card -->
            <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-4">
                <h3 class="font-display font-bold text-base text-forest">Next Steps for Your Order</h3>

                @if($order->payment_method === 'online_stripe' && !$order->isPaid() && !$order->isCancelled())
                    <div class="p-4 rounded-xl bg-low-wash border border-low/30 text-low space-y-2 text-xs">
                        <p class="font-semibold">Stripe Payment Incomplete or Pending</p>
                        <p>Your session expires in 31 minutes from order creation. If you were interrupted, you can resume payment right now:</p>
                        <div class="pt-1">
                            <a href="{{ route('order.resume-stripe', ['token' => $order->tracking_token]) }}" class="btn btn-ochre text-xs">
                                Resume Stripe Checkout &rarr;
                            </a>
                        </div>
                    </div>
                @elseif($order->payment_method === 'bank_transfer' && !$order->isPaid() && !$order->isCancelled())
                    <div class="p-4 rounded-xl bg-glass border border-hairline space-y-4 text-xs">
                        <div>
                            <p class="font-semibold text-forest text-sm">Please Complete Your Bank Transfer</p>
                            <p class="text-forest-3 mt-1">Transfer exact amount <strong class="font-mono text-forest">${{ number_format($order->total_amount, 2) }}</strong> to one of our designated bank accounts within 12 hours:</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($bankAccounts as $bank)
                                <div class="p-3 bg-paper rounded-lg border border-hairline">
                                    <p class="font-bold text-forest">{{ $bank->bank_name }}</p>
                                    <p class="font-mono text-sm font-semibold text-ochre mt-1">{{ $bank->account_number }}</p>
                                    <p class="text-forest-4 text-[11px]">Holder: {{ $bank->account_holder }}</p>
                                </div>
                            @endforeach
                        </div>

                        <!-- Upload Proof Form -->
                        <div class="pt-2 border-t border-hairline">
                            <h4 class="font-semibold text-forest mb-2">Upload Transfer Receipt Proof:</h4>
                            <form method="POST" action="{{ route('order.upload-proof', ['token' => $order->tracking_token]) }}" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-mono uppercase text-forest-3 mb-1">Receipt File (JPG, PNG, max 4MB) *</label>
                                        <input type="file" name="payment_proof" required accept="image/*" class="w-full text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-forest file:text-glass hover:file:bg-forest-2 cursor-pointer" />
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-mono uppercase text-forest-3 mb-1">Sender Bank Name</label>
                                        <input type="text" name="sender_bank_name" placeholder="e.g. Chase Bank" class="w-full text-xs h-9 px-3 bg-paper border border-hairline rounded-lg text-forest" />
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary text-xs">
                                    Submit Transfer Receipt Proof
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <a href="{{ route('order.track', ['token' => $order->tracking_token]) }}" class="btn btn-primary text-xs">
                        View Live Tracking Page
                    </a>
                    <a href="{{ route('shop') }}" class="btn btn-ghost text-xs">
                        Return to Store
                    </a>
                </div>
            </div>

        </div>

    </div>

@endsection
