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
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

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
     *
     * Tokobii displays prices in whole rupiah. Parse the stored DECIMAL value
     * as a string so rounding to that displayed value cannot introduce a
     * floating-point fraction into cash change calculations.
     */
    public function getGrandTotalInRupiahAttribute(): int
    {
        [$whole, $fraction] = array_pad(
            explode('.', (string) $this->getRawOriginal('grand_total'), 2),
            2,
            '0'
        );

        return (int) $whole + ((int) substr(str_pad($fraction, 2, '0'), 0, 2) >= 50 ? 1 : 0);
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
