<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id();

            // FK ke users (string 25)
            $table->string('user_id', 25);

            // FK ke produks (string 25)
            $table->string('produk_id', 25);

            $table->integer('qty')->default(1);
            $table->timestamps();

            // Foreign key ke users
            $table->foreign('user_id')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');

            // Foreign key ke produks
            $table->foreign('produk_id')
                ->references('id_produk')
                ->on('produks')
                ->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keranjang');
    }
};

