<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status_pesanan VARCHAR(255) DEFAULT 'Menunggu Pembayaran'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status_pesanan ENUM('Menunggu Pembayaran', 'Menunggu konfirmasi penjual', 'Pesanan ditolak', 'Pesanan sedang diproses', 'Pesanan dalam pengiriman', 'Pesanan selesai') DEFAULT 'Menunggu Pembayaran'");
    }
};
