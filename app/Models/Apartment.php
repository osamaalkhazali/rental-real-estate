<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
        'purchase_price',
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
            'purchase_price' => 'decimal:2',
            'important_files' => 'array',
            'furniture' => 'array',
        ];
    }

    public function leases(): HasMany
    {
        return $this->hasMany(Lease::class);
    }

    public function leasePayments(): HasManyThrough
    {
        return $this->hasManyThrough(LeasePayment::class, Lease::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function waterServices(): HasMany
    {
        return $this->hasMany(WaterService::class);
    }

    public function electricServices(): HasMany
    {
        return $this->hasMany(ElectricService::class);
    }

    public function waterReadings(): HasManyThrough
    {
        return $this->hasManyThrough(WaterReading::class, WaterService::class);
    }

    public function electricReadings(): HasManyThrough
    {
        return $this->hasManyThrough(ElectricReading::class, ElectricService::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class)->orderBy('order');
    }

    public function getTotalRentIncomeAttribute(): float
    {
        if ($this->relationLoaded('leasePayments')) {
            $sum = $this->leasePayments
                ->whereIn('status', ['paid', 'partial'])
                ->sum('amount_paid');

            return round((float) $sum, 2);
        }

        $sum = $this->leasePayments()
            ->whereIn('status', ['paid', 'partial'])
            ->sum('amount_paid');

        return round((float) $sum, 2);
    }

    public function getTotalGeneralExpensesAttribute(): float
    {
        if ($this->relationLoaded('expenses')) {
            return round((float) $this->expenses->sum('amount'), 2);
        }

        return round((float) $this->expenses()->sum('amount'), 2);
    }

    public function getTotalOwnerUtilityExpensesAttribute(): float
    {
        $waterTotal = $this->calculateOwnerUtilityTotal('waterServices', 'waterReadings');
        $electricTotal = $this->calculateOwnerUtilityTotal('electricServices', 'electricReadings');

        return round($waterTotal + $electricTotal, 2);
    }

    public function getTotalIncomeAttribute(): float
    {
        return $this->total_rent_income;
    }

    public function getTotalExpensesAttribute(): float
    {
        return round($this->total_general_expenses + $this->total_owner_utility_expenses, 2);
    }

    public function getRoiAttribute(): ?float
    {
        $purchasePrice = $this->purchase_price === null ? null : (float) $this->purchase_price;

        if ($purchasePrice === null || $purchasePrice <= 0.0) {
            return null;
        }

        $netProfit = $this->total_income - $this->total_expenses;

        return round(($netProfit / $purchasePrice) * 100, 2);
    }

    public function getTotalIncomeFormattedAttribute(): string
    {
        return '$' . number_format($this->total_income, 2);
    }

    public function getTotalExpensesFormattedAttribute(): string
    {
        return '$' . number_format($this->total_expenses, 2);
    }

    public function getRoiFormattedAttribute(): string
    {
        return $this->roi === null ? 'N/A' : number_format($this->roi, 2) . '%';
    }

    private function calculateOwnerUtilityTotal(string $serviceRelation, string $readingRelation): float
    {
        if ($this->relationLoaded($serviceRelation)) {
            $services = $this->{$serviceRelation};

            $total = $services
                ->filter(fn ($service) => $service->lease_id === null)
                ->sum(function ($service) use ($readingRelation) {
                    if ($service->relationLoaded($readingRelation)) {
                        return (float) $service->{$readingRelation}->sum('cost');
                    }

                    return (float) $service->{$readingRelation}()->sum('cost');
                });

            return round((float) $total, 2);
        }

        if ($readingRelation === 'waterReadings') {
            $sum = $this->waterReadings()
                ->whereNull('water_services.lease_id')
                ->sum('cost');

            return round((float) $sum, 2);
        }

        $sum = $this->electricReadings()
            ->whereNull('electric_services.lease_id')
            ->sum('cost');

        return round((float) $sum, 2);
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->name && $this->apartment_number) {
            return sprintf('%s (%s)', $this->name, $this->apartment_number);
        }

        return $this->name ?? $this->apartment_number ?? 'N/A';
    }
}
