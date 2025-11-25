<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lease extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'apartment_id',
        'tenant_name',
        'tenant_phone',
        'tenant_email',
        'tenant_national_id',
        'tenant_image',
        'national_id_image',
        'lease_document',
        'start_date',
        'end_date',
        'rent_amount',
        'payment_status',
        'deposit_amount',
        'documents',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'rent_amount' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'documents' => 'array',
        ];
    }

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }

    public function leasePayments(): HasMany
    {
        return $this->hasMany(LeasePayment::class);
    }
}
