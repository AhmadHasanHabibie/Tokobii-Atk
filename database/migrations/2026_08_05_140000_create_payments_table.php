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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('invoice_number', 50);
            $table->enum('payment_method', ['cash', 'qris'])->default('cash');
            $table->enum('payment_status', [
                'pending',
                'waiting_verification',
                'paid',
                'ready_for_pickup',
                'completed',
                'rejected'
            ])->default('pending');
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('proof_of_payment', 255)->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->foreignId('verified_by_admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('reject_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
