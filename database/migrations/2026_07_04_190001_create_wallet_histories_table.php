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
        Schema::create('wallet_histories', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 25);
            $table->decimal('jumlah', 15, 2);
            $table->enum('tipe', ['masuk', 'keluar']);
            $table->string('deskripsi', 255)->nullable();
            $table->timestamps();

            // Opsional: Jika ingin memastikan referential integrity
            // $table->foreign('user_id')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_histories');
    }
};
