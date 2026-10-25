@extends('admin.layout')

@section('title', 'Store Pickup Desk — Plantify Official')

@section('admin_content')

    <div class="space-y-6">
        
        <!-- POS Search Bar -->
        <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-4">
            <div>
                <h2 class="font-display font-bold text-lg text-forest">Store Pickup POS Verification Desk</h2>
                <p class="text-xs text-forest-3 mt-0.5">Scan or type customer's 6-character Pickup Code or Order Number to verify and handover</p>
            </div>

            <form method="GET" action="{{ route('admin.pickup') }}" class="flex gap-3 max-w-xl">
                <input type="text" name="code" value="{{ $search }}" autofocus placeholder="Enter 6-char Pickup Code (e.g. 8K2L9M) or Order #" class="w-full text-sm font-mono h-11 px-4 bg-glass border border-hairline rounded-xl text-forest uppercase tracking-wider focus:outline-none focus:border-ochre font-bold" />
                <button type="submit" class="btn btn-primary text-xs h-11 !px-6">
                    Lookup &rarr;
                </button>
            </form>
        </div>

        <!-- Lookup Result Card (If searched) -->
        @if($search !== '')
            @if($order)
                <div class="bg-forest text-glass rounded-2xl p-6 sm:p-8 shadow-md space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-4 border-b border-forest-2">
                        <div>
                            <span class="badge badge-new font-mono text-[10px]">Pickup Code Match</span>
                            <h3 class="font-display font-extrabold text-2xl text-glass mt-1">
                                {{ $order->order_number }} &bull; {{ $order->customer_name }}
                            </h3>
                            <p class="text-xs text-glass/70 font-mono mt-0.5">Pickup Code: <strong class="text-ochre tracking-widest text-base">{{ $order->pickup_code }}</strong></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="badge {{ $order->isPaid() ? 'badge-stock' : 'badge-low' }}">
                                Payment: {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                            </span>
                            <span class="badge badge-stock">
                                Order: {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Items Checklist -->
                    <div class="space-y-3">
                        <h4 class="font-mono text-xs uppercase tracking-wider text-glass/80 font-bold">Physical Plants to Handover:</h4>
                        <div class="divide-y divide-forest-2 bg-forest-dark/60 rounded-xl p-4 border border-forest-2">
                            @foreach($order->items as $item)
                                <div class="py-2.5 first:pt-0 last:pb-0 flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-forest overflow-hidden flex-shrink-0">
                                            <img src="{{ $item->plant->primary_image_url ?? '' }}" alt="{{ $item->plant_name }}" class="w-full h-full object-cover" />
                                        </div>
                                        <div>
                                            <p class="font-bold text-glass">{{ $item->plant_name }}</p>
                                            <p class="text-[11px] text-glass/60 italic font-mono">{{ $item->plant->botanical_name ?? '' }}</p>
                                        </div>
                                    </div>
                                    <span class="font-mono font-bold text-ochre text-sm">{{ $item->quantity }}x unit(s)</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Actions Strip -->
                    <div class="pt-4 border-t border-forest-2 flex flex-wrap items-center justify-between gap-4">
                        <div class="text-xs text-glass/70">
                            Total Due: <strong class="text-glass font-mono text-base">${{ number_format($order->total_amount, 2) }}</strong>
                        </div>

                        <div class="flex items-center gap-3">
                            @if($order->order_status === 'processing')
                                <form method="POST" action="{{ route('admin.pickup.ready', ['id' => $order->id]) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-ochre text-xs">
                                        Mark Ready for Pickup
                                    </button>
                                </form>
                            @endif

                            @if(in_array($order->order_status, ['ready_for_pickup', 'processing']))
                                <form method="POST" action="{{ route('admin.pickup.handover', ['id' => $order->id]) }}" onsubmit="return confirm('Confirm customer handover for order {{ $order->order_number }}?');" class="flex items-center gap-2">
                                    @csrf
                                    @if(!$order->isPaid())
                                        <select name="pickup_payment_method" class="text-xs h-9 px-2 bg-paper text-forest rounded-lg font-medium">
                                            <option value="cash">Paid via Cash</option>
                                            <option value="card_pos">Paid via EDC / Card</option>
                                        </select>
                                    @endif
                                    <button type="submit" class="btn btn-primary bg-stock hover:bg-stock text-white text-xs !py-2 !px-4">
                                        Confirm Handover &amp; Complete &rarr;
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="p-6 rounded-2xl bg-paper border border-hairline text-center text-xs text-forest-3">
                    No store pickup order found matching "<strong>{{ $search }}</strong>". Please double check the code.
                </div>
            @endif
        @endif

        <!-- Ready Pickups Queue -->
        <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-4">
            <h3 class="font-display font-bold text-base text-forest pb-3 border-b border-hairline">
                Active Store Pickups Queue
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-hairline text-forest-4 font-mono uppercase text-[10px]">
                            <th class="py-2.5 px-3">Pickup Code</th>
                            <th class="py-2.5 px-3">Order Number</th>
                            <th class="py-2.5 px-3">Customer</th>
                            <th class="py-2.5 px-3">Order Status</th>
                            <th class="py-2.5 px-3">Payment</th>
                            <th class="py-2.5 px-3 text-right">Total</th>
                            <th class="py-2.5 px-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline">
                        @forelse($readyOrders as $o)
                            <tr class="hover:bg-glass/50 transition-colors">
                                <td class="py-3 px-3 font-mono font-extrabold text-ochre text-sm">
                                    {{ $o->pickup_code }}
                                </td>
                                <td class="py-3 px-3 font-mono font-bold text-forest">
                                    {{ $o->order_number }}
                                </td>
                                <td class="py-3 px-3 text-forest">
                                    {{ $o->customer_name }}
                                </td>
                                <td class="py-3 px-3">
                                    <span class="badge {{ $o->order_status === 'ready_for_pickup' ? 'badge-stock' : 'badge-new' }} text-[10px]">
                                        {{ ucfirst(str_replace('_', ' ', $o->order_status)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="badge {{ $o->isPaid() ? 'badge-stock' : 'badge-low' }} text-[10px]">
                                        {{ ucfirst(str_replace('_', ' ', $o->payment_status)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-bold text-forest">
                                    ${{ number_format($o->total_amount, 2) }}
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <a href="{{ route('admin.pickup', ['code' => $o->pickup_code]) }}" class="btn btn-ghost !text-xs !py-1 !px-2.5">
                                        Open POS &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-forest-3 font-mono text-xs">
                                    No orders currently queued for pickup.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection
