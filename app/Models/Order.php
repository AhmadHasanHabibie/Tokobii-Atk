<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'invoice_number',
        'order_date',
        'order_status',
        'payment_status',
        'payment_method',
        'subtotal',
        'shipping_cost',
        'grand_total',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order_date' => 'datetime',
        'subtotal' => 'integer',
        'shipping_cost' => 'integer',
        'grand_total' => 'integer',
    ];

    /**
     * Get the single unified order status.
     * Source of truth for order lifecycle display across all admin and customer views.
     */
    public function getStatusAttribute(): string
    {
        if ($this->order_status === 'completed') {
            return 'completed';
        }
        if ($this->order_status === 'cancelled' || $this->payment_status === 'rejected') {
            return 'cancelled';
        }
        if ($this->order_status === 'ready_for_pickup') {
            return 'ready_for_pickup';
        }
        if ($this->order_status === 'processing') {
            return 'processing';
        }
        if ($this->payment_status === 'paid' || $this->order_status === 'paid') {
            return 'paid';
        }
        if ($this->payment_status === 'waiting_verification' || $this->order_status === 'waiting_verification') {
            return 'waiting_verification';
        }

        return 'waiting_payment';
    }

    /**
     * Get the unified human-readable Indonesian label for the order status.
     * Single source of truth for presentation across all roles & views.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'Selesai',
            'ready_for_pickup' => 'Siap Diambil',
            'processing', 'paid' => 'Sedang Diproses',
            'waiting_verification' => 'Menunggu Verifikasi',
            'cancelled', 'rejected' => 'Dibatalkan',
            default => 'Menunggu Pembayaran',
        };
    }

    /**
     * Get the unified badge CSS class for the order status.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'tokobii-badge-success',
            'ready_for_pickup' => 'tokobii-badge-info',
            'processing', 'paid' => 'tokobii-badge-info',
            'waiting_verification' => 'tokobii-badge-warning',
            'cancelled', 'rejected' => 'tokobii-badge-danger',
            default => 'tokobii-badge-warning',
        };
    }

    /**
     * Get the customer user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Alias relationship for customer user.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the items for the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the payment associated with the order.
     */
    public function payment(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get the payment proof file path from the related payment.
     */
    public function getPaymentProofAttribute(): ?string
    {
        return $this->payment?->proof_of_payment;
    }

    /**
     * Get the proof of payment file path from the related payment.
     */
    public function getProofOfPaymentAttribute(): ?string
    {
        return $this->payment?->proof_of_payment;
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Return the payable total in whole rupiah for cash transactions.
     */
    public function getGrandTotalInRupiahAttribute(): int
    {
        return (int) $this->grand_total;
    }

    /**
     * Helper to generate a unique invoice number format (e.g. INV-20260805-0001).
     */
    public static function generateInvoiceNumber(): string
    {
        $datePrefix = date('Ymd');
        $prefix = 'INV-' . $datePrefix . '-';

        $lastOrder = self::where('invoice_number', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder) {
            $lastSequence = (int) substr($lastOrder->invoice_number, -4);
            $sequence = str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $sequence = '0001';
        }

        return $prefix . $sequence;
    }
}
