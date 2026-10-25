<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\UserGarden;
use App\Services\GardenService;
use App\Services\WeatherService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GardenController extends Controller
{
    public function __construct(
        protected GardenService $gardenService,
        protected WeatherService $weatherService
    ) {}

    public function index(): View
    {
        $user = Auth::user();
        $gardens = UserGarden::where('user_id', $user->id)
            ->with('plant')
            ->orderBy('next_watering_at', 'asc')
            ->get();

        $totalPlants = $gardens->count();
        $now = Carbon::now();

        $wateringDueCount = $gardens->filter(fn($g) => $g->next_watering_at && $g->next_watering_at->isPast())->count();
        $totalCo2Absorbed = $gardens->sum(fn($g) => (float) ($g->plant?->co2_absorption_rate ?? 0));

        // Check weather condition for outdoor plants
        $weatherAlert = null;
        if (!empty($user->city) && $gardens->contains(fn($g) => $g->isOutdoor())) {
            $weather = $this->weatherService->checkWateringCondition($user->city);
            if ($weather['should_defer']) {
                $weatherAlert = [
                    'city' => $user->city,
                    'reason' => $weather['reason'],
                    'weather_desc' => $weather['weather_desc'],
                    'humidity' => $weather['humidity'],
                ];
            }
        }

        return view('pages.garden', compact('gardens', 'totalPlants', 'wateringDueCount', 'totalCo2Absorbed', 'weatherAlert'));
    }

    public function water(int $id): RedirectResponse
    {
        $user = Auth::user();
        $garden = UserGarden::where('user_id', $user->id)->where('id', $id)->firstOrFail();

        $this->gardenService->markWatered($garden);

        return back()->with('status', "Recorded watering for '{$garden->nickname}'. Next watering scheduled.");
    }

    public function fertilize(int $id): RedirectResponse
    {
        $user = Auth::user();
        $garden = UserGarden::where('user_id', $user->id)->where('id', $id)->firstOrFail();

        $this->gardenService->markFertilized($garden);

        return back()->with('status', "Recorded fertilizing for '{$garden->nickname}'. Next fertilizing scheduled.");
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $user = Auth::user();
        $garden = UserGarden::where('user_id', $user->id)->where('id', $id)->firstOrFail();

        $validated = $request->validate([
            'nickname' => ['nullable', 'string', 'max:120'],
            'location_type' => ['required', 'in:indoor,outdoor'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $garden->update($validated);

        return back()->with('status', 'Plant details updated successfully.');
    }
}
