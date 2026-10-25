@extends('admin.layout')

@section('title', 'Manage Orders — Plantify Official')

@section('admin_content')

    <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-hairline">
            <div>
                <h2 class="font-display font-bold text-lg text-forest">Order Management</h2>
                <p class="text-xs text-forest-3 mt-0.5">Filter, search, inspect transactions, and process state changes</p>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.orders') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 p-4 bg-glass rounded-xl border border-hairline">
            <div>
                <label class="block text-[11px] font-mono uppercase text-forest-3 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Order #, name, email, code..." class="w-full text-xs h-9 px-2.5 bg-paper border border-hairline rounded-lg text-forest" />
            </div>

            <div>
                <label class="block text-[11px] font-mono uppercase text-forest-3 mb-1">Payment Status</label>
                <select name="payment_status" class="w-full text-xs h-9 px-2 bg-paper border border-hairline rounded-lg text-forest">
                    <option value="">All Payments</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="awaiting_verification" {{ request('payment_status') === 'awaiting_verification' ? 'selected' : '' }}>Awaiting Verification</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-mono uppercase text-forest-3 mb-1">Order Status</label>
                <select name="order_status" class="w-full text-xs h-9 px-2 bg-paper border border-hairline rounded-lg text-forest">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('order_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('order_status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="ready_for_pickup" {{ request('order_status') === 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                    <option value="shipped" {{ request('order_status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('order_status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="completed" {{ request('order_status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('order_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-mono uppercase text-forest-3 mb-1">Fulfillment</label>
                <select name="fulfillment_type" class="w-full text-xs h-9 px-2 bg-paper border border-hairline rounded-lg text-forest">
                    <option value="">All Types</option>
                    <option value="delivery" {{ request('fulfillment_type') === 'delivery' ? 'selected' : '' }}>Delivery</option>
                    <option value="store_pickup" {{ request('fulfillment_type') === 'store_pickup' ? 'selected' : '' }}>Store Pickup</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary h-9 !px-4 text-xs flex-grow">Filter</button>
                @if(request()->hasAny(['search', 'payment_status', 'order_status', 'fulfillment_type']))
                    <a href="{{ route('admin.orders') }}" class="btn btn-ghost h-9 !px-3 text-xs">Reset</a>
                @endif
            </div>
        </form>

        <!-- Orders Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-hairline text-forest-4 font-mono uppercase text-[10px]">
                        <th class="py-2.5 px-3">Order Number</th>
                        <th class="py-2.5 px-3">Date</th>
                        <th class="py-2.5 px-3">Customer</th>
                        <th class="py-2.5 px-3">Fulfillment</th>
                        <th class="py-2.5 px-3">Payment</th>
                        <th class="py-2.5 px-3">Status</th>
                        <th class="py-2.5 px-3 text-right">Total</th>
                        <th class="py-2.5 px-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse($orders as $order)
                        <tr class="hover:bg-glass/50 transition-colors">
                            <td class="py-3 px-3 font-mono font-bold text-forest">
                                <a href="{{ route('admin.orders.detail', ['id' => $order->id]) }}" class="hover:text-ochre">
                                    {{ $order->order_number }}
                                </a>
                                @if($order->isStorePickup())
                                    <span class="block text-[10px] text-forest-4 font-normal">Code: {{ $order->pickup_code }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-3 font-mono text-forest-3">
                                {{ $order->created_at->format('M d, H:i') }}
                            </td>
                            <td class="py-3 px-3 text-forest">
                                <p class="font-medium truncate max-w-[140px]">{{ $order->customer_name }}</p>
                                <p class="text-[11px] text-forest-4 font-mono truncate max-w-[140px]">{{ $order->customer_email }}</p>
                            </td>
                            <td class="py-3 px-3">
                                <span class="badge {{ $order->isStorePickup() ? 'badge-new' : 'badge-stock' }} text-[10px]">
                                    {{ ucfirst(str_replace('_', ' ', $order->fulfillment_type)) }}
                                </span>
                            </td>
                            <td class="py-3 px-3">
                                <span class="badge {{ $order->isPaid() ? 'badge-stock' : ($order->payment_status === 'awaiting_verification' ? 'badge-low' : 'badge-sale') }} text-[10px]">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                </span>
                            </td>
                            <td class="py-3 px-3">
                                <span class="badge {{ in_array($order->order_status, ['delivered', 'completed']) ? 'badge-stock' : 'badge-new' }} text-[10px]">
                                    {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold text-forest">
                                ${{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="py-3 px-3 text-right">
                                <a href="{{ route('admin.orders.detail', ['id' => $order->id]) }}" class="btn btn-ghost !text-xs !py-1 !px-2.5">
                                    View Detail &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center text-forest-3 font-mono text-xs">
                                No orders matching the current filter criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 border-t border-hairline">
            {{ $orders->links() }}
        </div>

    </div>

@endsection
