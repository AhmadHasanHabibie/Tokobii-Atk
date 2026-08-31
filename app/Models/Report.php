<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_id', 'order_item_id', 'product_id', 'report_type',
        'description', 'status', 'admin_reply', 'replied_by', 'replied_at',
    ];

    protected $casts = ['replied_at' => 'datetime'];

    /**
     * Get user-friendly Indonesian status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Tanggapan',
            'replied' => 'Sudah Dibalas',
            'resolved' => 'Selesai',
            default => ucfirst((string) $this->status),
        };
    }

    /**
     * Get Tokobii badge styling class for report status.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'tokobii-badge-warning',
            'replied' => 'tokobii-badge-info',
            'resolved' => 'tokobii-badge-success',
            default => 'tokobii-badge-neutral',
        };
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function orderItem(): BelongsTo { return $this->belongsTo(OrderItem::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function repliedBy(): BelongsTo { return $this->belongsTo(User::class, 'replied_by'); }
}
