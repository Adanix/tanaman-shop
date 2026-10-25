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

            <!-- Quick Demo Access Section -->
            <div class="mt-6 pt-6 border-t border-hairline space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-mono uppercase tracking-wider text-forest-4 font-semibold">Demo Access (One-Click)</span>
                    <span class="text-[10px] bg-stock-wash text-stock font-mono px-2 py-0.5 rounded-full font-semibold">Instant Login</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <button type="button" 
                            onclick="fillAndSubmit('admin@plantify.test', 'Plantify2026!')" 
                            class="flex flex-col items-start p-3 bg-glass hover:bg-glass-dark border border-hairline rounded-xl text-left transition-colors group cursor-pointer">
                        <span class="text-xs font-bold text-forest flex items-center justify-between w-full">
                            <span>Sign in as Admin</span>
                            <span class="text-[10px] text-ochre font-mono group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </span>
                        <span class="text-[10px] text-forest-3 font-mono truncate mt-0.5">admin@plantify.test</span>
                        <span class="text-[10px] text-forest-4 mt-1">POS Pickup, Orders &amp; Settings</span>
                    </button>

                    <button type="button" 
                            onclick="fillAndSubmit('customer@plantify.test', 'Plantify2026!')" 
                            class="flex flex-col items-start p-3 bg-glass hover:bg-glass-dark border border-hairline rounded-xl text-left transition-colors group cursor-pointer">
                        <span class="text-xs font-bold text-forest flex items-center justify-between w-full">
                            <span>Sign in as Customer</span>
                            <span class="text-[10px] text-ochre font-mono group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </span>
                        <span class="text-[10px] text-forest-3 font-mono truncate mt-0.5">customer@plantify.test</span>
                        <span class="text-[10px] text-forest-4 mt-1">Digital Garden &amp; AI Care</span>
                    </button>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-hairline text-center text-xs text-forest-3">
                <span>Don't have an account yet?</span>
                <a href="{{ route('register') }}" class="text-ochre hover:underline font-semibold ml-1">Create one now</a>
            </div>
        </div>

    </div>

    <script>
        function fillAndSubmit(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            document.getElementById('email').form.submit();
        }
    </script>
@endsection
