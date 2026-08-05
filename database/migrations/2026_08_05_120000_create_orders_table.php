<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('invoice_number', 50)->unique();
            $table->timestamp('order_date')->useCurrent();
            $table->enum('order_status', [
                'pending',
                'processing',
                'ready_for_pickup',
                'completed',
                'cancelled'
            ])->default('pending');
            $table->enum('payment_status', [
                'pending',
                'waiting_verification',
                'paid',
                'rejected'
            ])->default('pending');
            $table->enum('payment_method', [
                'cash',
                'qris'
            ])->default('cash');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
