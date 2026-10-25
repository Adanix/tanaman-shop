@extends('layouts.app')

@section('title', $plant->name . ' — Plantify Official')
@section('meta_description', 'Discover ' . $plant->name . ' (' . $plant->botanical_name . '). Acclimated nursery houseplant with digital care tracking and plant doctor support.')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-16">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-forest-3 font-mono mb-6" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-ochre">Home</a>
            <span>/</span>
            <a href="{{ route('shop') }}" class="hover:text-ochre">Living Catalog</a>
            <span>/</span>
            <span class="text-forest font-semibold">{{ $plant->name }}</span>
        </nav>

        <!-- Product Hero: 2-Column Responsive -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 xl:gap-14 items-start">
            
            <!-- Left: Gallery -->
            <div class="space-y-4 lg:sticky lg:top-24" x-data="{ activeImage: '{{ $plant->primary_image_url }}' }">
                <div class="w-full aspect-[4/5] rounded-3xl overflow-hidden bg-glass border border-hairline shadow-sm relative">
                    <img :src="activeImage" alt="{{ $plant->name }}" class="w-full h-full object-cover transition-all duration-300" />
                    
                    <div class="absolute top-4 left-4 flex flex-col gap-2">
                        @if($plant->stock <= 5 && $plant->stock > 0)
                            <span class="badge badge-low">Only {{ $plant->stock }} available</span>
                        @elseif($plant->stock <= 0)
                            <span class="badge badge-sale">Currently Out of Stock</span>
                        @else
                            <span class="badge badge-stock">Nursery Acclimated</span>
                        @endif

                        @if($plant->is_pet_friendly)
                            <span class="badge badge-stock">ASPCA Pet-Safe</span>
                        @endif
                    </div>
                </div>

                @if($plant->images && $plant->images->count() > 1)
                    <div class="grid grid-cols-4 gap-3">
                        @foreach($plant->images as $img)
                            <button type="button" @click="activeImage = '{{ $img->image_url }}'" class="aspect-square rounded-xl overflow-hidden border border-hairline hover:border-ochre bg-glass p-0.5 cursor-pointer focus:outline-none transition-colors">
                                <img src="{{ $img->image_url }}" alt="{{ $plant->name }} thumbnail" class="w-full h-full object-cover rounded-lg" />
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right: Product Details & Buying Section -->
            <div class="space-y-6">
                <!-- Category & Badges -->
                <div class="flex items-center gap-3">
                    <span class="text-xs font-mono uppercase tracking-wider text-ochre font-semibold">
                        {{ $plant->category->name ?? 'Houseplant' }}
                    </span>
                    <span class="text-hairline-soft">&bull;</span>
                    <span class="text-xs font-mono text-forest-3 capitalize">
                        Care: {{ $plant->difficulty_level }}
                    </span>
                </div>

                <!-- Plant Name & Botanical Identity -->
                <div>
                    <h1 class="font-display font-extrabold text-3xl sm:text-4xl text-forest tracking-tight">
                        {{ $plant->name }}
                    </h1>
                    <p class="text-sm text-forest-4 italic font-serif mt-1">
                        {{ $plant->botanical_name }}
                    </p>
                </div>

                <!-- Price -->
                <div class="flex items-baseline gap-3 pt-1 border-t border-hairline">
                    <span class="font-mono font-bold text-3xl text-forest">
                        ${{ number_format($plant->price, 2) }}
                    </span>
                    <span class="text-xs font-mono text-forest-3">
                        Tax &amp; shipping calculated at checkout
                    </span>
                </div>

                <!-- Description -->
                <div class="text-sm text-forest-2 leading-relaxed">
                    {{ $plant->description }}
                </div>

                <!-- Care Parameters Summary Strip -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-4 rounded-2xl bg-paper border border-hairline">
                    <div class="space-y-1">
                        <div class="flex items-center gap-1.5 text-xs text-forest-3">
                            <svg class="w-4 h-4 text-ochre" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <circle cx="12" cy="12" r="5" />
                                <line x1="12" y1="1" x2="12" y2="3" />
                                <line x1="12" y1="21" x2="12" y2="23" />
                                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                                <line x1="1" y1="12" x2="3" y2="12" />
                                <line x1="21" y1="12" x2="23" y2="12" />
                            </svg>
                            <span class="font-mono text-[11px] uppercase">Light</span>
                        </div>
                        <p class="font-semibold text-xs text-forest capitalize">{{ $plant->light_requirement }}</p>
                    </div>

                    <div class="space-y-1">
                        <div class="flex items-center gap-1.5 text-xs text-forest-3">
                            <svg class="w-4 h-4 text-stock" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21s-6-5.686-6-10A6 6 0 0118 11c0 4.314-6 10-6 10z" />
                            </svg>
                            <span class="font-mono text-[11px] uppercase">Watering</span>
                        </div>
                        <p class="font-semibold text-xs text-forest">Every {{ $plant->watering_frequency_days }} days</p>
                    </div>

                    <div class="space-y-1">
                        <div class="flex items-center gap-1.5 text-xs text-forest-3">
                            <svg class="w-4 h-4 text-forest-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                            </svg>
                            <span class="font-mono text-[11px] uppercase">Humidity</span>
                        </div>
                        <p class="font-semibold text-xs text-forest capitalize">{{ $plant->humidity_requirement ?? 'Moderate' }}</p>
                    </div>

                    <div class="space-y-1">
                        <div class="flex items-center gap-1.5 text-xs text-forest-3">
                            <svg class="w-4 h-4 text-stock" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span class="font-mono text-[11px] uppercase">CO2 Offset</span>
                        </div>
                        <p class="font-semibold text-xs text-forest">{{ $plant->co2_absorption_rate ?? 0.8 }} kg/yr</p>
                    </div>
                </div>

                <!-- Add to Cart Form -->
                @if($plant->stock > 0)
                    <form method="POST" action="{{ route('cart.add') }}" class="pt-2 space-y-4" x-data="{ qty: 1 }">
                        @csrf
                        <input type="hidden" name="plant_id" value="{{ $plant->id }}" />
                        
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                            <!-- Stepper -->
                            <div class="flex items-center justify-between border border-hairline rounded-full bg-paper px-3 py-1.5 h-12 w-32 flex-shrink-0">
                                <button type="button" @click="if(qty > 1) qty--" class="w-8 h-8 rounded-full hover:bg-glass flex items-center justify-center text-forest text-lg font-bold" aria-label="Decrease quantity">
                                    &minus;
                                </button>
                                <input type="number" name="quantity" x-model="qty" min="1" max="{{ $plant->stock }}" class="w-10 text-center font-mono font-bold text-sm bg-transparent focus:outline-none" aria-label="Quantity" />
                                <button type="button" @click="if(qty < {{ $plant->stock }}) qty++" class="w-8 h-8 rounded-full hover:bg-glass flex items-center justify-center text-forest text-lg font-bold" aria-label="Increase quantity">
                                    &plus;
                                </button>
                            </div>

                            <!-- Add to Cart Button -->
                            <button type="submit" name="action" value="add_to_cart" class="btn btn-ghost border border-hairline hover:border-forest h-12 px-4 text-xs font-semibold flex-1 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-forest-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="8" cy="21" r="1" />
                                    <circle cx="19" cy="21" r="1" />
                                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                                </svg>
                                <span>Add to Cart</span>
                            </button>

                            <!-- Direct Buy Now Button -->
                            <button type="submit" name="action" value="buy_now" class="btn btn-ochre h-12 px-5 text-xs font-semibold flex-1 flex items-center justify-center gap-2 shadow-sm">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                </svg>
                                <span>Buy Now &rarr;</span>
                            </button>
                        </div>
                    </form>
                @else
                    <div class="p-4 rounded-2xl bg-sale-wash border border-sale/20 text-sale text-sm font-medium">
                        This botanical specimen is currently out of stock. Please check back next season or explore our related varieties below.
                    </div>
                @endif

                <!-- Care Instructions Accordion -->
                <div class="border-t border-hairline pt-6 space-y-3" x-data="{ openCare: true, openDigital: false }">
                    <div class="border border-hairline rounded-xl overflow-hidden bg-paper">
                        <button type="button" @click="openCare = !openCare" class="w-full px-4 py-3 text-left font-display font-semibold text-sm text-forest flex items-center justify-between">
                            <span>Detailed Botanical Care Guide</span>
                            <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': openCare }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </button>
                        <div x-show="openCare" class="px-4 pb-4 text-xs text-forest-2 leading-relaxed border-t border-hairline pt-3">
                            <p>{{ $plant->care_instructions ?? 'Keep in moderate ambient temperature, water when the top two inches of soil feel dry, and ensure adequate drainage.' }}</p>
                        </div>
                    </div>

                    <div class="border border-hairline rounded-xl overflow-hidden bg-paper">
                        <button type="button" @click="openDigital = !openDigital" class="w-full px-4 py-3 text-left font-display font-semibold text-sm text-forest flex items-center justify-between">
                            <span>My Digital Garden Integration</span>
                            <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': openDigital }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </button>
                        <div x-show="openDigital" x-cloak class="px-4 pb-4 text-xs text-forest-2 leading-relaxed border-t border-hairline pt-3">
                            <p>Upon order delivery or in-store handover, this specimen is automatically imported into your Digital Garden. You will receive weather-aware watering reminders, AI leaf health checks, and a monthly CO2 absorption tally.</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Related Plants Rail -->
        @if($relatedPlants->isNotEmpty())
            <div class="mt-20 pt-10 border-t border-hairline">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-display font-bold text-xl sm:text-2xl text-forest">Related Varieties You May Like</h2>
                    <a href="{{ route('shop') }}" class="text-xs font-semibold text-ochre hover:underline">View All &rarr;</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedPlants as $rel)
                        <div class="bg-paper border border-hairline rounded-2xl p-4 flex flex-col justify-between hover:border-ochre transition-all shadow-sm group">
                            <div>
                                <a href="{{ route('product.show', ['slug' => $rel->slug]) }}" class="block relative aspect-square rounded-xl overflow-hidden bg-glass mb-3">
                                    <img src="{{ $rel->primary_image_url }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" />
                                </a>
                                <h3 class="font-semibold text-sm text-forest group-hover:text-ochre transition-colors truncate">
                                    <a href="{{ route('product.show', ['slug' => $rel->slug]) }}">{{ $rel->name }}</a>
                                </h3>
                                <p class="text-xs text-forest-3 italic font-mono truncate">{{ $rel->botanical_name }}</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-hairline flex items-center justify-between">
                                <span class="font-display font-bold text-sm text-forest">${{ number_format($rel->price, 2) }}</span>
                                <a href="{{ route('product.show', ['slug' => $rel->slug]) }}" class="btn btn-ghost !text-xs !py-1 !px-2.5">
                                    View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

@endsection
