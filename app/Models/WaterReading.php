<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaterReading extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'water_service_id',
        'reading_date',
        'reading_value',
        'cost',
        'image_path',
        'is_paid',
    ];

    protected function casts(): array
    {
        return [
            'reading_date' => 'date',
            'reading_value' => 'decimal:2',
            'cost' => 'decimal:2',
            'is_paid' => 'boolean',
        ];
    }

    public function waterService(): BelongsTo
    {
        return $this->belongsTo(WaterService::class);
    }
}
