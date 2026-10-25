@extends('admin.layout')

@section('title', 'Store Settings — Plantify Official')

@section('admin_content')

    <div class="bg-paper border border-hairline rounded-2xl p-6 sm:p-8 shadow-sm space-y-8">
        
        <div class="pb-4 border-b border-hairline">
            <h2 class="font-display font-bold text-lg text-forest">Store &amp; Operational Settings</h2>
            <p class="text-xs text-forest-3 mt-0.5">Configure nursery desk parameters, tax rates, flat delivery pricing, and payment gateways</p>
        </div>

        <form method="POST" action="{{ route('admin.settings.save') }}" class="space-y-8">
            @csrf

            <!-- Section 1: Physical Nursery Desk Profile -->
            <div class="space-y-4">
                <h3 class="font-display font-bold text-sm text-forest uppercase tracking-wider pb-2 border-b border-hairline">
                    1. Physical Nursery Counter &amp; Pickup Desk
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-mono uppercase text-forest-3 mb-1">Store Name *</label>
                        <input type="text" name="store_name" required value="{{ old('store_name', $settings['store_name'] ?? 'Plantify Official') }}" class="w-full text-xs h-9 px-3 bg-glass border border-hairline rounded-lg text-forest" />
                    </div>

                    <div>
                        <label class="block text-[11px] font-mono uppercase text-forest-3 mb-1">City / Region *</label>
                        <input type="text" name="store_city" required value="{{ old('store_city', $settings['store_city'] ?? 'Jakarta Selatan') }}" class="w-full text-xs h-9 px-3 bg-glass border border-hairline rounded-lg text-forest" />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-mono uppercase text-forest-3 mb-1">Physical Street Address *</label>
                        <input type="text" name="store_address" required value="{{ old('store_address', $settings['store_address'] ?? 'Jl. Agrowisata Hijau No. 24, Botanical District') }}" class="w-full text-xs h-9 px-3 bg-glass border border-hairline rounded-lg text-forest" />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-mono uppercase text-forest-3 mb-1">Operating Hours *</label>
                        <input type="text" name="store_hours" required value="{{ old('store_hours', $settings['store_hours'] ?? 'Tuesday - Sunday, 08:30 - 17:00 (Closed Mondays)') }}" class="w-full text-xs h-9 px-3 bg-glass border border-hairline rounded-lg text-forest" />
                    </div>
                </div>
            </div>

            <!-- Section 2: Pricing, Delivery & Tax -->
            <div class="space-y-4">
                <h3 class="font-display font-bold text-sm text-forest uppercase tracking-wider pb-2 border-b border-hairline">
                    2. Pricing, Fulfillment &amp; Tax Rules
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[11px] font-mono uppercase text-forest-3 mb-1">Flat Delivery Fee ($ USD) *</label>
                        <input type="number" step="0.01" min="0" name="delivery_flat_fee" required value="{{ old('delivery_flat_fee', $settings['delivery_flat_fee'] ?? '15.00') }}" class="w-full text-xs h-9 px-3 bg-glass border border-hairline rounded-lg text-forest font-mono" />
                    </div>

                    <div>
                        <label class="block text-[11px] font-mono uppercase text-forest-3 mb-1">Tax Rate Percentage (%) *</label>
                        <input type="number" step="0.01" min="0" max="100" name="tax_rate_percent" required value="{{ old('tax_rate_percent', $settings['tax_rate_percent'] ?? '8.00') }}" class="w-full text-xs h-9 px-3 bg-glass border border-hairline rounded-lg text-forest font-mono" />
                    </div>

                    <div>
                        <label class="block text-[11px] font-mono uppercase text-forest-3 mb-1">Apply Tax to Shipping? *</label>
                        <select name="tax_shipping" class="w-full text-xs h-9 px-2 bg-glass border border-hairline rounded-lg text-forest">
                            <option value="true" {{ ($settings['tax_shipping'] ?? 'true') === 'true' ? 'selected' : '' }}>Yes (Tax Subtotal + Shipping)</option>
                            <option value="false" {{ ($settings['tax_shipping'] ?? 'true') === 'false' ? 'selected' : '' }}>No (Tax Items Only)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-mono uppercase text-forest-3 mb-1">Gift Claim Validity (Days) *</label>
                        <input type="number" min="1" max="365" name="gift_claim_validity_days" required value="{{ old('gift_claim_validity_days', $settings['gift_claim_validity_days'] ?? '30') }}" class="w-full text-xs h-9 px-3 bg-glass border border-hairline rounded-lg text-forest font-mono" />
                    </div>
                </div>
            </div>

            <!-- Section 3: Payment Gateways & Method Control -->
            <div class="space-y-4">
                <h3 class="font-display font-bold text-sm text-forest uppercase tracking-wider pb-2 border-b border-hairline">
                    3. Payment Gateways &amp; Expiration Windows
                </h3>

                <div class="space-y-3">
                    @foreach($methods as $method)
                        <div class="p-4 rounded-xl bg-glass border border-hairline flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="methods[{{ $method->code }}][is_enabled]" value="1" {{ $method->is_enabled ? 'checked' : '' }} class="rounded border-hairline text-ochre focus:ring-ochre w-4 h-4" />
                                <div>
                                    <p class="font-bold text-xs text-forest">{{ $method->name }}</p>
                                    <p class="text-[11px] text-forest-3 font-mono">Code: {{ $method->code }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <label class="text-[11px] font-mono uppercase text-forest-3">Expires After:</label>
                                <input type="number" name="methods[{{ $method->code }}][expires_after_minutes]" value="{{ $method->expires_after_minutes }}" min="5" max="10080" class="w-20 text-xs h-8 px-2 bg-paper border border-hairline rounded-lg text-forest font-mono text-center" />
                                <span class="text-xs text-forest-3 font-mono">minutes</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-hairline flex justify-end">
                <button type="submit" class="btn btn-primary text-xs !py-2.5 !px-6">
                    Save All Settings &rarr;
                </button>
            </div>
        </form>

    </div>

@endsection
