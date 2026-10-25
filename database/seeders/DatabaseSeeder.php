<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Plant;
use App\Models\PlantImage;
use App\Models\StoreSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with complete PRD v2.1 initial dataset.
     */
    public function run(): void
    {
        // 1. Store Settings (PRD Section 1.3 & 9.2)
        $settings = [
            'store_name' => 'Plantify Official',
            'store_address' => 'Jl. Agrowisata Hijau No. 24, Kawasan Nursery Selatan',
            'store_city' => 'Jakarta Selatan',
            'store_postal_code' => '12190',
            'store_hours' => 'Tuesday - Sunday, 08:30 - 17:00 WIB',
            'store_phone' => '+62 21 555-7890',
            'store_timezone' => 'Asia/Jakarta',
            'currency_code' => 'USD',
            'delivery_flat_fee' => '15.00',
            'tax_rate_percent' => '8.00',
            'tax_shipping' => 'false',
            'gift_claim_validity_days' => '30',
            'bank_transfer_rejection_grace_minutes' => '720',
            'weather_cache_minutes' => '30',
        ];

        foreach ($settings as $key => $value) {
            StoreSetting::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        }

        // 2. Payment Methods (PRD Section 4.1 & 9.2)
        $methods = [
            ['code' => 'online_stripe', 'is_enabled' => true, 'expires_after_minutes' => 31],
            ['code' => 'bank_transfer', 'is_enabled' => true, 'expires_after_minutes' => 1440],
            ['code' => 'pay_in_store', 'is_enabled' => true, 'expires_after_minutes' => 2880],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(['code' => $method['code']], $method);
        }

        // 3. Bank Accounts (PRD Section 4.5 & 9.2)
        $accounts = [
            [
                'bank_name' => 'BCA (Bank Central Asia)',
                'account_number' => '8830-1928-44',
                'account_holder_name' => 'PT Plantify Botani Indonesia',
                'routing_or_swift_code' => 'CENAIDJA',
                'instructions' => 'Please include your order number in the payment transfer note and upload your transaction receipt.',
                'is_active' => true,
            ],
            [
                'bank_name' => 'Bank Mandiri',
                'account_number' => '137-00-982145-2',
                'account_holder_name' => 'PT Plantify Botani Indonesia',
                'routing_or_swift_code' => 'BMRIIDJA',
                'instructions' => 'Transfer the exact total amount and upload your digital transfer confirmation receipt.',
                'is_active' => true,
            ],
        ];

        foreach ($accounts as $acc) {
            BankAccount::updateOrCreate(['account_number' => $acc['account_number']], $acc);
        }

        // 4. Users (Admin and Verified Customer per PRD Section 2)
        // Store Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@plantify.test'],
            [
                'name' => 'Store Administrator',
                'password' => Hash::make('Plantify2026!'),
                'email_verified_at' => Carbon::now(),
                'phone' => '+62 811 0000 1111',
                'city' => 'Jakarta Selatan',
                'address' => 'Plantify Nursery Headquarters, Level 2',
                'postal_code' => '12190',
                'timezone' => 'Asia/Jakarta',
                'is_active' => true,
            ]
        );
        $admin->role = 'admin';
        $admin->save();

        // Verified Customer
        $customer = User::updateOrCreate(
            ['email' => 'customer@plantify.test'],
            [
                'name' => 'Sarah Anindita',
                'password' => Hash::make('Plantify2026!'),
                'email_verified_at' => Carbon::now(),
                'phone' => '+62 812 3456 7890',
                'city' => 'Jakarta Selatan',
                'address' => 'Jl. Senopati Raya No. 42',
                'postal_code' => '12190',
                'timezone' => 'Asia/Jakarta',
                'is_active' => true,
            ]
        );
        $customer->role = 'customer';
        $customer->save();

        // 5. Plant Categories (PRD Section 4.1)
        $categoriesData = [
            ['name' => 'Indoor Statement', 'slug' => 'indoor', 'icon' => 'home', 'description' => 'Architectural houseplants for living and work spaces.'],
            ['name' => 'Succulents & Cacti', 'slug' => 'succulents', 'icon' => 'sun', 'description' => 'Drought-tolerant, easy-care succulent varieties.'],
            ['name' => 'Bonsai & Topiary', 'slug' => 'bonsai', 'icon' => 'feather', 'description' => 'Artisanal miniature trees and cultivated sculptures.'],
            ['name' => 'Pet-Friendly', 'slug' => 'pet-friendly', 'icon' => 'shield', 'description' => 'Non-toxic, safe houseplants for cat and dog parents.'],
            ['name' => 'Low-Light Forgiving', 'slug' => 'low-light', 'icon' => 'moon', 'description' => 'Hardy species thriving in bedrooms and north-facing corners.'],
            ['name' => 'Outdoor Garden', 'slug' => 'outdoor', 'icon' => 'cloud', 'description' => 'Sun-loving shrubs and foliage for balconies and patios.'],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['slug']] = Category::updateOrCreate(['slug' => $c['slug']], $c);
        }

        // 6. Plants Catalog (PRD Section 3.1 & 8.1)
        $plantsData = [
            [
                'category_id' => $categories['indoor']->id,
                'sku' => 'PLN-MON-001',
                'name' => 'Monstera Deliciosa',
                'scientific_name' => 'Monstera deliciosa',
                'slug' => 'monstera-deliciosa',
                'description' => 'A statement houseplant beloved for its iconic split foliage. Three years nursery grown, strong self-standing stems, peat-free potting.',
                'price' => 42.00,
                'stock' => 15,
                'low_stock_threshold' => 3,
                'image_path' => 'https://images.unsplash.com/photo-1545241047-6083a3684587?w=700&q=72&auto=format&fit=crop',
                'light_requirement' => 'bright_indirect',
                'default_location_type' => 'indoor',
                'water_interval_days' => 7,
                'fertilize_interval_days' => 30,
                'temperature_range' => '18C - 28C',
                'difficulty_level' => 'beginner',
                'is_pet_friendly' => false,
                'co2_absorption_rate' => 4.80,
                'is_active' => true,
            ],
            [
                'category_id' => $categories['pet-friendly']->id,
                'sku' => 'PLN-CAL-002',
                'name' => 'Rattlesnake Calathea',
                'scientific_name' => 'Calathea lancifolia',
                'slug' => 'rattlesnake-calathea',
                'description' => 'Distinctive patterned leaves with deep burgundy undersides. Completely pet-safe and flourishes in humid, low-light spaces.',
                'price' => 34.00,
                'stock' => 12,
                'low_stock_threshold' => 4,
                'image_path' => 'https://images.unsplash.com/photo-1602923668104-8f9e03e77e62?w=700&q=72&auto=format&fit=crop',
                'light_requirement' => 'low',
                'default_location_type' => 'indoor',
                'water_interval_days' => 4,
                'fertilize_interval_days' => 30,
                'temperature_range' => '18C - 26C',
                'difficulty_level' => 'intermediate',
                'is_pet_friendly' => true,
                'co2_absorption_rate' => 3.20,
                'is_active' => true,
            ],
            [
                'category_id' => $categories['bonsai']->id,
                'sku' => 'PLN-FIC-003',
                'name' => 'Ginseng Ficus Bonsai',
                'scientific_name' => 'Ficus microcarpa',
                'slug' => 'ginseng-ficus-bonsai',
                'description' => 'Grown in our nursery for 4 years with robust exposed roots and compact crown. Sculptural and very long-lived.',
                'price' => 58.00,
                'stock' => 3,
                'low_stock_threshold' => 2,
                'image_path' => 'https://images.unsplash.com/photo-1512428813834-c702c7702b78?w=700&q=72&auto=format&fit=crop',
                'light_requirement' => 'direct_sun',
                'default_location_type' => 'indoor',
                'water_interval_days' => 5,
                'fertilize_interval_days' => 21,
                'temperature_range' => '16C - 28C',
                'difficulty_level' => 'intermediate',
                'is_pet_friendly' => false,
                'co2_absorption_rate' => 2.90,
                'is_active' => true,
            ],
            [
                'category_id' => $categories['succulents']->id,
                'sku' => 'PLN-HAW-004',
                'name' => 'Zebra Haworthia',
                'scientific_name' => 'Haworthiopsis attenuata',
                'slug' => 'zebra-haworthia',
                'description' => 'Architectural succulent with distinctive white pearled ridges. Tolerates missed waterings and completely pet-safe.',
                'price' => 18.00,
                'stock' => 25,
                'low_stock_threshold' => 5,
                'image_path' => 'https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=700&q=72&auto=format&fit=crop',
                'light_requirement' => 'medium',
                'default_location_type' => 'indoor',
                'water_interval_days' => 14,
                'fertilize_interval_days' => 60,
                'temperature_range' => '15C - 30C',
                'difficulty_level' => 'beginner',
                'is_pet_friendly' => true,
                'co2_absorption_rate' => 1.40,
                'is_active' => true,
            ],
            [
                'category_id' => $categories['succulents']->id,
                'sku' => 'PLN-MOO-005',
                'name' => 'Moon Cactus',
                'scientific_name' => 'Gymnocalycium mihanovichii',
                'slug' => 'moon-cactus',
                'description' => 'Vibrant grafted cactus in bright neon hues. Needs minimal water and thrives on warm sunny windowsills.',
                'price' => 14.00,
                'stock' => 30,
                'low_stock_threshold' => 5,
                'image_path' => 'https://images.unsplash.com/photo-1509937528035-ad76254b0356?w=700&q=72&auto=format&fit=crop',
                'light_requirement' => 'direct_sun',
                'default_location_type' => 'indoor',
                'water_interval_days' => 30,
                'fertilize_interval_days' => 90,
                'temperature_range' => '18C - 32C',
                'difficulty_level' => 'beginner',
                'is_pet_friendly' => false,
                'co2_absorption_rate' => 0.80,
                'is_active' => true,
            ],
            [
                'category_id' => $categories['succulents']->id,
                'sku' => 'PLN-ECH-006',
                'name' => 'Echeveria in Concrete',
                'scientific_name' => 'Echeveria elegans',
                'slug' => 'echeveria-concrete',
                'description' => 'Rosette-forming succulent potted in artisanal concrete with natural drainage. Pet-friendly and thrives in direct light.',
                'price' => 22.00,
                'stock' => 20,
                'low_stock_threshold' => 4,
                'image_path' => 'https://images.unsplash.com/photo-1483794344563-d27a8d18014e?w=700&q=72&auto=format&fit=crop',
                'light_requirement' => 'direct_sun',
                'default_location_type' => 'indoor',
                'water_interval_days' => 14,
                'fertilize_interval_days' => 60,
                'temperature_range' => '15C - 30C',
                'difficulty_level' => 'beginner',
                'is_pet_friendly' => true,
                'co2_absorption_rate' => 1.20,
                'is_active' => true,
            ],
            [
                'category_id' => $categories['low-light']->id,
                'sku' => 'PLN-BAM-007',
                'name' => 'Lucky Bamboo Spiral',
                'scientific_name' => 'Dracaena sanderiana',
                'slug' => 'lucky-bamboo',
                'description' => 'Cultivated in hydro stone media. Highly forgiving in shaded desks, bathrooms, and rooms with limited window exposure.',
                'price' => 16.00,
                'stock' => 18,
                'low_stock_threshold' => 4,
                'image_path' => 'https://images.unsplash.com/photo-1567331711402-509c12c41959?w=700&q=72&auto=format&fit=crop',
                'light_requirement' => 'low',
                'default_location_type' => 'indoor',
                'water_interval_days' => 7,
                'fertilize_interval_days' => 30,
                'temperature_range' => '18C - 28C',
                'difficulty_level' => 'beginner',
                'is_pet_friendly' => false,
                'co2_absorption_rate' => 1.80,
                'is_active' => true,
            ],
            [
                'category_id' => $categories['succulents']->id,
                'sku' => 'PLN-SUC-008',
                'name' => 'Succulent Trio Gift Set',
                'scientific_name' => 'Mixed species',
                'slug' => 'succulent-trio',
                'description' => 'A complementary trio of Haworthia, Echeveria, and Sedum in matched terracotta saucers. Excellent gift item.',
                'price' => 26.00,
                'stock' => 14,
                'low_stock_threshold' => 3,
                'image_path' => 'https://images.unsplash.com/photo-1459156212016-c812468e2115?w=700&q=72&auto=format&fit=crop',
                'light_requirement' => 'medium',
                'default_location_type' => 'indoor',
                'water_interval_days' => 14,
                'fertilize_interval_days' => 60,
                'temperature_range' => '15C - 30C',
                'difficulty_level' => 'beginner',
                'is_pet_friendly' => true,
                'co2_absorption_rate' => 2.60,
                'is_active' => true,
            ],
            [
                'category_id' => $categories['outdoor']->id,
                'sku' => 'PLN-BOX-009',
                'name' => 'Boxwood Topiary Ball',
                'scientific_name' => 'Buxus sempervirens',
                'slug' => 'boxwood-topiary',
                'description' => 'Hardy evergreen shrub sculpted into an elegant sphere. Designed for outdoor entrances, balconies, and terrace gardens.',
                'price' => 75.00,
                'stock' => 8,
                'low_stock_threshold' => 2,
                'image_path' => 'https://images.unsplash.com/photo-1520412099551-62b6bafeb5bb?w=700&q=72&auto=format&fit=crop',
                'light_requirement' => 'direct_sun',
                'default_location_type' => 'outdoor',
                'water_interval_days' => 2,
                'fertilize_interval_days' => 14,
                'temperature_range' => '10C - 35C',
                'difficulty_level' => 'intermediate',
                'is_pet_friendly' => false,
                'co2_absorption_rate' => 6.50,
                'is_active' => true,
            ],
            [
                'category_id' => $categories['indoor']->id,
                'sku' => 'PLN-EUC-010',
                'name' => 'Fresh Eucalyptus Stems',
                'scientific_name' => 'Eucalyptus cinerea',
                'slug' => 'eucalyptus-stems',
                'description' => 'Freshly harvested aromatic stems grown in nursery poly-tunnels. Natural refreshing scent and long vase-life.',
                'price' => 19.50,
                'stock' => 22,
                'low_stock_threshold' => 5,
                'image_path' => 'https://images.unsplash.com/photo-1466781783364-36c955e42a7f?w=700&q=72&auto=format&fit=crop',
                'light_requirement' => 'bright_indirect',
                'default_location_type' => 'indoor',
                'water_interval_days' => 5,
                'fertilize_interval_days' => 30,
                'temperature_range' => '16C - 26C',
                'difficulty_level' => 'beginner',
                'is_pet_friendly' => false,
                'co2_absorption_rate' => 2.10,
                'is_active' => true,
            ],
        ];

        foreach ($plantsData as $plantRecord) {
            $plant = Plant::updateOrCreate(['sku' => $plantRecord['sku']], $plantRecord);

            // Seed sample gallery image for PDP
            PlantImage::updateOrCreate(
                ['plant_id' => $plant->id, 'sort_order' => 1],
                ['image_path' => $plant->image_path]
            );
        }
    }
}
