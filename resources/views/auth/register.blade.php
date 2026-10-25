@extends('layouts.app')

@section('title', 'Register Account — Plantify Official')

@section('content')

    <div class="max-w-md mx-auto px-4 sm:px-6 pt-10 pb-20">
        
        <div class="text-center space-y-2 mb-8">
            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-forest tracking-tight">
                Create Your Account
            </h1>
            <p class="text-xs sm:text-sm text-forest-3">
                Join our botanical community for personal Digital Garden tracking and free AI plant care.
            </p>
        </div>

        <div class="bg-paper border border-hairline rounded-2xl p-6 sm:p-8 shadow-sm">
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Full Name *</label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}" placeholder="e.g. Eleanor Vance" class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" />
                </div>

                <div>
                    <label for="email" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Email Address *</label>
                    <input type="email" id="email" name="email" required value="{{ old('email') }}" placeholder="eleanor@example.com" class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" />
                </div>

                <div>
                    <label for="phone" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+1 (555) 000-0000" class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" />
                </div>

                <div>
                    <label for="city" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">City (for Weather Reminders)</label>
                    <input type="text" id="city" name="city" value="{{ old('city') }}" placeholder="e.g. Jakarta Selatan" class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" />
                </div>

                <div>
                    <label for="password" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Password * (min 8 chars, letters &amp; numbers)</label>
                    <input type="password" id="password" name="password" required class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" />
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-mono uppercase tracking-wider text-forest-3 mb-1 font-medium">Confirm Password *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full text-xs h-10 px-3 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" />
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn btn-primary w-full text-xs !py-2.5">
                        Register Account &rarr;
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-4 border-t border-hairline text-center text-xs text-forest-3">
                <span>Already have an account?</span>
                <a href="{{ route('login') }}" class="text-ochre hover:underline font-semibold ml-1">Sign in here</a>
            </div>
        </div>

    </div>

@endsection
