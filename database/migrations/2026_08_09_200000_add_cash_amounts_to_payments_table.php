<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('received_amount', 12, 2)->nullable()->after('amount');
            $table->decimal('change_amount', 12, 2)->nullable()->after('received_amount');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['received_amount', 'change_amount']);
        });
    }
};
