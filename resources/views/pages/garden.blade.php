@extends('layouts.app')

@section('title', 'My Digital Garden — Plantify Official')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-16">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-forest-3 font-mono mb-6" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-ochre">Home</a>
            <span>/</span>
            <span class="text-forest font-semibold">My Digital Garden</span>
        </nav>

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-stock animate-pulse"></span>
                    <span class="text-xs font-mono font-semibold text-stock uppercase tracking-wider">Living Garden Sanctuary</span>
                </div>
                <h1 class="font-display font-extrabold text-2xl sm:text-4xl text-forest mt-1 tracking-tight">My Digital Garden</h1>
                <p class="text-xs sm:text-sm text-forest-3 mt-1.5 max-w-xl">
                    Every delivered plant is tracked individually per physical unit. Monitor watering cadences, weather deferrals, and estimated CO2 capture.
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('care.index') }}" class="btn btn-ghost text-xs">
                    <svg class="w-4 h-4 text-ochre" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    AI Plant Doctor
                </a>
                <a href="{{ route('shop') }}" class="btn btn-primary text-xs">
                    + Adopt New Plant
                </a>
            </div>
        </div>

        <!-- Weather Deferral Alert Banner (PRD Section 6.2) -->
        @if(!empty($weatherAlert))
            <div class="bg-paper border border-ochre/40 rounded-2xl p-5 mb-8 shadow-sm flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-ochre/15 text-ochre flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-ochre-deep" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <h3 class="font-display font-bold text-sm text-forest">Weather-Aware Watering Deferral Active</h3>
                        <span class="badge badge-new text-[10px]">{{ $weatherAlert['city'] }}</span>
                    </div>
                    <p class="text-xs text-forest-2 leading-relaxed">
                        {{ $weatherAlert['reason'] }} Current condition: <strong class="capitalize">{{ $weatherAlert['weather_desc'] }}</strong> (Humidity: {{ $weatherAlert['humidity'] }}%). Outdoor plant watering reminders have been automatically deferred by 1 day to prevent root rot.
                    </p>
                </div>
            </div>
        @endif

        <!-- Stats Overview Bar -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <!-- Card 1: Total Plants -->
            <div class="bg-paper border border-hairline rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-forest-3">Active Living Plants</p>
                    <p class="font-display font-extrabold text-2xl sm:text-3xl text-forest mt-1">
                        {{ $totalPlants }} <span class="text-xs font-sans font-normal text-forest-4">specimen(s)</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-stock-wash text-stock flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 21V9" />
                        <path d="M12 13c0-3.3 2.7-6 6-6 0 3.3-2.7 6-6 6Z" />
                        <path d="M12 16c0-2.8-2.2-5-5-5 0 2.8 2.2 5 5 5Z" />
                    </svg>
                </div>
            </div>

            <!-- Card 2: Watering Due Today -->
            <div class="bg-paper border border-hairline rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-forest-3">Watering Due Today</p>
                    <p class="font-display font-extrabold text-2xl sm:text-3xl {{ $wateringDueCount > 0 ? 'text-sale' : 'text-forest' }} mt-1">
                        {{ $wateringDueCount }} <span class="text-xs font-sans font-normal text-forest-4">needing hydration</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl {{ $wateringDueCount > 0 ? 'bg-sale-wash text-sale' : 'bg-glass text-forest-3' }} flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21s-6-5.686-6-10A6 6 0 0118 11c0 4.314-6 10-6 10z" />
                    </svg>
                </div>
            </div>

            <!-- Card 3: CO2 Absorption -->
            <div class="bg-paper border border-hairline rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-forest-3">Total Estimated CO2 Offset</p>
                    <p class="font-display font-extrabold text-2xl sm:text-3xl text-stock mt-1">
                        {{ number_format($totalCo2Absorbed, 1) }} <span class="text-xs font-sans font-normal text-forest-4">kg/year</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-stock-wash text-stock flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Garden Plant Unit Cards -->
        @if($gardens->isEmpty())
            <div class="bg-paper border border-hairline rounded-3xl p-12 text-center max-w-xl mx-auto shadow-sm">
                <div class="w-16 h-16 rounded-full bg-glass flex items-center justify-center text-forest-3 mx-auto mb-4">
                    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 21V9" />
                        <path d="M12 13c0-3.3 2.7-6 6-6 0 3.3-2.7 6-6 6Z" />
                        <path d="M12 16c0-2.8-2.2-5-5-5 0 2.8 2.2 5 5 5Z" />
                    </svg>
                </div>
                <h3 class="font-display font-bold text-xl text-forest">Your Garden Is Waiting for Its First Plant</h3>
                <p class="text-xs text-forest-3 mt-2 leading-relaxed">
                    Once you purchase a plant or claim a botanical gift, it will be automatically added here with individual watering schedules and smart care tracking.
                </p>
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('shop') }}" class="btn btn-primary text-xs">
                        Browse Available Plants &rarr;
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($gardens as $garden)
                    @php
                        $plant = $garden->plant;
                        $isDue = $garden->isWateringDue();
                    @endphp
                    <div class="bg-paper border {{ $isDue ? 'border-sale/40 shadow-md' : 'border-hairline shadow-sm' }} rounded-2xl p-5 flex flex-col justify-between" x-data="{ editing: false }">
                        <div>
                            <!-- Header & Location Badge -->
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div>
                                    <span class="badge {{ $garden->isOutdoor() ? 'badge-new' : 'badge-stock' }} text-[10px]">
                                        {{ ucfirst($garden->location_type) }} &bull; Unit #{{ $garden->unit_number }}
                                    </span>
                                    <h3 class="font-display font-bold text-base text-forest mt-1.5">
                                        {{ $garden->nickname ?? $plant->name }}
                                    </h3>
                                    <p class="text-xs text-forest-4 italic font-mono">{{ $plant->name }} ({{ $plant->botanical_name }})</p>
                                </div>
                                <div class="w-14 h-14 rounded-xl overflow-hidden bg-glass flex-shrink-0 border border-hairline">
                                    <img src="{{ $plant->primary_image_url }}" alt="{{ $plant->name }}" class="w-full h-full object-cover" />
                                </div>
                            </div>

                            <!-- Care Dates Strip -->
                            <div class="space-y-2 py-3 border-t border-b border-hairline text-xs">
                                <div class="flex justify-between items-center">
                                    <span class="text-forest-3 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-stock" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21s-6-5.686-6-10A6 6 0 0118 11c0 4.314-6 10-6 10z" />
                                        </svg>
                                        Next Watering:
                                    </span>
                                    <span class="font-mono font-semibold {{ $isDue ? 'text-sale' : 'text-forest' }}">
                                        @if($garden->next_watering_at)
                                            {{ $garden->next_watering_at->format('M d, Y') }}
                                            ({{ $garden->next_watering_at->diffForHumans() }})
                                        @else
                                            Not scheduled
                                        @endif
                                    </span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-forest-3 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-ochre" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12 6 12 12 16 14" />
                                        </svg>
                                        Last Watered:
                                    </span>
                                    <span class="font-mono text-forest-3">
                                        {{ $garden->last_watered_at ? $garden->last_watered_at->format('M d, Y') : 'Never' }}
                                    </span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-forest-3 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-ochre-deep" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        CO2 Offset:
                                    </span>
                                    <span class="font-mono text-stock font-semibold">
                                        {{ $plant->co2_absorption_rate ?? 0.8 }} kg/yr
                                    </span>
                                </div>
                            </div>

                            <!-- Notes Preview -->
                            @if(!empty($garden->notes))
                                <p class="text-[11px] text-forest-3 italic mt-2.5">
                                    &ldquo;{{ $garden->notes }}&rdquo;
                                </p>
                            @endif
                        </div>

                        <!-- Action Controls -->
                        <div class="mt-4 pt-3 border-t border-hairline space-y-2">
                            <div class="flex items-center gap-2">
                                <!-- Water Button -->
                                <form method="POST" action="{{ route('garden.water', ['id' => $garden->id]) }}" class="flex-grow">
                                    @csrf
                                    <button type="submit" class="btn {{ $isDue ? 'btn-ochre' : 'btn-primary' }} w-full text-xs !py-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21s-6-5.686-6-10A6 6 0 0118 11c0 4.314-6 10-6 10z" />
                                        </svg>
                                        Water Plant
                                    </button>
                                </form>

                                <!-- Fertilize Button -->
                                <form method="POST" action="{{ route('garden.fertilize', ['id' => $garden->id]) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-ghost text-xs !py-1.5 !px-3" title="Record Fertilizing">
                                        Fertilize
                                    </button>
                                </form>

                                <!-- Edit Details Toggle -->
                                <button type="button" @click="editing = !editing" class="btn-icon !w-8 !h-8 text-forest-3 hover:text-forest" title="Edit Plant Details">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Inline Edit Form Drawer/Box -->
                            <div x-show="editing" x-cloak class="p-3 bg-glass rounded-xl border border-hairline space-y-3 text-xs mt-3">
                                <form method="POST" action="{{ route('garden.update', ['id' => $garden->id]) }}" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-[11px] font-mono uppercase text-forest-3 mb-1">Nickname</label>
                                        <input type="text" name="nickname" value="{{ $garden->nickname }}" class="w-full text-xs h-8 px-2 bg-paper border border-hairline rounded-lg text-forest" />
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-mono uppercase text-forest-3 mb-1">Location</label>
                                        <select name="location_type" class="w-full text-xs h-8 px-2 bg-paper border border-hairline rounded-lg text-forest">
                                            <option value="indoor" {{ $garden->location_type === 'indoor' ? 'selected' : '' }}>Indoor (Protected)</option>
                                            <option value="outdoor" {{ $garden->location_type === 'outdoor' ? 'selected' : '' }}>Outdoor (Weather-Aware)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-mono uppercase text-forest-3 mb-1">Care Notes</label>
                                        <textarea name="notes" rows="2" class="w-full text-xs p-2 bg-paper border border-hairline rounded-lg text-forest">{{ $garden->notes }}</textarea>
                                    </div>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="editing = false" class="btn btn-ghost !text-xs !py-1 !px-2">Cancel</button>
                                        <button type="submit" class="btn btn-primary !text-xs !py-1 !px-3">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>

@endsection
