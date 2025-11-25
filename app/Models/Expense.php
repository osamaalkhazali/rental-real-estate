<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'apartment_id',
        'lease_id',
        'type',
        'title',
        'description',
        'expense_date',
        'amount',
        'vendor_name',
        'receipt_file',
        'attachments',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'expense_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }

    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }
}
