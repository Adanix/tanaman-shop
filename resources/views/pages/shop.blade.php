@extends('layouts.app')

@section('title', 'Botanical Catalog — Plantify Official')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-16">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-forest-3 font-mono mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-ochre">Home</a>
            <span>/</span>
            <span class="text-forest font-semibold">Living Catalog</span>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="font-display font-extrabold text-3xl sm:text-4xl text-forest tracking-tight">The Botanical Catalog</h1>
            <p class="text-xs sm:text-sm text-forest-3 mt-1.5 max-w-2xl">
                Nursery-raised houseplants, greenhouse acclimated and root-inspected. Every plant includes digital care tracking.
            </p>
        </div>

        <!-- Filter & Search Form -->
        <form method="GET" action="{{ route('shop') }}" class="bg-paper border border-hairline rounded-2xl p-4 sm:p-6 mb-8 shadow-sm">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                
                <!-- Search -->
                <div>
                    <label for="search" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Search</label>
                    <div class="relative">
                        <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Plant name or botanical..." class="w-full text-xs h-10 px-3 pl-8 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" />
                        <svg class="w-3.5 h-3.5 text-forest-4 absolute left-2.5 top-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Category</label>
                    <select id="category" name="category" class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }} ({{ $cat->plants_count ?? 0 }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Light -->
                <div>
                    <label for="light" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Light Condition</label>
                    <select id="light" name="light" class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre">
                        <option value="">Any Light</option>
                        <option value="low" {{ request('light') === 'low' ? 'selected' : '' }}>Low Indirect Light</option>
                        <option value="medium" {{ request('light') === 'medium' ? 'selected' : '' }}>Medium Indirect Light</option>
                        <option value="bright" {{ request('light') === 'bright' ? 'selected' : '' }}>Bright Filtered Light</option>
                    </select>
                </div>

                <!-- Difficulty -->
                <div>
                    <label for="difficulty" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Care Level</label>
                    <select id="difficulty" name="difficulty" class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre">
                        <option value="">All Care Levels</option>
                        <option value="beginner" {{ request('difficulty') === 'beginner' ? 'selected' : '' }}>Beginner (Forgiving)</option>
                        <option value="intermediate" {{ request('difficulty') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="expert" {{ request('difficulty') === 'expert' ? 'selected' : '' }}>Expert Collector</option>
                    </select>
                </div>

                <!-- Sort & Pet-Friendly -->
                <div class="flex items-center gap-2">
                    <div class="flex-grow">
                        <label for="sort" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Sort By</label>
                        <select id="sort" name="sort" class="w-full text-xs h-10 px-2 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre">
                            <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>Featured</option>
                            <option value="price-asc" {{ request('sort') === 'price-asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price-desc" {{ request('sort') === 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name A-Z</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary h-10 !px-4 text-xs mt-auto">
                        Filter
                    </button>
                </div>

            </div>

            <!-- Pet Safe checkbox row -->
            <div class="mt-4 pt-3 border-t border-hairline flex items-center justify-between">
                <label class="flex items-center gap-2 text-xs font-medium text-forest cursor-pointer">
                    <input type="checkbox" name="pet_safe" value="1" {{ request('pet_safe') ? 'checked' : '' }} class="rounded border-hairline text-ochre focus:ring-ochre" onchange="this.form.submit()" />
                    <span>Show Pet-Friendly (ASPCA safe) specimens only</span>
                </label>
                @if(request()->hasAny(['search', 'category', 'light', 'difficulty', 'pet_safe', 'sort']))
                    <a href="{{ route('shop') }}" class="text-xs text-sale hover:underline font-mono">
                        Clear all filters
                    </a>
                @endif
            </div>
        </form>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($plants as $plant)
                <div class="bg-paper border border-hairline rounded-2xl p-4 flex flex-col justify-between hover:border-ochre transition-all shadow-sm group">
                    <div>
                        <a href="{{ route('product.show', ['slug' => $plant->slug]) }}" class="block relative aspect-square rounded-xl overflow-hidden bg-glass mb-3">
                            <img src="{{ $plant->primary_image_url }}" alt="{{ $plant->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" />
                            
                            @if($plant->stock <= 5 && $plant->stock > 0)
                                <span class="absolute top-2 left-2 badge badge-low">
                                    Only {{ $plant->stock }} left
                                </span>
                            @elseif($plant->stock <= 0)
                                <span class="absolute top-2 left-2 badge badge-sale">
                                    Sold Out
                                </span>
                            @else
                                <span class="absolute top-2 left-2 badge badge-stock">
                                    In Stock ({{ $plant->stock }})
                                </span>
                            @endif

                            @if($plant->is_pet_friendly)
                                <span class="absolute top-2 right-2 bg-paper/90 backdrop-blur-sm text-stock text-[10px] font-mono px-2 py-0.5 rounded-full border border-hairline font-semibold">
                                    Pet-Safe
                                </span>
                            @endif
                        </a>

                        <div class="space-y-1">
                            <div class="flex items-center justify-between text-[11px] font-mono text-forest-4 uppercase">
                                <span>{{ $plant->category->name ?? 'Houseplant' }}</span>
                                <span class="capitalize">{{ $plant->difficulty_level }}</span>
                            </div>
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
                        @if($plant->stock > 0)
                            <form method="POST" action="{{ route('cart.add') }}">
                                @csrf
                                <input type="hidden" name="plant_id" value="{{ $plant->id }}" />
                                <input type="hidden" name="quantity" value="1" />
                                <button type="submit" class="btn btn-primary !text-xs !py-1.5 !px-3">
                                    Add to Cart
                                </button>
                            </form>
                        @else
                            <button type="button" disabled class="btn btn-ghost !text-xs !py-1.5 !px-3 opacity-50 cursor-not-allowed">
                                Out of Stock
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-paper rounded-2xl border border-hairline">
                    <svg class="w-12 h-12 text-forest-4 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="4.93" y1="4.93" x2="19.07" y2="19.07" />
                    </svg>
                    <h3 class="font-display font-bold text-lg text-forest">No matching specimens found</h3>
                    <p class="text-xs text-forest-3 mt-1">Try adjusting your filters or search keywords.</p>
                    <div class="mt-4">
                        <a href="{{ route('shop') }}" class="btn btn-ghost text-xs">Reset All Filters</a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $plants->links() }}
        </div>
    </div>

@endsection
