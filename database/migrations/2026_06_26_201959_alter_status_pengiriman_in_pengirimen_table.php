<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pengiriman MODIFY COLUMN status_pengiriman VARCHAR(255) DEFAULT 'dikemas'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pengiriman MODIFY COLUMN status_pengiriman ENUM('dikemas', 'diproses', 'dikirim', 'selesai', 'dibatalkan') DEFAULT 'dikemas'");
    }
};
