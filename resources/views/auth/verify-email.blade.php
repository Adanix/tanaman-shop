@extends('layouts.app')

@section('title', 'Verify Your Email Address — Plantify Official')

@section('content')

    <div class="max-w-md mx-auto px-4 sm:px-6 pt-12 pb-20">
        
        <div class="bg-paper border border-hairline rounded-3xl p-8 shadow-sm text-center space-y-6">
            <div class="w-16 h-16 rounded-full bg-stock-wash text-stock flex items-center justify-center mx-auto">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>

            <div class="space-y-2">
                <h1 class="font-display font-extrabold text-2xl text-forest">Verify Your Email Address</h1>
                <p class="text-xs text-forest-3 leading-relaxed">
                    Thanks for registering! We've sent a verification link to your email address. Please click the link to verify your account and unlock AI Plant Doctor and gift unboxing features.
                </p>
            </div>

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary w-full text-xs !py-2.5">
                    Resend Verification Email
                </button>
            </form>

            <div class="pt-4 border-t border-hairline flex items-center justify-between text-xs">
                <a href="{{ route('home') }}" class="text-forest-3 hover:text-forest">
                    &larr; Back to Home
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sale hover:underline">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>

    </div>

@endsection
