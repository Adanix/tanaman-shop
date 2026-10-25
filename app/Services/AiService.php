<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AiChat;
use App\Models\AiDiagnosis;
use App\Models\AiUsageLog;
use App\Models\Plant;
use App\Models\StoreSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class AiService
{
    protected string $baseUrl;
    protected ?string $apiKey;
    protected string $visionModel;
    protected string $textModel;

    public function __construct()
    {
        $this->baseUrl = config('services.ciora.base_url') ?? env('CIORA_BASE_URL', 'https://ciora.id/v1');
        $this->apiKey = config('services.ciora.key') ?? env('CIORA_API_KEY');
        $this->visionModel = env('CIORA_MODEL_VISION', 'ciora-vision-free');
        $this->textModel = env('CIORA_MODEL_TEXT', 'ciora-ai-free');
    }

    /**
     * Check user's remaining quota for a given AI feature (PRD Section 7.1 & 7.2).
     */
    public function getRemainingQuota(User $user, string $feature): int
    {
        $timezone = $user->timezone ?: (string) StoreSetting::get('store_timezone', 'Asia/Jakarta');
        $localToday = Carbon::now($timezone)->toDateString();

        $startUtc = Carbon::parse($localToday . ' 00:00:00', $timezone)->setTimezone('UTC');
        $endUtc = Carbon::parse($localToday . ' 23:59:59', $timezone)->setTimezone('UTC');

        if (in_array($feature, ['plant_doctor', 'plant_matchmaker'], true)) {
            // Shared pool of 5/day
            $maxLimit = 5;
            $used = AiUsageLog::where('user_id', $user->id)
                ->whereIn('feature', ['plant_doctor', 'plant_matchmaker'])
                ->whereBetween('created_at', [$startUtc, $endUtc])
                ->count();
        } elseif ($feature === 'care_chat') {
            $maxLimit = 30;
            $used = AiUsageLog::where('user_id', $user->id)
                ->where('feature', 'care_chat')
                ->whereBetween('created_at', [$startUtc, $endUtc])
                ->count();
        } elseif ($feature === 'admin_care_guide') {
            $maxLimit = 50;
            $used = AiUsageLog::where('user_id', $user->id)
                ->where('feature', 'admin_care_guide')
                ->whereBetween('created_at', [$startUtc, $endUtc])
                ->count();
        } else {
            $maxLimit = 0;
            $used = 0;
        }

        return max(0, $maxLimit - $used);
    }

    /**
     * Reserve and log quota atomicity before executing AI call (PRD Section 7.2).
     */
    public function reserveQuota(User $user, string $feature): AiUsageLog
    {
        return DB::transaction(function () use ($user, $feature) {
            $remaining = $this->getRemainingQuota($user, $feature);
            if ($remaining <= 0) {
                throw new RuntimeException("Daily quota for {$feature} has been exhausted. Resets at midnight in your timezone.");
            }

            return AiUsageLog::create([
                'request_uuid' => (string) Str::uuid(),
                'user_id' => $user->id,
                'feature' => $feature,
                'model' => ($feature === 'plant_doctor') ? $this->visionModel : $this->textModel,
                'status' => 'queued',
            ]);
        });
    }

    /**
     * Run AI Plant Doctor diagnosis on uploaded photo (PRD Section 5.2).
     *
     * @return array{diagnosis: string, severity: string, cause: string, rescue_steps: array<string>, recommended_products: array<int>}
     */
    public function diagnosePlantPhoto(AiDiagnosis $diagnosisRecord, string $imageB64): array
    {
        $log = AiUsageLog::where('request_uuid', $diagnosisRecord->id)->first() ??
            AiUsageLog::where('user_id', $diagnosisRecord->user_id)->where('status', 'queued')->latest()->first();

        // Demo Mode / Fallback Mock Diagnosis
        if (config('app.demo_mode') || empty($this->apiKey)) {
            $recIds = Plant::where('is_active', true)->where('stock', '>', 0)->take(3)->pluck('id')->toArray();
            $demoDiagnosis = [
                'diagnosis' => 'Early Chlorosis & Moisture Imbalance (Monstera / Araceae Foliage)',
                'severity' => 'mild',
                'cause' => 'Slight root saturation due to high potting density combined with lower ambient indirect light.',
                'rescue_steps' => [
                    'Allow the top 2 to 3 inches of potting medium to dry thoroughly before hydrating again.',
                    'Relocate specimen 3 feet nearer to an east or south-facing window with diffused canopy sunlight.',
                    'Aerate soil gently using a sanitized bamboo skewer to restore capillary root respiration.',
                    'Mist leaves during morning hours only if indoor relative humidity drops below 50%.'
                ],
                'recommended_plant_ids' => $recIds,
            ];

            $diagnosisRecord->update([
                'status' => 'completed',
                'diagnosis' => $demoDiagnosis['diagnosis'],
                'severity' => $demoDiagnosis['severity'],
                'ai_response' => $demoDiagnosis,
                'recommended_plant_ids' => $demoDiagnosis['recommended_plant_ids'],
                'model' => 'demo-botanical-vision',
            ]);

            if ($log) {
                $log->update(['status' => 'completed', 'latency_ms' => 320]);
            }

            return $demoDiagnosis;
        }

        $startTime = microtime(true);

        // Fetch candidate active products for cross-selling verification
        $activeProducts = Plant::where('is_active', true)->where('stock', '>', 0)->take(15)->get();
        $catalogContext = $activeProducts->map(fn($p) => "ID: {$p->id}, Name: {$p->name}")->implode('; ');

        $systemPrompt = "You are Plantify AI Plant Doctor. Analyze the uploaded plant leaf/foliage image for diseases, pests, or physiological issues. "
            . "CRITICAL RULES: Output strictly valid JSON. Language MUST be US English (en-US). DO NOT use any Unicode emoji characters. "
            . "Output format: {\"diagnosis\": string, \"severity\": \"mild\"|\"moderate\"|\"severe\", \"cause\": string, \"rescue_steps\": [string], \"recommended_plant_ids\": [integer]}. "
            . "Only select recommended_plant_ids from this catalog: [{$catalogContext}].";

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(45)
                ->retry(2, 800, throw: false)
                ->post($this->baseUrl . '/chat/completions', [
                    'model' => $this->visionModel,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        [
                            'role' => 'user',
                            'content' => [
                                ['type' => 'text', 'text' => 'Please diagnose this plant leaf issue and provide rescue steps.'],
                                ['type' => 'image_url', 'image_url' => ['url' => "data:image/jpeg;base64,{$imageB64}"]],
                            ],
                        ],
                    ],
                    'temperature' => 0.3,
                    'max_tokens' => 800,
                ]);

            $latency = (int) round((microtime(true) - $startTime) * 1000);

            if (!$response->successful()) {
                throw new RuntimeException('AI Gateway error: ' . $response->status());
            }

            $body = $response->json();
            $rawContent = $body['choices'][0]['message']['content'] ?? '{}';

            // Clean markdown code blocks if wrapped
            $rawContent = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($rawContent));
            $parsed = json_decode($rawContent, true) ?? [];

            $result = [
                'diagnosis' => $parsed['diagnosis'] ?? 'Leaf Chlorosis / Overwatering Symptoms',
                'severity' => $parsed['severity'] ?? 'mild',
                'cause' => $parsed['cause'] ?? 'Early moisture retention or insufficient indirect lighting.',
                'rescue_steps' => $parsed['rescue_steps'] ?? [
                    'Allow the top 2 inches of soil to completely dry before watering.',
                    'Move plant to a brighter location with indirect sunlight.',
                    'Check pot drainage holes to avoid stagnant root moisture.',
                ],
                'recommended_plant_ids' => array_values(array_intersect(
                    $parsed['recommended_plant_ids'] ?? [],
                    $activeProducts->pluck('id')->toArray()
                )),
            ];

            $diagnosisRecord->update([
                'status' => 'completed',
                'diagnosis' => $result['diagnosis'],
                'severity' => $result['severity'],
                'ai_response' => $result,
                'recommended_plant_ids' => $result['recommended_plant_ids'],
                'model' => $this->visionModel,
            ]);

            $log?->update([
                'status' => 'completed',
                'latency_ms' => $latency,
                'prompt_tokens' => $body['usage']['prompt_tokens'] ?? null,
                'completion_tokens' => $body['usage']['completion_tokens'] ?? null,
            ]);

            return $result;
        } catch (\Throwable $e) {
            $latency = (int) round((microtime(true) - $startTime) * 1000);
            Log::error('AI Plant Doctor failure', ['error' => $e->getMessage()]);

            $diagnosisRecord->update(['status' => 'failed']);
            $log?->update([
                'status' => 'failed',
                'latency_ms' => $latency,
                'error_code' => Str::limit($e->getMessage(), 100),
            ]);

            throw new RuntimeException('AI Plant Doctor is currently unavailable. Please try again later.');
        }
    }

    /**
     * Send message to Contextual Plant Care Chatbot (PRD Section 5.4).
     */
    public function chatWithBot(User $user, string $message, ?Plant $plant = null): string
    {
        $log = $this->reserveQuota($user, 'care_chat');

        // Demo Mode / Fallback Mock Chatbot
        if (config('app.demo_mode') || empty($this->apiKey)) {
            $lower = strtolower($message);
            $plantName = $plant ? $plant->name : 'your houseplant';
            if (str_contains($lower, 'water') || str_contains($lower, 'wet') || str_contains($lower, 'dry')) {
                $reply = "For {$plantName}, check the top 2 inches of soil before adding water. Only irrigate when the soil feels crumbly and dry to the touch. Ensure excess water drains freely through bottom drainage holes.";
            } elseif (str_contains($lower, 'light') || str_contains($lower, 'sun')) {
                $reply = "{$plantName} thrives best in bright, indirect sunlight. Avoid placing it in harsh afternoon direct sun, which can scorch the leaves, while keeping it out of completely dark corners.";
            } elseif (str_contains($lower, 'fertiliz') || str_contains($lower, 'feed')) {
                $reply = "Feed {$plantName} with a balanced liquid organic plant food diluted to half strength once a month during spring and summer. Withhold fertilizer during autumn and winter dormancy.";
            } elseif (str_contains($lower, 'yellow') || str_contains($lower, 'brown') || str_contains($lower, 'dying') || str_contains($lower, 'leaf') || str_contains($lower, 'leaves')) {
                $reply = "Yellowing leaves typically indicate overwatering or root moisture stress. Allow the soil mix to dry out significantly, prune fully brown leaves with sterile shears, and inspect drainage.";
            } else {
                $reply = "Hello! I am your Plantify Botanical Assistant. For {$plantName}, maintaining regular watering intervals, steady temperatures between 18 to 26 degrees Celsius, and filtered natural light will ensure lush, vibrant growth.";
            }

            AiChat::create([
                'user_id' => $user->id,
                'plant_id' => $plant?->id,
                'role' => 'user',
                'message' => $message,
                'request_uuid' => $log->request_uuid,
            ]);

            AiChat::create([
                'user_id' => $user->id,
                'plant_id' => $plant?->id,
                'role' => 'assistant',
                'message' => $reply,
                'request_uuid' => $log->request_uuid,
            ]);

            $log->update(['status' => 'completed', 'latency_ms' => 150]);

            return $reply;
        }

        $startTime = microtime(true);

        // Build context from plant if present
        $plantContext = $plant
            ? "Plant: {$plant->name} ({$plant->scientific_name}). Light: {$plant->light_requirement}, Water interval: every {$plant->water_interval_days} days, Pet safe: " . ($plant->is_pet_friendly ? 'yes' : 'no')
            : "General plant care consultation.";

        $systemPrompt = "You are Plantify Care Assistant. Answer plant care questions accurately, concisely, and warmly. "
            . "CRITICAL RULES: Strict US English (en-US). DO NOT use any Unicode emoji characters. "
            . "Context: {$plantContext}.";

        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(30)
                ->post($this->baseUrl . '/chat/completions', [
                    'model' => $this->textModel,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $message],
                    ],
                    'temperature' => 0.4,
                    'max_tokens' => 500,
                ]);

            $latency = (int) round((microtime(true) - $startTime) * 1000);

            if (!$response->successful()) {
                throw new RuntimeException('AI Gateway error: ' . $response->status());
            }

            $body = $response->json();
            $reply = trim((string) ($body['choices'][0]['message']['content'] ?? 'I could not generate an answer at this moment.'));

            // Remove any unintended emojis per PRD 0.2
            $reply = preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $reply);

            // Save chat history
            AiChat::create([
                'user_id' => $user->id,
                'plant_id' => $plant?->id,
                'role' => 'user',
                'message' => $message,
                'request_uuid' => $log->request_uuid,
            ]);

            AiChat::create([
                'user_id' => $user->id,
                'plant_id' => $plant?->id,
                'role' => 'assistant',
                'message' => $reply,
                'request_uuid' => $log->request_uuid,
            ]);

            $log->update([
                'status' => 'completed',
                'latency_ms' => $latency,
                'prompt_tokens' => $body['usage']['prompt_tokens'] ?? null,
                'completion_tokens' => $body['usage']['completion_tokens'] ?? null,
            ]);

            return $reply;
        } catch (\Throwable $e) {
            $latency = (int) round((microtime(true) - $startTime) * 1000);
            $log->update([
                'status' => 'failed',
                'latency_ms' => $latency,
                'error_code' => Str::limit($e->getMessage(), 100),
            ]);

            return "The AI assistant is temporarily busy. Please ensure your plant receives adequate indirect light and well-draining soil, or try asking again in a few moments.";
        }
    }
}
