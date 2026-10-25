@extends('layouts.app')

@section('title', 'Plantify Official — Living Botanicals & Digital Care')

@section('content')

    <!-- 1. Hero Botanical Banner -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-8">
        <div class="relative bg-forest rounded-3xl overflow-hidden shadow-xl text-glass p-8 sm:p-12 lg:p-16 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-xl space-y-4 z-10">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-ochre/20 text-ochre text-xs font-mono uppercase tracking-wider font-semibold border border-ochre/30">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    Nursery Grown &bull; 100% Acclimated
                </span>
                <h1 class="font-display font-extrabold text-3xl sm:text-4xl lg:text-5xl tracking-tight leading-tight">
                    Living botanicals for your mindful living space.
                </h1>
                <p class="text-sm sm:text-base text-glass/80 leading-relaxed">
                    Explore sustainably grown indoor plants, botanical ceramics, and an automated Digital Garden companion powered by AI.
                </p>
                <div class="pt-2 flex flex-wrap items-center gap-4">
                    <a href="{{ route('shop') }}" class="btn btn-ochre text-sm px-6 py-3">
                        Shop Living Catalog
                    </a>
                    <a href="{{ route('garden.index') }}" class="btn btn-ghost !text-glass border-glass/30 hover:border-glass text-sm px-6 py-3">
                        Open My Digital Garden &rarr;
                    </a>
                </div>
            </div>

            <div class="relative w-full md:w-1/2 flex justify-center">
                <div class="w-64 sm:w-80 h-72 sm:h-96 rounded-2xl overflow-hidden shadow-2xl border-4 border-paper/10 transform rotate-1 hover:rotate-0 transition-transform duration-500">
                    <img src="https://images.unsplash.com/photo-1614594975525-e45190c55d0b?w=700&q=80&auto=format&fit=crop" alt="Monstera Deliciosa Botanical" class="w-full h-full object-cover" />
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Dynamic Category Rail -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" aria-label="Explore Categories">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="font-display font-bold text-xl sm:text-2xl text-forest tracking-tight">Curated Plant Collections</h2>
                <p class="text-xs text-forest-3 mt-0.5">Explore by species, environment, and care requirement</p>
            </div>
            <a href="{{ route('shop') }}" class="text-xs font-semibold text-ochre hover:underline flex items-center gap-1">
                View All Categories &rarr;
            </a>
        </div>

        <div class="flex gap-4 overflow-x-auto pb-4 no-scrollbar snap-x snap-mandatory">
            @forelse($categories as $category)
                <a href="{{ route('shop', ['category' => $category->slug]) }}" class="flex-none w-40 sm:w-48 snap-start p-3 bg-paper border border-hairline rounded-2xl hover:border-ochre hover:-translate-y-1 transition-all group shadow-sm">
                    <div class="w-full aspect-square rounded-xl overflow-hidden bg-glass">
                        <img src="{{ $category->image_url ?? 'https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=340&q=72&auto=format&fit=crop' }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" />
                    </div>
                    <div class="mt-2.5">
                        <h3 class="font-semibold text-sm text-forest group-hover:text-ochre transition-colors truncate">{{ $category->name }}</h3>
                        <p class="font-mono text-[11px] text-forest-4 mt-0.5">{{ $category->plants_count ?? 0 }} varieties</p>
                    </div>
                </a>
            @empty
                <div class="p-6 text-center text-sm text-forest-3 bg-paper rounded-2xl border border-hairline w-full">
                    No botanical categories loaded.
                </div>
            @endforelse
        </div>
    </section>

    <!-- 3. Featured Nursery Specimens -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <span class="text-xs font-mono uppercase tracking-wider text-ochre font-semibold">Handpicked Arrivals</span>
                <h2 class="font-display font-bold text-xl sm:text-2xl text-forest tracking-tight">Featured Living Plants</h2>
            </div>
            <a href="{{ route('shop') }}" class="text-xs font-semibold text-ochre hover:underline">
                Explore Shop &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($featuredPlants as $plant)
                <div class="bg-paper border border-hairline rounded-2xl p-4 flex flex-col justify-between hover:border-ochre transition-all shadow-sm group">
                    <div>
                        <a href="{{ route('product.show', ['slug' => $plant->slug]) }}" class="block relative aspect-square rounded-xl overflow-hidden bg-glass mb-3">
                            <img src="{{ $plant->primary_image_url }}" alt="{{ $plant->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" />
                            @if($plant->stock <= 5)
                                <span class="absolute top-2 left-2 badge badge-low">
                                    Only {{ $plant->stock }} left
                                </span>
                            @else
                                <span class="absolute top-2 left-2 badge badge-stock">
                                    In Stock
                                </span>
                            @endif
                            @if($plant->is_pet_friendly)
                                <span class="absolute top-2 right-2 bg-paper/90 backdrop-blur-sm text-stock text-[10px] font-mono px-2 py-0.5 rounded-full border border-hairline font-semibold">
                                    Pet-Safe
                                </span>
                            @endif
                        </a>
                        <div class="space-y-1">
                            <span class="text-[11px] font-mono text-forest-4 uppercase tracking-wider">{{ $plant->category->name ?? 'Houseplant' }}</span>
                            <h3 class="font-semibold text-sm text-forest group-hover:text-ochre transition-colors">
                                <a href="{{ route('product.show', ['slug' => $plant->slug]) }}">{{ $plant->name }}</a>
                            </h3>
                            <p class="text-xs text-forest-3 italic font-mono truncate">{{ $plant->botanical_name }}</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-hairline flex items-center justify-between">
                        <div>
                            <span class="font-display font-bold text-base text-forest">${{ number_format($plant->price, 2) }}</span>
                        </div>
                        <form method="POST" action="{{ route('cart.add') }}">
                            @csrf
                            <input type="hidden" name="plant_id" value="{{ $plant->id }}" />
                            <input type="hidden" name="quantity" value="1" />
                            <button type="submit" class="btn btn-primary !text-xs !py-1.5 !px-3" aria-label="Add {{ $plant->name }} to cart">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-forest-3">
                    No featured plants available currently.
                </div>
            @endforelse
        </div>
    </section>

    <!-- 4. Beginner & Low-Maintenance Highlights -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-12">
        <div class="bg-forest-dark text-glass rounded-3xl p-6 sm:p-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                <div>
                    <span class="text-xs font-mono uppercase tracking-wider text-ochre font-semibold">Resilient Botanicals</span>
                    <h2 class="font-display font-bold text-xl sm:text-2xl text-glass tracking-tight mt-1">Easy-Care Specimens for Beginners</h2>
                    <p class="text-xs text-glass/70 mt-1 max-w-lg">Thrives in varying light conditions with forgiving watering cycles. Perfect for new plant parents.</p>
                </div>
                <a href="{{ route('shop', ['difficulty' => 'beginner']) }}" class="btn btn-ochre text-xs self-start md:self-auto">
                    View All Easy-Care &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($easyCarePlants as $plant)
                    <div class="bg-forest border border-forest-2 rounded-2xl p-4 flex flex-col justify-between hover:border-ochre transition-all shadow-sm">
                        <div>
                            <a href="{{ route('product.show', ['slug' => $plant->slug]) }}" class="block relative aspect-square rounded-xl overflow-hidden bg-forest-2 mb-3">
                                <img src="{{ $plant->primary_image_url }}" alt="{{ $plant->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" loading="lazy" />
                            </a>
                            <div class="space-y-1">
                                <h3 class="font-semibold text-sm text-glass hover:text-ochre transition-colors truncate">
                                    <a href="{{ route('product.show', ['slug' => $plant->slug]) }}">{{ $plant->name }}</a>
                                </h3>
                                <div class="flex items-center gap-2 text-[11px] font-mono text-glass/60">
                                    <span>Every {{ $plant->watering_frequency_days }}d water</span>
                                    <span>&bull;</span>
                                    <span>{{ ucfirst($plant->light_requirement) }} light</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-forest-2 flex items-center justify-between">
                            <span class="font-display font-bold text-sm text-ochre">${{ number_format($plant->price, 2) }}</span>
                            <form method="POST" action="{{ route('cart.add') }}">
                                @csrf
                                <input type="hidden" name="plant_id" value="{{ $plant->id }}" />
                                <input type="hidden" name="quantity" value="1" />
                                <button type="submit" class="btn btn-ghost !text-glass border-glass/20 hover:border-ochre !text-xs !py-1.5 !px-3">
                                    Add
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-8 text-center text-glass/60 text-xs">
                        No beginner specimens found.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

@endsection
