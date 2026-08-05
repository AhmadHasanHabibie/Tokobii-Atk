<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'invoice_number',
        'payment_method',
        'payment_status',
        'amount',
        'proof_of_payment',
        'payment_date',
        'verified_by_admin_id',
        'verified_at',
        'reject_reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the order associated with the payment.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the admin user who verified/rejected the payment.
     */
    public function verifiedByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_admin_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods & Accessors
    |--------------------------------------------------------------------------
    */

    public function isCash(): bool
    {
        return $this->payment_method === 'cash';
    }

    public function isQris(): bool
    {
        return $this->payment_method === 'qris';
    }

    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    public function isWaitingVerification(): bool
    {
        return $this->payment_status === 'waiting_verification';
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isReadyForPickup(): bool
    {
        return $this->payment_status === 'ready_for_pickup';
    }

    public function isCompleted(): bool
    {
        return $this->payment_status === 'completed';
    }

    public function isRejected(): bool
    {
        return $this->payment_status === 'rejected';
    }

    /**
     * Pickup Receipt uses the Order Invoice Number directly.
     */
    public function getPickupReceiptNumberAttribute(): string
    {
        return $this->invoice_number;
    }
}
