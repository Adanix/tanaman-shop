<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Plant;
use App\Services\MoneyService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function __construct(
        protected MoneyService $moneyService
    ) {}

    public function home(): View
    {
        $categories = Category::where('is_active', true)->withCount('plants')->get();
        $featuredPlants = Plant::where('is_active', true)->where('stock', '>', 0)->latest()->take(4)->get();
        $easyCarePlants = Plant::where('is_active', true)
            ->where('difficulty_level', 'beginner')
            ->where('stock', '>', 0)
            ->take(4)
            ->get();

        return view('pages.home', compact('categories', 'featuredPlants', 'easyCarePlants'));
    }

    public function shop(Request $request): View
    {
        $query = Plant::where('is_active', true)->with('category');

        // Filter by category slug
        if ($request->filled('category')) {
            $catSlug = $request->input('category');
            $query->whereHas('category', fn($q) => $q->where('slug', $catSlug));
        }

        // Search text
        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('scientific_name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Light requirement filter
        if ($request->filled('light')) {
            $query->where('light_requirement', $request->input('light'));
        }

        // Difficulty level filter
        if ($request->filled('difficulty')) {
            $query->where('difficulty_level', $request->input('difficulty'));
        }

        // Pet-friendly filter
        if ($request->boolean('pet_safe')) {
            $query->where('is_pet_friendly', true);
        }

        // Sorting
        $sort = $request->input('sort', 'featured');
        match ($sort) {
            'price-asc' => $query->orderBy('price', 'asc'),
            'price-desc' => $query->orderBy('price', 'desc'),
            'name' => $query->orderBy('name', 'asc'),
            default => $query->latest(),
        };

        $plants = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->withCount('plants')->get();

        return view('pages.shop', compact('plants', 'categories'));
    }

    public function product(string $slug = 'monstera-deliciosa'): View
    {
        $plant = Plant::where('slug', $slug)->where('is_active', true)->with(['category', 'images'])->firstOrFail();
        $relatedPlants = Plant::where('category_id', $plant->category_id)
            ->where('id', '!=', $plant->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('pages.product', compact('plant', 'relatedPlants'));
    }

    public function about(): View
    {
        return view('pages.about');
    }
}
