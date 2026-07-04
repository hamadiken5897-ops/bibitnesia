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
        // Karena Laravel kadang butuh doctrine/dbal untuk change() ENUM, 
        // lebih aman menggunakan raw SQL statement.
        DB::statement("ALTER TABLE penarikan_saldos MODIFY COLUMN role ENUM('penjual', 'kurir', 'pembeli') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan seperti semula
        DB::statement("ALTER TABLE penarikan_saldos MODIFY COLUMN role ENUM('penjual', 'kurir') NOT NULL");
    }
};
