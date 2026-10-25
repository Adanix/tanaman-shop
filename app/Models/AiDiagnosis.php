<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiDiagnosis extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_garden_id',
        'image_path',
        'status',
        'diagnosis',
        'severity',
        'ai_response',
        'recommended_plant_ids',
        'model',
    ];

    protected function casts(): array
    {
        return [
            'ai_response' => 'array',
            'recommended_plant_ids' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function garden(): BelongsTo
    {
        return $this->belongsTo(UserGarden::class, 'user_garden_id');
    }
}
