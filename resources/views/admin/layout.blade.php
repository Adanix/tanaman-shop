@extends('layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-16">
        
        <!-- Admin Header & Subnavigation Strip -->
        <div class="bg-forest text-glass rounded-2xl p-6 mb-8 shadow-md">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-forest-2">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-ochre text-forest flex items-center justify-center font-bold">
                        <svg class="w-5 h-5 text-forest" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"/>
                            <rect x="14" y="3" width="7" height="7"/>
                            <rect x="14" y="14" width="7" height="7"/>
                            <rect x="3" y="14" width="7" height="7"/>
                        </svg>
                    </span>
                    <div>
                        <h1 class="font-display font-extrabold text-xl text-glass tracking-tight">Plantify Nursery Console</h1>
                        <p class="text-xs text-glass/70 font-mono">Store Administration &bull; Physical POS &bull; Fulfillment Operations</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('home') }}" class="btn btn-ghost !text-glass border-glass/20 hover:border-glass text-xs !py-1.5 !px-3">
                        &larr; View Storefront
                    </a>
                </div>
            </div>

            <!-- Admin Navigation Tabs -->
            <nav class="flex items-center gap-2 overflow-x-auto pt-4 no-scrollbar text-xs font-medium">
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 rounded-xl transition-colors whitespace-nowrap {{ request()->routeIs('admin.dashboard') ? 'bg-ochre text-forest font-bold' : 'text-glass/80 hover:bg-forest-2 hover:text-white' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.orders') }}" class="px-3.5 py-2 rounded-xl transition-colors whitespace-nowrap {{ request()->routeIs('admin.orders*') ? 'bg-ochre text-forest font-bold' : 'text-glass/80 hover:bg-forest-2 hover:text-white' }}">
                    Orders
                </a>
                <a href="{{ route('admin.verifications') }}" class="px-3.5 py-2 rounded-xl transition-colors whitespace-nowrap flex items-center gap-1.5 {{ request()->routeIs('admin.verifications*') ? 'bg-ochre text-forest font-bold' : 'text-glass/80 hover:bg-forest-2 hover:text-white' }}">
                    <span>Bank Verifications</span>
                    @php
                        $pendingCount = \App\Models\Order::where('payment_status', 'awaiting_verification')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="w-4 h-4 rounded-full bg-sale text-white text-[10px] font-mono flex items-center justify-center font-bold">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.pickup') }}" class="px-3.5 py-2 rounded-xl transition-colors whitespace-nowrap {{ request()->routeIs('admin.pickup*') ? 'bg-ochre text-forest font-bold' : 'text-glass/80 hover:bg-forest-2 hover:text-white' }}">
                    Store Pickup POS
                </a>
                <a href="{{ route('admin.delivery') }}" class="px-3.5 py-2 rounded-xl transition-colors whitespace-nowrap {{ request()->routeIs('admin.delivery*') ? 'bg-ochre text-forest font-bold' : 'text-glass/80 hover:bg-forest-2 hover:text-white' }}">
                    Delivery Dispatch
                </a>
                <a href="{{ route('admin.plants') }}" class="px-3.5 py-2 rounded-xl transition-colors whitespace-nowrap {{ request()->routeIs('admin.plants*') ? 'bg-ochre text-forest font-bold' : 'text-glass/80 hover:bg-forest-2 hover:text-white' }}">
                    Plant Catalog
                </a>
                <a href="{{ route('admin.settings') }}" class="px-3.5 py-2 rounded-xl transition-colors whitespace-nowrap {{ request()->routeIs('admin.settings*') ? 'bg-ochre text-forest font-bold' : 'text-glass/80 hover:bg-forest-2 hover:text-white' }}">
                    Store Settings
                </a>
            </nav>
        </div>

        <!-- Subpage Content -->
        @yield('admin_content')

    </div>

@endsection
