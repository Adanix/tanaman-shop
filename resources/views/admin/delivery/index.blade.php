@extends('admin.layout')

@section('title', 'Delivery Dispatch — Plantify Official')

@section('admin_content')

    <div class="space-y-8">
        
        <!-- Processing Orders Awaiting Dispatch -->
        <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-hairline">
                <div>
                    <h2 class="font-display font-bold text-base text-forest">Orders Awaiting Climate Dispatch</h2>
                    <p class="text-xs text-forest-3 mt-0.5">Paid delivery orders ready to be packaged and handed to courier</p>
                </div>
                <span class="badge badge-stock text-xs">{{ $processingOrders->count() }} Ready to Pack</span>
            </div>

            @if($processingOrders->isEmpty())
                <p class="py-6 text-center text-xs text-forest-3 font-mono">No delivery orders currently awaiting shipment dispatch.</p>
            @else
                <div class="space-y-4">
                    @foreach($processingOrders as $order)
                        <div class="p-4 rounded-xl bg-glass border border-hairline flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <span class="font-mono font-bold text-forest text-sm">
                                    <a href="{{ route('admin.orders.detail', ['id' => $order->id]) }}" class="hover:text-ochre">
                                        {{ $order->order_number }}
                                    </a>
                                </span>
                                <p class="text-xs text-forest-2">
                                    <strong>{{ $order->customer_name }}</strong> &bull; {{ $order->shipping_address }}, {{ $order->shipping_city }}
                                </p>
                                <p class="text-[11px] text-forest-4 font-mono">
                                    {{ $order->items->count() }} botanical item(s) &bull; ${{ number_format($order->total_amount, 2) }}
                                </p>
                            </div>

                            <!-- Ship Action Form -->
                            <form method="POST" action="{{ route('admin.delivery.ship', ['id' => $order->id]) }}" class="flex flex-wrap items-center gap-2">
                                @csrf
                                <input type="text" name="carrier" placeholder="Carrier (e.g. FedEx / JNE)" class="text-xs h-9 px-3 bg-paper border border-hairline rounded-lg text-forest" />
                                <input type="text" name="tracking_number" placeholder="Tracking #" class="text-xs h-9 px-3 bg-paper border border-hairline rounded-lg text-forest font-mono" />
                                <button type="submit" class="btn btn-ochre text-xs h-9 !px-4">
                                    Dispatch &rarr;
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Shipped Orders In Transit -->
        <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-hairline">
                <div>
                    <h2 class="font-display font-bold text-base text-forest">Dispatched &amp; In-Transit Orders</h2>
                    <p class="text-xs text-forest-3 mt-0.5">Confirming delivery immediately adpots physical plant units into the customer's Digital Garden</p>
                </div>
                <span class="badge badge-new text-xs">{{ $shippedOrders->count() }} In Transit</span>
            </div>

            @if($shippedOrders->isEmpty())
                <p class="py-6 text-center text-xs text-forest-3 font-mono">No dispatched orders currently in transit.</p>
            @else
                <div class="space-y-4">
                    @foreach($shippedOrders as $order)
                        <div class="p-4 rounded-xl bg-glass border border-hairline flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <span class="font-mono font-bold text-forest text-sm">
                                    <a href="{{ route('admin.orders.detail', ['id' => $order->id]) }}" class="hover:text-ochre">
                                        {{ $order->order_number }}
                                    </a>
                                </span>
                                <p class="text-xs text-forest-2">
                                    <strong>{{ $order->customer_name }}</strong> &bull; Carrier: <strong>{{ $order->carrier ?? 'Standard Express' }}</strong> (No: <span class="font-mono">{{ $order->tracking_number ?? 'N/A' }}</span>)
                                </p>
                                <p class="text-[11px] text-forest-4 font-mono">
                                    Dispatched: {{ $order->shipped_at?->format('M d, H:i') ?? 'Recently' }}
                                </p>
                            </div>

                            <!-- Deliver Confirmation Form -->
                            <form method="POST" action="{{ route('admin.delivery.deliver', ['id' => $order->id]) }}" onsubmit="return confirm('Confirm safe delivery for order {{ $order->order_number }}? This will activate Digital Garden entries for the customer.');">
                                @csrf
                                <button type="submit" class="btn btn-primary bg-stock hover:bg-stock text-white text-xs h-9 !px-4">
                                    Confirm Safe Delivery &rarr;
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

@endsection
