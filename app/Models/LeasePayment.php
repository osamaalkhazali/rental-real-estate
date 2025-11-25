<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeasePayment extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lease_id',
        'month',
        'due_date',
        'amount_due',
        'amount_paid',
        'payment_date',
        'status',
        'receipt_file',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'payment_date' => 'date',
            'amount_due' => 'decimal:2',
            'amount_paid' => 'decimal:2',
        ];
    }

    /**
     * Get the lease that owns the payment.
     */
    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }
}
