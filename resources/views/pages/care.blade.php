@extends('layouts.app')

@section('title', 'AI Plant Doctor & Care Guides — Plantify Official')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-16">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-forest-3 font-mono mb-6" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-ochre">Home</a>
            <span>/</span>
            <span class="text-forest font-semibold">AI Plant Doctor &amp; Care</span>
        </nav>

        <!-- Header -->
        <div class="mb-10">
            <span class="text-xs font-mono font-semibold text-ochre uppercase tracking-wider">Botanical Intelligence</span>
            <h1 class="font-display font-extrabold text-2xl sm:text-4xl text-forest mt-1 tracking-tight">AI Plant Doctor &amp; Botanical Care</h1>
            <p class="text-xs sm:text-sm text-forest-3 mt-1.5 max-w-2xl">
                Diagnose foliage anomalies with Ciora AI Vision and consult our botanical assistant for expert organic recovery guidance.
            </p>
        </div>

        <!-- 1. AI Plant Doctor Photo Diagnosis Section -->
        <section class="bg-forest text-glass rounded-3xl p-6 sm:p-10 mb-12 border border-forest-2 relative overflow-hidden"
                 x-data="{
                     loading: false,
                     errorMsg: '',
                     diagnosis: null,
                     remainingQuota: {{ $doctorQuota }},
                     selectedFile: null,
                     previewUrl: null,
                     onFileChange(e) {
                         const file = e.target.files[0];
                         if (file) {
                             this.selectedFile = file;
                             this.previewUrl = URL.createObjectURL(file);
                             this.diagnosis = null;
                             this.errorMsg = '';
                         }
                     },
                     async submitDiagnosis() {
                         if (!this.selectedFile) return;
                         this.loading = true;
                         this.errorMsg = '';
                         this.diagnosis = null;

                         const formData = new FormData();
                         formData.append('image', this.selectedFile);

                         try {
                             const res = await fetch('{{ route('ai.diagnose') }}', {
                                 method: 'POST',
                                 headers: {
                                     'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                     'Accept': 'application/json'
                                 },
                                 body: formData
                             });
                             const data = await res.json();
                             if (!res.ok) {
                                 this.errorMsg = data.error || 'Diagnosis failed. Please check your image.';
                             } else {
                                 this.diagnosis = data.diagnosis;
                                 this.remainingQuota = data.remaining_quota;
                             }
                         } catch (err) {
                             this.errorMsg = 'Network error during AI vision processing.';
                         } finally {
                             this.loading = false;
                         }
                     }
                 }">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Info & Quota (col-span-5) -->
                <div class="lg:col-span-5 space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-forest-2 border border-forest-3 text-ochre text-xs font-mono font-semibold">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                        <span>Ciora AI Botanical Vision</span>
                    </div>

                    <h2 class="font-display font-extrabold text-2xl sm:text-3xl text-white tracking-tight">
                        Foliage Health Diagnostic
                    </h2>
                    <p class="text-xs sm:text-sm text-hairline leading-relaxed">
                        Photograph yellowing leaves, brown tips, or pest webbing. Our AI Vision model inspects foliar distress patterns and formulates an actionable treatment protocol.
                    </p>

                    <!-- Quota & Auth Status -->
                    <div class="p-4 rounded-xl bg-forest-2 border border-forest-3 text-xs space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-hairline-soft font-mono uppercase text-[11px]">Daily Diagnosis Quota</span>
                            <span class="font-mono font-bold text-ochre" x-text="remainingQuota + ' / 5 remaining today'"></span>
                        </div>
                        <div class="w-full h-1.5 bg-forest-dark rounded-full overflow-hidden">
                            <div class="h-full bg-ochre transition-all" :style="'width: ' + (remainingQuota / 5 * 100) + '%'"></div>
                        </div>
                        @guest
                            <p class="text-[11px] text-ochre pt-1">
                                <a href="{{ route('login') }}" class="underline font-semibold">Sign in</a> with a verified email to use AI Doctor.
                            </p>
                        @endguest
                    </div>
                </div>

                <!-- Right: Upload Form & Results (col-span-7) -->
                <div class="lg:col-span-7 bg-forest-dark border border-forest-2 rounded-2xl p-6 shadow-inner space-y-4">
                    
                    @auth
                        @if(Auth::user()->hasVerifiedEmail())
                            <!-- Photo Uploader -->
                            <div>
                                <label class="block text-xs font-mono uppercase tracking-wider text-hairline mb-2">Upload Clear Foliage Photo</label>
                                <input type="file" accept="image/*" @change="onFileChange" class="w-full text-xs text-hairline file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-ochre file:text-white hover:file:bg-ochre-deep cursor-pointer" />
                            </div>

                            <!-- Preview -->
                            <template x-if="previewUrl">
                                <div class="relative w-full max-h-56 rounded-xl overflow-hidden bg-forest border border-forest-2 flex items-center justify-center">
                                    <img :src="previewUrl" alt="Foliage preview" class="max-h-56 object-contain" />
                                </div>
                            </template>

                            <div class="pt-2">
                                <button type="button" @click="submitDiagnosis" :disabled="loading || !selectedFile || remainingQuota <= 0" class="btn btn-ochre text-xs w-full disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-show="!loading">Analyze Plant with Ciora AI &rarr;</span>
                                    <span x-show="loading" x-cloak class="flex items-center gap-2">
                                        <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                        </svg>
                                        Diagnosing foliar pathology...
                                    </span>
                                </button>
                            </div>

                            <!-- Error Message -->
                            <template x-if="errorMsg">
                                <div class="p-3 rounded-xl bg-sale-wash border border-sale/40 text-sale text-xs" x-text="errorMsg"></div>
                            </template>

                            <!-- Diagnosis Result Card -->
                            <template x-if="diagnosis">
                                <div class="p-4 rounded-xl bg-forest border border-stock/40 text-xs space-y-3 mt-4">
                                    <div class="flex items-center justify-between border-b border-forest-2 pb-2">
                                        <span class="font-display font-bold text-sm text-ochre" x-text="diagnosis.diagnosis || 'Diagnosis Complete'"></span>
                                        <span class="badge badge-stock text-[10px]" x-text="'Confidence: ' + (diagnosis.confidence || 'High')"></span>
                                    </div>
                                    <div>
                                        <strong class="text-hairline font-mono uppercase text-[10px] block">Root Cause Analysis</strong>
                                        <p class="text-hairline-soft mt-0.5 leading-relaxed" x-text="diagnosis.explanation || 'Visual analysis completed.'"></p>
                                    </div>
                                    <div>
                                        <strong class="text-stock font-mono uppercase text-[10px] block">Recommended Recovery Steps</strong>
                                        <div class="text-hairline-soft mt-0.5 leading-relaxed whitespace-pre-line" x-text="diagnosis.treatment"></div>
                                    </div>
                                </div>
                            </template>
                        @else
                            <div class="p-6 text-center space-y-2">
                                <p class="text-xs text-ochre font-semibold">Email Verification Required</p>
                                <p class="text-xs text-hairline-soft">Please verify your email address to access AI Plant Doctor diagnosis.</p>
                                <div class="pt-2">
                                    <a href="{{ route('verification.notice') }}" class="btn btn-ochre text-xs">Verify Email Address</a>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="p-6 text-center space-y-2">
                            <p class="text-xs text-ochre font-semibold">Account Login Required</p>
                            <p class="text-xs text-hairline-soft">Please sign in or create an account to submit plants for AI diagnosis.</p>
                            <div class="pt-2 flex justify-center gap-2">
                                <a href="{{ route('login') }}" class="btn btn-ghost !text-white border-forest-3 text-xs">Sign In</a>
                                <a href="{{ route('register') }}" class="btn btn-ochre text-xs">Create Account</a>
                            </div>
                        </div>
                    @endauth

                </div>

            </div>
        </section>

        <!-- 2. AI Botanical Care Assistant Chatbot -->
        <section class="bg-paper border border-hairline rounded-3xl p-6 sm:p-10 mb-12 shadow-sm"
                 x-data="{
                     chatMessages: [
                         { role: 'assistant', text: 'Hello! I am your Plantify Botanical Assistant. Ask me about light levels, repotting, pest prevention, or propagation tips.' }
                     ],
                     inputMsg: '',
                     chatLoading: false,
                     chatQuota: {{ $chatQuota }},
                     chatError: '',
                     async sendChat() {
                         const msg = this.inputMsg.trim();
                         if (!msg || this.chatLoading) return;
                         this.inputMsg = '';
                         this.chatError = '';
                         this.chatMessages.push({ role: 'user', text: msg });
                         this.chatLoading = true;

                         try {
                             const res = await fetch('{{ route('ai.chat') }}', {
                                 method: 'POST',
                                 headers: {
                                     'Content-Type': 'application/json',
                                     'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                     'Accept': 'application/json'
                                 },
                                 body: JSON.stringify({ message: msg })
                             });
                             const data = await res.json();
                             if (!res.ok) {
                                 this.chatError = data.error || 'Failed to receive reply.';
                             } else {
                                 this.chatMessages.push({ role: 'assistant', text: data.reply });
                                 this.chatQuota = data.remaining_quota;
                             }
                         } catch (err) {
                             this.chatError = 'Network error while contacting botanical chat.';
                         } finally {
                             this.chatLoading = false;
                         }
                     }
                 }">

            <div class="flex items-center justify-between pb-4 border-b border-hairline mb-6">
                <div>
                    <h2 class="font-display font-bold text-xl text-forest">Botanical Care Assistant</h2>
                    <p class="text-xs text-forest-3 mt-0.5">Real-time consultation with AI care guidelines</p>
                </div>
                <span class="badge badge-stock text-xs" x-text="chatQuota + ' questions remaining today'"></span>
            </div>

            <!-- Chat Messages Log -->
            <div class="h-64 overflow-y-auto space-y-3 p-4 bg-glass rounded-2xl border border-hairline mb-4">
                <template x-for="(item, index) in chatMessages" :key="index">
                    <div :class="item.role === 'user' ? 'text-right' : 'text-left'">
                        <div class="inline-block max-w-[85%] rounded-2xl p-3 text-xs"
                             :class="item.role === 'user' ? 'bg-forest text-glass rounded-br-none' : 'bg-paper border border-hairline text-forest rounded-bl-none shadow-sm'">
                            <p class="leading-relaxed whitespace-pre-line" x-text="item.text"></p>
                        </div>
                    </div>
                </template>
                <div x-show="chatLoading" class="text-left">
                    <div class="inline-block bg-paper border border-hairline rounded-2xl p-2.5 text-xs text-forest-4 italic">
                        Botanical assistant is writing...
                    </div>
                </div>
            </div>

            <!-- Chat Input Box -->
            @auth
                @if(Auth::user()->hasVerifiedEmail())
                    <form @submit.prevent="sendChat" class="flex gap-2">
                        <input type="text" x-model="inputMsg" placeholder="Ask e.g. How often should I fertilize my Monstera in winter?..." class="flex-grow text-xs h-11 px-4 bg-glass border border-hairline rounded-xl text-forest focus:outline-none focus:border-ochre" :disabled="chatLoading || chatQuota <= 0" />
                        <button type="submit" class="btn btn-primary text-xs h-11 !px-5" :disabled="chatLoading || chatQuota <= 0">
                            Send
                        </button>
                    </form>
                    <template x-if="chatError">
                        <p class="text-sale text-xs mt-2" x-text="chatError"></p>
                    </template>
                @else
                    <p class="text-xs text-forest-3 text-center py-2">
                        Please <a href="{{ route('verification.notice') }}" class="text-ochre underline font-semibold">verify your email</a> to use botanical chat.
                    </p>
                @endif
            @else
                <p class="text-xs text-forest-3 text-center py-2">
                    Please <a href="{{ route('login') }}" class="text-ochre underline font-semibold">sign in</a> to consult our botanical chatbot.
                </p>
            @endauth
        </section>

        <!-- 3. Botanical Care Guides Grid -->
        <section class="space-y-6">
            <div>
                <span class="text-xs font-mono uppercase tracking-wider text-ochre font-semibold">Nursery Knowledge Base</span>
                <h2 class="font-display font-bold text-xl sm:text-2xl text-forest tracking-tight">Essential Indoor Plant Principles</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-2">
                    <div class="w-10 h-10 rounded-xl bg-stock-wash text-stock flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21s-6-5.686-6-10A6 6 0 0118 11c0 4.314-6 10-6 10z" />
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-base text-forest">The "Dry Top Two Inches" Rule</h3>
                    <p class="text-xs text-forest-3 leading-relaxed">
                        More houseplants perish from overwatering than underwatering. Always probe your finger 2 inches into the potting medium. Only irrigate when the soil is dry to the touch.
                    </p>
                </div>

                <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-2">
                    <div class="w-10 h-10 rounded-xl bg-ochre/15 text-ochre flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="12" cy="12" r="5" />
                            <line x1="12" y1="1" x2="12" y2="3" />
                            <line x1="12" y1="21" x2="12" y2="23" />
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                            <line x1="1" y1="12" x2="3" y2="12" />
                            <line x1="21" y1="12" x2="23" y2="12" />
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-base text-forest">Indirect vs Direct Sunlight</h3>
                    <p class="text-xs text-forest-3 leading-relaxed">
                        Tropical jungle floor species (Monstera, Calathea, Philodendron) thrive in bright, filtered light. Direct sun rays scorch tender leaves and bleach variegated patterns.
                    </p>
                </div>

                <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-2">
                    <div class="w-10 h-10 rounded-xl bg-stock-wash text-stock flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-base text-forest">Managing Ambient Humidity</h3>
                    <p class="text-xs text-forest-3 leading-relaxed">
                        Air-conditioned rooms cause leaf browning. Grouping multiple specimens together naturally raises localized relative humidity through vegetative transpiration.
                    </p>
                </div>
            </div>
        </section>

    </div>

@endsection
