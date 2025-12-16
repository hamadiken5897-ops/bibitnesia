<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pembayarans', function (Blueprint $table) {

            // VA
            $table->string('va_bank', 50)->nullable()->after('metode_pembayaran');
            $table->string('va_nomor', 50)->nullable()->after('va_bank');

            // Status lebih lengkap
            $table->enum('status_validasi', [
                'pending',
                'menunggu_validasi',
                'dibayar',
                'expired',
                'dibatalkan'
            ])->default('pending')->change();

            // Waktu & bukti
            $table->dateTime('expired_at')->nullable()->after('total_bayar');
            $table->dateTime('tanggal_pembayaran')->nullable()->change();
            $table->string('bukti_pembayaran')->nullable()->after('tanggal_pembayaran');
            $table->dateTime('tgl_validasi')->nullable()->change();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn([
                'va_bank',
                'va_nomor',
                'expired_at',
                'bukti_pembayaran'
            ]);
        });
    }
};
