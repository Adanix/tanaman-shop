@extends('admin.layout')

@section('title', 'Admin Dashboard — Plantify Official')

@section('admin_content')

    <!-- Metrics Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        
        <div class="bg-paper border border-hairline rounded-2xl p-4 shadow-sm">
            <span class="text-[11px] font-mono text-forest-4 uppercase">Paid Revenue</span>
            <p class="font-display font-extrabold text-xl sm:text-2xl text-forest mt-1">
                ${{ number_format($totalRevenue, 2) }}
            </p>
        </div>

        <div class="bg-paper border border-hairline rounded-2xl p-4 shadow-sm">
            <span class="text-[11px] font-mono text-forest-4 uppercase">Total Orders</span>
            <p class="font-display font-extrabold text-xl sm:text-2xl text-forest mt-1">
                {{ $totalOrders }}
            </p>
        </div>

        <div class="bg-paper border border-hairline rounded-2xl p-4 shadow-sm">
            <span class="text-[11px] font-mono text-forest-4 uppercase">Pending Proofs</span>
            <p class="font-display font-extrabold text-xl sm:text-2xl {{ $pendingVerifications > 0 ? 'text-ochre' : 'text-forest' }} mt-1">
                {{ $pendingVerifications }}
            </p>
        </div>

        <div class="bg-paper border border-hairline rounded-2xl p-4 shadow-sm">
            <span class="text-[11px] font-mono text-forest-4 uppercase">Ready Pickups</span>
            <p class="font-display font-extrabold text-xl sm:text-2xl text-forest mt-1">
                {{ $pendingPickups }}
            </p>
        </div>

        <div class="bg-paper border border-hairline rounded-2xl p-4 shadow-sm">
            <span class="text-[11px] font-mono text-forest-4 uppercase">To Ship</span>
            <p class="font-display font-extrabold text-xl sm:text-2xl text-forest mt-1">
                {{ $pendingDeliveries }}
            </p>
        </div>

        <div class="bg-paper border border-hairline rounded-2xl p-4 shadow-sm">
            <span class="text-[11px] font-mono text-forest-4 uppercase">Low Stock</span>
            <p class="font-display font-extrabold text-xl sm:text-2xl {{ $lowStockCount > 0 ? 'text-sale' : 'text-forest' }} mt-1">
                {{ $lowStockCount }}
            </p>
        </div>

    </div>

    <!-- Recent Orders Table -->
    <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-hairline">
            <h2 class="font-display font-bold text-base text-forest">Recent Customer Orders</h2>
            <a href="{{ route('admin.orders') }}" class="text-xs text-ochre hover:underline font-semibold font-mono">
                View All Orders &rarr;
            </a>
        </div>

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
                        <th class="py-2.5 px-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-glass/50 transition-colors">
                            <td class="py-3 px-3 font-mono font-bold text-forest">
                                <a href="{{ route('admin.orders.detail', ['id' => $order->id]) }}" class="hover:text-ochre">
                                    {{ $order->order_number }}
                                </a>
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
                                <span class="badge {{ $order->isPaid() ? 'badge-stock' : 'badge-low' }} text-[10px]">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                </span>
                            </td>
                            <td class="py-3 px-3">
                                <span class="badge badge-stock text-[10px]">
                                    {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-right font-mono font-bold text-forest">
                                ${{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="py-3 px-3 text-right">
                                <a href="{{ route('admin.orders.detail', ['id' => $order->id]) }}" class="btn btn-ghost !text-xs !py-1 !px-2.5">
                                    Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-forest-3 font-mono text-xs">
                                No customer orders recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
