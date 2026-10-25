@extends('admin.layout')

@section('title', 'Bank Transfer Verifications — Plantify Official')

@section('admin_content')

    <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-hairline">
            <div>
                <h2 class="font-display font-bold text-lg text-forest">Manual Bank Transfer Queue</h2>
                <p class="text-xs text-forest-3 mt-0.5">Review submitted customer payment slips, verify account transfers, and approve or reject with 12h grace</p>
            </div>
            <span class="badge badge-low text-xs">
                {{ $orders->count() }} Awaiting Verification
            </span>
        </div>

        @if($orders->isEmpty())
            <div class="py-12 text-center text-forest-3 font-mono text-xs">
                No bank transfer payments currently waiting for verification. All clear!
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                    @php
                        $signedProofUrl = $order->payment_proof_path ? \Illuminate\Support\Facades\URL::temporarySignedRoute(
                            'admin.proof.view',
                            \Carbon\Carbon::now()->addMinutes(30),
                            ['path' => urlencode($order->payment_proof_path)]
                        ) : null;
                    @endphp
                    <div class="p-5 rounded-2xl bg-glass border border-hairline space-y-4" x-data="{ openReject: false }">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                            <div>
                                <span class="text-[11px] font-mono text-forest-4 uppercase">Submitted: {{ $order->payment_submitted_at?->format('M d, Y - H:i') ?? 'Recent' }}</span>
                                <h3 class="font-display font-bold text-base text-forest mt-0.5">
                                    <a href="{{ route('admin.orders.detail', ['id' => $order->id]) }}" class="hover:text-ochre">
                                        {{ $order->order_number }}
                                    </a>
                                    &bull; <span class="font-normal font-sans">{{ $order->customer_name }}</span>
                                </h3>
                                <p class="text-xs text-forest-3 font-mono mt-0.5">
                                    Target Bank: <strong>{{ $order->bankAccount->bank_name ?? 'Any Official Account' }}</strong> &bull; Expected Amount: <strong class="text-forest font-bold">${{ number_format($order->total_amount, 2) }}</strong>
                                </p>
                                @if($order->sender_bank_name || $order->sender_account_name)
                                    <p class="text-xs text-forest-4 mt-1">
                                        Customer Claims: Sent from <strong>{{ $order->sender_bank_name ?? 'Bank' }}</strong> (a.n. {{ $order->sender_account_name ?? 'Customer' }})
                                    </p>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 flex-wrap">
                                @if($signedProofUrl)
                                    <a href="{{ $signedProofUrl }}" target="_blank" class="btn btn-ghost !text-xs !py-1.5 !px-3 font-mono">
                                        View Receipt Proof &rarr;
                                    </a>
                                @endif

                                <!-- Approve Form -->
                                <form method="POST" action="{{ route('admin.verifications.approve', ['id' => $order->id]) }}" onsubmit="return confirm('Approve transfer payment for order {{ $order->order_number }}?');">
                                    @csrf
                                    <button type="submit" class="btn btn-primary text-xs !py-1.5 !px-4">
                                        Approve Payment
                                    </button>
                                </form>

                                <!-- Reject Button -->
                                <button type="button" @click="openReject = !openReject" class="btn btn-ghost !text-xs text-sale border-sale/30 hover:bg-sale-wash !py-1.5 !px-3">
                                    Reject (12h Grace)
                                </button>
                            </div>
                        </div>

                        <!-- Reject Drawer -->
                        <div x-show="openReject" x-cloak class="p-4 bg-paper rounded-xl border border-sale/30 space-y-3 text-xs">
                            <form method="POST" action="{{ route('admin.verifications.reject', ['id' => $order->id]) }}">
                                @csrf
                                <label class="block font-semibold text-sale mb-1">Reason for Rejection * (sent to customer email with 12h re-upload window)</label>
                                <textarea name="reason" required minlength="10" rows="2" class="w-full text-xs p-2.5 bg-glass border border-hairline rounded-lg text-forest" placeholder="e.g. The transferred amount was $10 short. Please transfer the remaining balance and re-upload proof..."></textarea>
                                <div class="flex justify-end gap-2 mt-2">
                                    <button type="button" @click="openReject = false" class="btn btn-ghost !text-xs !py-1 !px-3">Cancel</button>
                                    <button type="submit" class="btn btn-primary bg-sale hover:bg-sale !text-xs !py-1 !px-4">Confirm Rejection</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

@endsection
