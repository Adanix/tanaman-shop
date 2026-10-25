@extends('layouts.app')

@section('title', 'A Botanical Gift for ' . $gift->recipient_name . ' — Plantify Official')

@section('content')

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-20">
        
        <!-- Gift Card Container -->
        <div class="bg-paper border border-hairline rounded-3xl p-6 sm:p-12 shadow-lg text-center space-y-8 relative overflow-hidden">
            
            <div class="absolute -top-12 -right-12 w-36 h-36 bg-ochre/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-36 h-36 bg-stock/10 rounded-full blur-2xl pointer-events-none"></div>

            <!-- Gift Badge / Icon -->
            <div class="w-20 h-20 rounded-2xl bg-ochre/15 text-ochre flex items-center justify-center mx-auto shadow-sm">
                <svg class="w-10 h-10 text-ochre-deep" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 12 20 22 4 22 4 12" />
                    <rect x="2" y="7" width="20" height="5" />
                    <line x1="12" y1="22" x2="12" y2="7" />
                    <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z" />
                    <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z" />
                </svg>
            </div>

            <!-- Heading & Sender Greeting -->
            <div class="space-y-2">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-forest/5 text-forest text-xs font-mono uppercase tracking-wider font-semibold">
                    Personalized Botanical Gift
                </span>
                <h1 class="font-display font-extrabold text-3xl sm:text-4xl text-forest tracking-tight">
                    A Living Gift for {{ $gift->recipient_name }}
                </h1>
                <p class="text-xs sm:text-sm text-forest-3">
                    Sent with love by <strong>{{ $gift->order->customer_name }}</strong>
                </p>
            </div>

            <!-- Personal Message Card -->
            @if(!empty($gift->message))
                <div class="p-6 rounded-2xl bg-glass border border-hairline text-left relative max-w-lg mx-auto">
                    <svg class="w-6 h-6 text-ochre/40 absolute top-4 left-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                    </svg>
                    <p class="italic text-xs sm:text-sm text-forest-2 leading-relaxed pl-8 font-serif">
                        &ldquo;{{ $gift->message }}&rdquo;
                    </p>
                    <p class="text-right text-xs font-mono text-forest-4 mt-2">&mdash; {{ $gift->order->customer_name }}</p>
                </div>
            @endif

            <!-- Included Plants List -->
            <div class="space-y-4 max-w-lg mx-auto text-left">
                <h3 class="font-display font-bold text-sm text-forest uppercase tracking-wider text-center">
                    Plants Included in Your Gift
                </h3>
                <div class="divide-y divide-hairline bg-glass/60 rounded-2xl p-4 border border-hairline">
                    @foreach($gift->order->items as $item)
                        <div class="py-3 first:pt-0 last:pb-0 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-paper overflow-hidden flex-shrink-0 border border-hairline">
                                    <img src="{{ $item->plant->primary_image_url ?? '' }}" alt="{{ $item->plant_name }}" class="w-full h-full object-cover" />
                                </div>
                                <div>
                                    <h4 class="font-semibold text-forest">{{ $item->plant_name }}</h4>
                                    <p class="text-[11px] text-forest-4 italic font-mono">{{ $item->plant->botanical_name ?? '' }}</p>
                                </div>
                            </div>
                            <span class="font-mono font-bold text-forest">{{ $item->quantity }}x unit(s)</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Claim Status Action -->
            <div class="pt-4 max-w-md mx-auto">
                @if($gift->isClaimed())
                    <div class="p-4 rounded-xl bg-stock-wash border border-stock/30 text-stock text-xs font-medium space-y-1">
                        <p class="font-semibold">Gift Already Claimed</p>
                        <p class="text-[11px]">This gift has been successfully claimed by {{ $gift->claimedBy?->name ?? 'the recipient' }} and added to their Digital Garden.</p>
                        <div class="pt-2">
                            <a href="{{ route('garden.index') }}" class="btn btn-primary text-xs">
                                Open My Digital Garden &rarr;
                            </a>
                        </div>
                    </div>
                @elseif($gift->isClaimable())
                    <div class="space-y-3">
                        <form method="POST" action="{{ route('gift.claim', ['token' => $gift->token]) }}">
                            @csrf
                            <button type="submit" class="btn btn-ochre w-full text-sm !py-3">
                                Claim &amp; Adopt to My Digital Garden &rarr;
                            </button>
                        </form>
                        <p class="text-[11px] text-forest-4 font-mono">
                            Claiming activates personalized watering reminders, AI leaf diagnoses, and nursery support.
                        </p>
                    </div>
                @else
                    <div class="p-4 rounded-xl bg-glass border border-hairline text-xs text-forest-3 space-y-1">
                        <p class="font-semibold text-forest">Your Plant Gift is Being Prepared</p>
                        <p class="text-[11px] leading-relaxed">
                            Our Lea Valley nursery team is currently acclimating and packaging your plants for safe delivery. Once the package arrives at your address, this claim button will activate!
                        </p>
                    </div>
                @endif
            </div>

        </div>

    </div>

@endsection
