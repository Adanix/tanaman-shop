@extends('admin.layout')

@section('title', 'Order ' . $order->order_number . ' — Admin Plantify')

@section('admin_content')

    <div class="space-y-6">
        
        <!-- Header Strip -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-paper border border-hairline rounded-2xl p-6 shadow-sm">
            <div>
                <a href="{{ route('admin.orders') }}" class="text-xs font-mono text-ochre hover:underline mb-1 inline-block">
                    &larr; Back to Orders List
                </a>
                <h2 class="font-display font-extrabold text-2xl text-forest">{{ $order->order_number }}</h2>
                <p class="text-xs text-forest-3 mt-0.5">Placed: {{ $order->created_at->format('M d, Y - H:i') }} &bull; Tracking: <span class="font-mono">{{ $order->tracking_token }}</span></p>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <span class="badge {{ $order->isPaid() ? 'badge-stock' : 'badge-low' }}">
                    Payment: {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                </span>
                <span class="badge badge-stock">
                    Order: {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left Info (col-span-8) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Line items -->
                <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="font-display font-bold text-base text-forest pb-3 border-b border-hairline">
                        Purchased Botanical Items
                    </h3>
                    <div class="divide-y divide-hairline">
                        @foreach($order->items as $item)
                            <div class="py-3 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-glass overflow-hidden flex-shrink-0 border border-hairline">
                                        <img src="{{ $item->plant->primary_image_url ?? '' }}" alt="{{ $item->plant_name }}" class="w-full h-full object-cover" />
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-forest">{{ $item->plant_name }}</h4>
                                        <p class="text-forest-4 font-mono">{{ $item->quantity }}x @ ${{ number_format($item->unit_price, 2) }}</p>
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
                            <span>Shipping Fee</span>
                            <span class="font-mono">${{ number_format($order->shipping_fee, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Sales Tax</span>
                            <span class="font-mono">${{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-sm text-forest pt-2 border-t border-hairline">
                            <span>Total Amount</span>
                            <span class="font-mono text-base">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Audit Logs -->
                <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="font-display font-bold text-base text-forest pb-3 border-b border-hairline">
                        System Audit Trail
                    </h3>
                    <div class="space-y-3">
                        @forelse($auditLogs as $log)
                            <div class="p-3 rounded-xl bg-glass border border-hairline text-xs space-y-1">
                                <div class="flex items-center justify-between text-[11px] font-mono">
                                    <span class="font-bold uppercase text-forest">{{ $log->action }}</span>
                                    <span class="text-forest-4">{{ $log->created_at->format('M d, Y - H:i:s') }}</span>
                                </div>
                                <p class="text-forest-2">{{ $log->description }}</p>
                                @if($log->user)
                                    <p class="text-[10px] text-forest-4">Actor: {{ $log->user->name }} ({{ $log->user->role }})</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-xs text-forest-3 font-mono">No audit events recorded for this order.</p>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Right Actions & Meta (col-span-4) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Customer & Fulfillment Card -->
                <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-4 text-xs">
                    <h3 class="font-display font-bold text-sm text-forest pb-2 border-b border-hairline">Customer Details</h3>
                    <div class="space-y-1.5">
                        <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                        <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                        <p><strong>Phone:</strong> {{ $order->customer_phone ?? 'N/A' }}</p>
                    </div>

                    <h3 class="font-display font-bold text-sm text-forest pt-3 border-t border-hairline pb-2">Fulfillment Details</h3>
                    <div class="space-y-1.5">
                        <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $order->fulfillment_type)) }}</p>
                        @if($order->isStorePickup())
                            <p><strong>Pickup Code:</strong> <span class="font-mono font-bold text-ochre text-sm">{{ $order->pickup_code }}</span></p>
                        @else
                            <p><strong>Address:</strong> {{ $order->shipping_address }}, {{ $order->shipping_city }} {{ $order->shipping_postal_code }}</p>
                            <p><strong>Carrier:</strong> {{ $order->carrier ?? 'Unassigned' }}</p>
                            <p><strong>Tracking No:</strong> {{ $order->tracking_number ?? 'Pending' }}</p>
                        @endif
                    </div>

                    <h3 class="font-display font-bold text-sm text-forest pt-3 border-t border-hairline pb-2">Payment Details</h3>
                    <div class="space-y-1.5">
                        <p><strong>Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                        @if($order->payment_method === 'online_stripe')
                            <p class="truncate font-mono"><strong>Stripe Session:</strong> {{ $order->stripe_session_id ?? 'N/A' }}</p>
                        @endif
                        @if($order->payment_proof_path && $proofUrl)
                            <div class="pt-2">
                                <a href="{{ $proofUrl }}" target="_blank" class="btn btn-primary text-xs w-full">
                                    View Uploaded Payment Proof &rarr;
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Refund Action -->
                @if($order->isPaid() && $order->order_status !== 'cancelled')
                    <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-3" x-data="{ openRefund: false }">
                        <h3 class="font-display font-bold text-sm text-forest">Process Refund</h3>
                        <p class="text-xs text-forest-3">Issue a full refund and cancel the order. Stock will be restored.</p>
                        
                        <button type="button" @click="openRefund = !openRefund" class="btn btn-ghost !text-xs text-sale border-sale/30 hover:bg-sale-wash w-full">
                            Initiate Refund Protocol
                        </button>

                        <div x-show="openRefund" x-cloak class="pt-3 border-t border-hairline space-y-3">
                            <form method="POST" action="{{ route('admin.refund', ['id' => $order->id]) }}" onsubmit="return confirm('Confirm full refund for order {{ $order->order_number }}?');">
                                @csrf
                                <label class="block text-[11px] font-mono uppercase text-forest-3 mb-1">Refund Reason *</label>
                                <textarea name="reason" required minlength="10" rows="3" class="w-full text-xs p-2.5 bg-glass border border-hairline rounded-lg text-forest" placeholder="e.g. Customer requested cancellation due to travel..."></textarea>
                                <button type="submit" class="btn btn-primary bg-sale hover:bg-sale text-xs w-full mt-2">
                                    Confirm Full Refund (${{ number_format($order->total_amount, 2) }})
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

            </div>

        </div>

    </div>

@endsection
