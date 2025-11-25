<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'apartment_id',
        'path',
        'title',
        'caption',
        'is_primary',
        'type',
        'original_name',
        'size',
        'mime_type',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'order' => 'integer',
            'is_primary' => 'boolean',
        ];
    }

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
