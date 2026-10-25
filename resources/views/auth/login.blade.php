@extends('layouts.app')

@section('title', 'Sign In — Plantify Official')

@section('content')

    <div class="max-w-md mx-auto px-4 sm:px-6 pt-12 pb-20">
        
        <div class="text-center space-y-2 mb-8">
            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-forest tracking-tight">
                Welcome Back to Plantify
            </h1>
            <p class="text-xs sm:text-sm text-forest-3">
                Sign in to manage your Digital Garden, track orders, and consult AI Plant Doctor.
            </p>
        </div>

        <div class="bg-paper border border-hairline rounded-2xl p-6 sm:p-8 shadow-sm">
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Email Address *</label>
                    <input type="email" id="email" name="email" required autofocus value="{{ old('email') }}" class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" placeholder="you@example.com" />
                </div>

                <div>
                    <label for="password" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Password *</label>
                    <input type="password" id="password" name="password" required class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" />
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 text-xs text-forest cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-hairline text-ochre focus:ring-ochre" />
                        <span>Remember me</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn btn-primary w-full text-xs !py-2.5">
                        Sign In &rarr;
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-4 border-t border-hairline text-center text-xs text-forest-3">
                <span>Don't have an account yet?</span>
                <a href="{{ route('register') }}" class="text-ochre hover:underline font-semibold ml-1">Create one now</a>
            </div>
        </div>

    </div>

@endsection
