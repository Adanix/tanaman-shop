<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AiDiagnosis;
use App\Models\Plant;
use App\Services\AiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AiController extends Controller
{
    public function __construct(
        protected AiService $aiService
    ) {}

    public function carePage(): View
    {
        $user = Auth::user();
        $doctorQuota = $user ? $this->aiService->getRemainingQuota($user, 'plant_doctor') : 0;
        $chatQuota = $user ? $this->aiService->getRemainingQuota($user, 'care_chat') : 0;

        $recentDiagnoses = $user ? AiDiagnosis::where('user_id', $user->id)->latest()->take(3)->get() : collect();

        return view('pages.care', compact('doctorQuota', 'chatQuota', 'recentDiagnoses', 'user'));
    }

    public function diagnose(Request $request): JsonResponse|RedirectResponse
    {
        $user = Auth::user();
        if (!$user || (!config('app.demo_mode') && !$user->hasVerifiedEmail())) {
            return response()->json([
                'error' => 'Please verify your email address to access AI Plant Doctor diagnosis.',
            ], 403);
        }

        $validated = $request->validate([
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'user_garden_id' => ['nullable', 'integer', 'exists:user_gardens,id'],
        ]);

        try {
            // Check quota and reserve log
            $log = $this->aiService->reserveQuota($user, 'plant_doctor');

            // Store on private disk (PRD Section 0.2 & 7.3)
            $path = $request->file('image')->store('ai_diagnoses', 'local');
            $imageB64 = base64_encode(file_get_contents($request->file('image')->getRealPath()));

            $diagnosisRecord = AiDiagnosis::create([
                'user_id' => $user->id,
                'user_garden_id' => $validated['user_garden_id'] ?? null,
                'image_path' => $path,
                'status' => 'processing',
            ]);

            $result = $this->aiService->diagnosePlantPhoto($diagnosisRecord, $imageB64);

            return response()->json([
                'success' => true,
                'diagnosis' => $result,
                'remaining_quota' => $this->aiService->getRemainingQuota($user, 'plant_doctor'),
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function chat(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user || (!config('app.demo_mode') && !$user->hasVerifiedEmail())) {
            return response()->json([
                'error' => 'Please verify your email address to use the care assistant chatbot.',
            ], 403);
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'plant_id' => ['nullable', 'integer', 'exists:plants,id'],
        ]);

        try {
            $plant = !empty($validated['plant_id']) ? Plant::find($validated['plant_id']) : null;
            $reply = $this->aiService->chatWithBot($user, trim($validated['message']), $plant);

            return response()->json([
                'reply' => $reply,
                'remaining_quota' => $this->aiService->getRemainingQuota($user, 'care_chat'),
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
