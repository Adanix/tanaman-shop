@extends('layouts.app')

@section('title', 'About Our Nursery — Plantify Official')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-16">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-forest-3 font-mono mb-6" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-ochre">Home</a>
            <span>/</span>
            <span class="text-forest font-semibold">About Nursery</span>
        </nav>

        <!-- Nursery Story Header -->
        <div class="max-w-3xl mb-12">
            <span class="text-xs font-mono font-semibold text-ochre uppercase tracking-wider">Our Botanical Heritage</span>
            <h1 class="font-display font-extrabold text-3xl sm:text-5xl text-forest mt-1.5 tracking-tight leading-tight">
                Cultivated with botanical patience, backed by digital care.
            </h1>
            <p class="text-sm sm:text-base text-forest-2 mt-4 leading-relaxed">
                Plantify Official was established with a singular mission: to make thriving houseplants accessible and joyful. We combine greenhouse acclimation, peat-free organic growing mediums, and an intelligent Digital Garden companion so every plant flourishes in its new home.
            </p>
        </div>

        <!-- Facts & Numbers Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mb-16">
            <div class="bg-paper border border-hairline rounded-2xl p-6 text-center shadow-sm">
                <span class="font-display font-extrabold text-3xl sm:text-4xl text-forest">100%</span>
                <p class="text-xs text-forest-3 mt-1.5 font-medium">Peat-Free Organic Substrate</p>
            </div>
            <div class="bg-paper border border-hairline rounded-2xl p-6 text-center shadow-sm">
                <span class="font-display font-extrabold text-3xl sm:text-4xl text-forest">14 Days</span>
                <p class="text-xs text-forest-3 mt-1.5 font-medium">Growth Health Guarantee</p>
            </div>
            <div class="bg-paper border border-hairline rounded-2xl p-6 text-center shadow-sm">
                <span class="font-display font-extrabold text-3xl sm:text-4xl text-ochre">14.8 Tons</span>
                <p class="text-xs text-forest-3 mt-1.5 font-medium">CO2 Captured Annually</p>
            </div>
            <div class="bg-paper border border-hairline rounded-2xl p-6 text-center shadow-sm">
                <span class="font-display font-extrabold text-3xl sm:text-4xl text-forest">&gt; 5,000</span>
                <p class="text-xs text-forest-3 mt-1.5 font-medium">Plant Parents Supported</p>
            </div>
        </div>

        <!-- Editorial Story + Image -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center mb-16">
            <div class="space-y-4">
                <h2 class="font-display font-bold text-2xl sm:text-3xl text-forest tracking-tight">
                    Why Greenhouse Acclimation Matters
                </h2>
                <p class="text-xs sm:text-sm text-forest-2 leading-relaxed">
                    Most retail houseplants are shipped directly from humid equatorial shade houses into harsh consumer living rooms, causing shocking leaf loss within weeks.
                </p>
                <p class="text-xs sm:text-sm text-forest-2 leading-relaxed">
                    At Plantify, every specimen undergoes a rigorous 4-to-6-week intermediate humidity stepping process before being certified ready for adoption. We inspect root systems, prune dormant runners, and ensure root balls are robust.
                </p>
                <div class="pt-2">
                    <a href="{{ route('shop') }}" class="btn btn-primary text-xs">
                        Browse Acclimated Plants &rarr;
                    </a>
                </div>
            </div>

            <div class="rounded-3xl overflow-hidden bg-glass border border-hairline shadow-md aspect-[4/3]">
                <img src="https://images.unsplash.com/photo-1470058869958-2a77ade41c02?w=800&q=75&auto=format&fit=crop" alt="Greenhouse nursery aisle" class="w-full h-full object-cover" />
            </div>
        </div>

        <!-- Physical Nursery Location & Hours -->
        <div class="bg-forest text-glass rounded-3xl p-8 sm:p-12 shadow-xl border border-forest-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="space-y-3">
                    <span class="text-xs font-mono uppercase tracking-wider text-ochre font-semibold">Physical Counter Desk</span>
                    <h3 class="font-display font-bold text-2xl text-glass">Visit Our Nursery &amp; Pickup Desk</h3>
                    <p class="text-xs sm:text-sm text-glass/80 leading-relaxed">
                        Experience our botanical collection in person, consult with resident horticulturists, or collect your online orders seamlessly with your pickup code.
                    </p>
                    <div class="space-y-1 text-xs text-glass/70 pt-2 font-mono">
                        <p><strong>Address:</strong> Jl. Agrowisata Hijau No. 24, Botanical District, Jakarta Selatan</p>
                        <p><strong>Store Pickup Hours:</strong> Tuesday &ndash; Sunday, 08:30 &ndash; 17:00 (Closed Mondays)</p>
                    </div>
                </div>

                <div class="p-6 bg-forest-dark rounded-2xl border border-forest-2 space-y-4">
                    <h4 class="font-display font-bold text-base text-glass">Have Questions About Your Plants?</h4>
                    <p class="text-xs text-glass/70 leading-relaxed">
                        Our botanical care team is on standby to assist with fertilization questions, potting queries, and corporate plant styling.
                    </p>
                    <a href="{{ route('care.index') }}" class="btn btn-ochre text-xs inline-flex">
                        Consult AI Care Doctor &rarr;
                    </a>
                </div>
            </div>
        </div>

    </div>

@endsection
