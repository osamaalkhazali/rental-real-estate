<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apartment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'location_text',
        'location_link',
        'floor_plan',
        'ownership_document',
        'important_files',
        'apartment_number',
        'floor_number',
        'bedrooms',
        'living_rooms',
        'guest_rooms',
        'kitchens',
        'bathrooms',
        'balconies',
        'master_rooms',
        'square_meters',
        'rent_price',
        'location',
        'description',
        'furniture',
        'notes',
        'is_available',
    ];

    protected $appends = [
        'display_name',
    ];

    protected function casts(): array
    {
        return [
            'is_available' => 'boolean',
            'square_meters' => 'decimal:2',
            'rent_price' => 'decimal:2',
            'important_files' => 'array',
            'furniture' => 'array',
        ];
    }

    public function leases(): HasMany
    {
        return $this->hasMany(Lease::class);
    }

    public function waterServices(): HasMany
    {
        return $this->hasMany(WaterService::class);
    }

    public function electricServices(): HasMany
    {
        return $this->hasMany(ElectricService::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class)->orderBy('order');
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->name && $this->apartment_number) {
            return sprintf('%s (%s)', $this->name, $this->apartment_number);
        }

        return $this->name ?? $this->apartment_number ?? 'N/A';
    }
}
