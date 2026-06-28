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
        Schema::create('pengaturan_pembayarans', function (Blueprint $table) {
            $table->id();
            
            // Rekening Bank Utama
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_owner')->nullable();
            
            // Dompet Elektronik (Opsional)
            $table->string('ewallet_name')->nullable();
            $table->string('ewallet_phone')->nullable();
            $table->string('ewallet_owner')->nullable();
            
            // Midtrans
            $table->boolean('midtrans_is_active')->default(false);
            $table->string('midtrans_server_key')->nullable();
            $table->string('midtrans_client_key')->nullable();
            
            // Tema Kartu (Untuk estetika UI)
            $table->string('card_theme')->default('blue'); // 'blue', 'dark', 'gold', dll

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_pembayarans');
    }
};
