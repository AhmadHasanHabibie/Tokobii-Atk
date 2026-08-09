<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('report_type', 50);
            $table->text('description');
            $table->enum('status', ['pending', 'replied', 'resolved'])->default('pending');
            $table->text('admin_reply')->nullable();
            $table->foreignId('replied_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();

            // A customer can report a product only once per order.
            $table->unique(['user_id', 'order_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
