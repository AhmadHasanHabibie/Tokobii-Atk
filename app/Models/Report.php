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

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function orderItem(): BelongsTo { return $this->belongsTo(OrderItem::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function repliedBy(): BelongsTo { return $this->belongsTo(User::class, 'replied_by'); }
}
