<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('face_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->longText('embedding')->nullable(); // Encrypted via Crypt::encryptString
            $table->longText('samples')->nullable();   // Encrypted multi-sample array
            $table->string('engine_version', 32)->default('human-v3');
            $table->boolean('is_active')->default(true);
            $table->boolean('needs_re_enroll')->default(false);
            $table->timestamp('enrolled_at')->nullable();
            $table->timestamp('last_verified_at')->nullable();
            $table->unsignedTinyInteger('failed_attempts')->default(0);
            $table->timestamp('locked_until')->nullable();
            $table->string('device_info')->nullable();
            $table->string('recovery_code', 64)->nullable(); // One-time recovery code
            $table->timestamp('recovery_code_used_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
        });

        // Migrate existing legacy face_verifications records marked as needs_re_enroll
        if (Schema::hasTable('face_verifications')) {
            $legacyRecords = DB::table('face_verifications')->get();
            foreach ($legacyRecords as $legacy) {
                DB::table('face_profiles')->insert([
                    'user_id' => $legacy->user_id,
                    'embedding' => null, // Legacy 128-D vector cannot be directly used by Human engine
                    'samples' => null,
                    'engine_version' => 'legacy-face-api-128',
                    'is_active' => (bool) $legacy->is_active,
                    'needs_re_enroll' => true,
                    'enrolled_at' => $legacy->enrolled_at,
                    'last_verified_at' => $legacy->last_verified_at,
                    'failed_attempts' => $legacy->failed_attempts,
                    'locked_until' => $legacy->locked_until,
                    'device_info' => $legacy->device_info,
                    'created_at' => $legacy->created_at ?? now(),
                    'updated_at' => $legacy->updated_at ?? now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('face_profiles');
    }
};
