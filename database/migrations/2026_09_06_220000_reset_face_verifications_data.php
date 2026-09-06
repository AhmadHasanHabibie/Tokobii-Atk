<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Reset semua data biometrik wajah agar Admin & Owner
     * mendaftar ulang (re-enroll) menggunakan logika JS yang sudah dioptimalkan.
     */
    public function up(): void
    {
        // 1. Hapus semua record face enrollment
        DB::table('face_verifications')->truncate();

        // 2. Reset flag di semua users
        DB::table('users')->update([
            'face_verification_enabled' => false,
        ]);
    }

    /**
     * Reverse the migrations.
     * (Tidak bisa mengembalikan data yang sudah terhapus)
     */
    public function down(): void
    {
        // Data biometrik yang sudah dihapus tidak bisa dikembalikan.
        // Admin/Owner perlu re-enroll secara manual.
    }
};
