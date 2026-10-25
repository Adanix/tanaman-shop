@extends('layouts.app')

@section('title', 'Order Tracking #' . $order->order_number . ' — Plantify Official')

@section('content')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
        
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-forest-3 font-mono mb-6" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-ochre">Home</a>
            <span>/</span>
            <a href="{{ route('order.track.search') }}" class="hover:text-ochre">Track Order</a>
            <span>/</span>
            <span class="text-forest font-semibold">{{ $order->order_number }}</span>
        </nav>

        <!-- Order Header Card -->
        <div class="bg-paper border border-hairline rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-hairline">
                <div>
                    <span class="text-xs font-mono text-forest-4 uppercase">Order Placed: {{ $order->created_at->format('M d, Y - H:i') }}</span>
                    <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-forest tracking-tight mt-0.5">
                        {{ $order->order_number }}
                    </h1>
                    <p class="text-xs text-forest-3 mt-1">Recipient: <strong>{{ $order->customer_name }}</strong> ({{ $order->customer_email }})</p>
                </div>
                
                <div class="flex items-center gap-2 flex-wrap">
                    <!-- Order Status Badge -->
                    <span class="badge {{ in_array($order->order_status, ['delivered', 'completed']) ? 'badge-stock' : ($order->order_status === 'cancelled' ? 'badge-sale' : 'badge-new') }}">
                        Status: {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                    </span>
                    <!-- Payment Status Badge -->
                    <span class="badge {{ $order->isPaid() ? 'badge-stock' : ($order->payment_status === 'awaiting_verification' ? 'badge-low' : ($order->isCancelled() ? 'badge-sale' : 'badge-low')) }}">
                        Payment: {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                    </span>
                </div>
            </div>

            <!-- Fulfillment Progress Timeline -->
            @php
                $statusListDelivery = ['pending', 'processing', 'shipped', 'delivered'];
                $statusListPickup = ['pending', 'processing', 'ready_for_pickup', 'completed'];
                $currentStatusList = $order->isStorePickup() ? $statusListPickup : $statusListDelivery;
                $currentIndex = array_search($order->order_status, $currentStatusList);
                if ($currentIndex === false && $order->order_status === 'cancelled') {
                    $currentIndex = -1;
                }
            @endphp

            @if(!$order->isCancelled())
                <div class="py-4">
                    <h3 class="text-xs font-mono uppercase tracking-wider text-forest-3 font-semibold mb-6">Fulfillment Timeline</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 relative">
                        @foreach($currentStatusList as $idx => $step)
                            @php
                                $isComplete = $currentIndex !== false && $idx <= $currentIndex;
                                $isCurrent = $currentIndex !== false && $idx === $currentIndex;
                            @endphp
                            <div class="flex flex-col items-center text-center space-y-2">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-mono font-bold transition-all
                                    {{ $isComplete ? 'bg-stock text-white shadow-sm' : 'bg-glass border border-hairline text-forest-4' }}">
                                    @if($isComplete)
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        {{ $idx + 1 }}
                                    @endif
                                </div>
                                <span class="text-xs font-medium capitalize {{ $isCurrent ? 'text-forest font-bold' : ($isComplete ? 'text-forest-2' : 'text-forest-4') }}">
                                    {{ str_replace('_', ' ', $step) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="p-4 rounded-xl bg-sale-wash border border-sale/30 text-sale text-xs font-medium flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="15" y1="9" x2="9" y2="15" />
                        <line x1="9" y1="9" x2="15" y2="15" />
                    </svg>
                    <div>
                        <strong class="font-bold">This order has been cancelled.</strong>
                        <p class="mt-0.5 text-[11px]">Reserved inventory has been restored to the nursery catalog.</p>
                    </div>
                </div>
            @endif

            <!-- Pickup Details (If Store Pickup) -->
            @if($order->isStorePickup())
                <div class="p-5 rounded-xl bg-glass border border-hairline flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <span class="text-[11px] font-mono uppercase text-ochre font-semibold">Store Pickup Verification</span>
                        <h4 class="font-display font-bold text-base text-forest mt-0.5">Physical Counter Handover</h4>
                        <p class="text-xs text-forest-3 mt-1">Visit our nursery desk and present this code along with your name:</p>
                    </div>
                    <div class="px-5 py-2.5 bg-paper rounded-xl border border-ochre font-mono font-extrabold text-xl text-forest tracking-wider shadow-sm">
                        {{ $order->pickup_code }}
                    </div>
                </div>
            @endif

            <!-- Delivery Tracking Information (If Shipped) -->
            @if($order->order_status === 'shipped' || $order->order_status === 'delivered')
                <div class="p-4 rounded-xl bg-glass border border-hairline space-y-2 text-xs">
                    <div class="flex items-center gap-2 font-semibold text-forest">
                        <svg class="w-4 h-4 text-stock" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Shipment Dispatch Details</span>
                    </div>
                    <p class="text-forest-2">
                        Carrier: <strong>{{ $order->carrier ?? 'Standard Nursery Climate Express' }}</strong> &bull; Tracking No: <strong class="font-mono">{{ $order->tracking_number ?? 'In Transit' }}</strong>
                    </p>
                    @if($order->shipped_at)
                        <p class="text-[11px] text-forest-4 font-mono">Dispatched at: {{ $order->shipped_at->format('M d, Y - H:i') }}</p>
                    @endif
                </div>
            @endif

            <!-- Items Ordered Breakdown -->
            <div class="space-y-3 pt-4 border-t border-hairline">
                <h3 class="font-display font-bold text-sm text-forest">Botanical Line Items</h3>
                <div class="divide-y divide-hairline">
                    @foreach($order->items as $item)
                        <div class="py-3 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg bg-glass overflow-hidden flex-shrink-0 border border-hairline">
                                    <img src="{{ $item->plant->primary_image_url ?? '' }}" alt="{{ $item->plant_name }}" class="w-full h-full object-cover" />
                                </div>
                                <div>
                                    <h4 class="font-semibold text-forest">{{ $item->plant_name }}</h4>
                                    <p class="font-mono text-forest-4">{{ $item->quantity }}x unit(s) @ ${{ number_format($item->unit_price, 2) }}</p>
                                </div>
                            </div>
                            <span class="font-mono font-bold text-forest">${{ number_format($item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-hairline space-y-1.5 text-xs text-forest-2">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="font-mono">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Fulfillment ({{ ucfirst(str_replace('_', ' ', $order->fulfillment_type)) }})</span>
                        <span class="font-mono">${{ number_format($order->shipping_fee, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Sales Tax</span>
                        <span class="font-mono">${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-sm text-forest pt-2 border-t border-hairline">
                        <span>Total</span>
                        <span class="font-mono">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Digital Garden Adoption Button (If Completed/Delivered) -->
            @if(in_array($order->order_status, ['delivered', 'completed']) && !$order->is_gift)
                <div class="p-6 rounded-2xl bg-stock-wash border border-stock/30 space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-stock text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 21V9" />
                                <path d="M12 13c0-3.3 2.7-6 6-6 0 3.3-2.7 6-6 6Z" />
                                <path d="M12 16c0-2.8-2.2-5-5-5 0 2.8 2.2 5 5 5Z" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h4 class="font-display font-bold text-base text-forest">Adopt Specimens to My Digital Garden</h4>
                            <p class="text-xs text-forest-2 leading-relaxed">
                                Your order has arrived! Click below to adopt each individual physical unit into your Digital Garden to activate weather-aware watering schedules and AI health checks.
                            </p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('order.adopt', ['token' => $order->tracking_token]) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary text-xs">
                            Adopt to My Digital Garden &rarr;
                        </button>
                    </form>
                </div>
            @endif

            <!-- Gift Unboxing Link (If Gift) -->
            @if($order->is_gift && $order->gift)
                <div class="p-6 rounded-2xl bg-ochre-wash border border-ochre/30 space-y-3">
                    <span class="badge badge-new font-mono text-[10px]">Gift Order</span>
                    <h4 class="font-display font-bold text-base text-forest">Complimentary Gift Unboxing Experience</h4>
                    <p class="text-xs text-forest-2 leading-relaxed">
                        This order is designated for recipient <strong>{{ $order->gift->recipient_name }}</strong> ({{ $order->gift->recipient_email }}). Once delivered, the recipient can claim and adopt these plants into their own Digital Garden account via their private unboxing link.
                    </p>
                    <div class="pt-1">
                        <a href="{{ route('gift.show', ['token' => $order->gift->token]) }}" class="btn btn-ochre text-xs" target="_blank">
                            Preview Gift Unboxing Landing Page &rarr;
                        </a>
                    </div>
                </div>
            @endif

            <!-- Pending Bank Transfer Upload Proof Section -->
            @if($order->payment_method === 'bank_transfer' && $order->payment_status === 'pending')
                <div class="p-5 rounded-xl bg-glass border border-hairline space-y-4 text-xs">
                    <div>
                        <h4 class="font-semibold text-forest text-sm">Upload Bank Transfer Receipt Proof</h4>
                        <p class="text-forest-3 mt-1">Please upload your transfer slip so our staff can verify and begin packing your botanicals.</p>
                    </div>

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
                            Submit Transfer Receipt
                        </button>
                    </form>
                </div>
            @elseif($order->payment_status === 'awaiting_verification')
                <div class="p-4 rounded-xl bg-stock-wash border border-stock/30 text-stock text-xs space-y-1">
                    <p class="font-semibold">Transfer Proof Submitted for Verification</p>
                    <p class="text-[11px]">Submitted on {{ $order->payment_submitted_at?->format('M d, Y - H:i') ?? 'recently' }}. Our nursery finance team reviews transfers within 2 business hours.</p>
                </div>
            @endif

            <!-- Cancel Unpaid Action -->
            @if(!$order->isPaid() && !$order->isCancelled())
                <div class="pt-4 border-t border-hairline flex items-center justify-between">
                    <span class="text-xs text-forest-4">No longer need this order?</span>
                    <form method="POST" action="{{ route('order.cancel', ['token' => $order->tracking_token]) }}" onsubmit="return confirm('Are you sure you want to cancel this unpaid order? Your reserved stock will be immediately released.');">
                        @csrf
                        <button type="submit" class="btn btn-ghost !text-xs text-sale hover:bg-sale-wash border-sale/30">
                            Cancel Unpaid Order
                        </button>
                    </form>
                </div>
            @endif

        </div>

    </div>

@endsection
