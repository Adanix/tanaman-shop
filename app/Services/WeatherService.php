<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\StoreSetting;
use App\Models\WeatherCache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    /**
     * Check if outdoor watering should be deferred for a given city due to rain/high humidity (PRD Section 6.4).
     *
     * @return array{should_defer: bool, reason: ?string, weather_desc: ?string, humidity: ?int}
     */
    public function checkWateringCondition(string $city): array
    {
        $city = trim($city);
        if ($city === '') {
            return ['should_defer' => false, 'reason' => null, 'weather_desc' => null, 'humidity' => null];
        }

        $cacheMinutes = (int) StoreSetting::get('weather_cache_minutes', '30');
        $cache = WeatherCache::where('city', strtolower($city))->first();

        $payload = null;
        if ($cache && $cache->fetched_at->diffInMinutes(Carbon::now()) < $cacheMinutes) {
            $payload = $cache->payload;
        } else {
            $payload = $this->fetchFromProvider($city);
            if ($payload) {
                WeatherCache::updateOrCreate(
                    ['city' => strtolower($city)],
                    ['payload' => $payload, 'fetched_at' => Carbon::now()]
                );
            }
        }

        if (!$payload) {
            // Fail-open: do not defer if weather service unavailable
            return ['should_defer' => false, 'reason' => 'Weather data unavailable (fail-open)', 'weather_desc' => null, 'humidity' => null];
        }

        $weatherMain = $payload['weather'][0]['main'] ?? '';
        $weatherDesc = $payload['weather'][0]['description'] ?? '';
        $humidity = (int) ($payload['main']['humidity'] ?? 0);

        // PRD 6.4: Defer if Rain, Thunderstorm, or Humidity > 80%
        $isRainOrStorm = in_array(strtolower($weatherMain), ['rain', 'thunderstorm', 'drizzle'], true);
        $isHighHumidity = $humidity > 80;

        if ($isRainOrStorm) {
            return [
                'should_defer' => true,
                'reason' => "Rain or storm forecast ({$weatherDesc})",
                'weather_desc' => $weatherDesc,
                'humidity' => $humidity,
            ];
        }

        if ($isHighHumidity) {
            return [
                'should_defer' => true,
                'reason' => "High ambient humidity ({$humidity}%)",
                'weather_desc' => $weatherDesc,
                'humidity' => $humidity,
            ];
        }

        return [
            'should_defer' => false,
            'reason' => null,
            'weather_desc' => $weatherDesc,
            'humidity' => $humidity,
        ];
    }

    /**
     * Fetch weather from OpenWeatherMap API without exposing keys in logs.
     */
    protected function fetchFromProvider(string $city): ?array
    {
        $apiKey = config('services.openweather.key') ?? env('OPENWEATHER_API_KEY');
        if (!$apiKey) {
            return null;
        }

        try {
            $response = Http::timeout(8)
                ->get('https://api.openweathermap.org/data/2.5/weather', [
                    'q' => $city,
                    'appid' => $apiKey,
                    'units' => 'metric',
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('OpenWeatherMap returned non-success response', [
                'city' => $city,
                'status' => $response->status(),
            ]);
            return null;
        } catch (\Throwable $e) {
            Log::warning('OpenWeatherMap request failed (fail-open)', [
                'city' => $city,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
