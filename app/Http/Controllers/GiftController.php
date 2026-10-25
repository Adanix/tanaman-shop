<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Gift;
use App\Services\GardenService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GiftController extends Controller
{
    public function __construct(
        protected GardenService $gardenService
    ) {}

    public function show(string $token): View
    {
        $gift = Gift::where('token', $token)->with(['order.items.plant', 'claimedBy'])->firstOrFail();

        return view('gifts.show', compact('gift'));
    }

    public function claim(string $token): RedirectResponse
    {
        $gift = Gift::where('token', $token)->firstOrFail();
        $user = Auth::user();

        if (!$user) {
            session(['url.intended' => route('gift.show', ['token' => $token])]);
            return redirect()->route('login')->with('status', 'Please sign in or register with your email (' . $gift->recipient_email . ') to claim your plant gift.');
        }

        if (!config('app.demo_mode') && !$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')->with('error', 'Please verify your email address before claiming this plant gift.');
        }

        try {
            $gardens = $this->gardenService->adoptFromGift($gift, $user);
            $count = count($gardens);

            return redirect()->route('garden.index')
                ->with('status', "Your plant gift has been successfully unboxed! {$count} plant(s) have joined your Digital Garden.");
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
