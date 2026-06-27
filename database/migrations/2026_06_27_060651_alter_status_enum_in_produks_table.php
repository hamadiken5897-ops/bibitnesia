<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE produks MODIFY COLUMN status ENUM('tersedia', 'tidak_tersedia', 'habis', 'hidden', 'dihapus_admin') DEFAULT 'tersedia'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE produks MODIFY COLUMN status ENUM('tersedia', 'habis') DEFAULT 'tersedia'");
    }
};
