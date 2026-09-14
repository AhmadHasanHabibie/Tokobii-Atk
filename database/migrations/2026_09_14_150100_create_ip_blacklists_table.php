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
        Schema::create('ip_blacklists', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->unique();
            $table->string('reason');
            $table->text('user_agent')->nullable();
            $table->unsignedInteger('hit_count')->default(1);
            $table->boolean('is_permanent')->default(true);
            $table->timestamp('blocked_until')->nullable();
            $table->string('blocked_by')->default('honeypot_system');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_blacklists');
    }
};
