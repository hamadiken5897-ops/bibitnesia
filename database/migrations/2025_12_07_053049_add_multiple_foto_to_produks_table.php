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
        Schema::table('produks', function (Blueprint $table) {
            $table->string('foto_produk1')->after('deskripsi'); // wajib
            $table->string('foto_produk2')->nullable()->after('foto_produk1');
            $table->string('foto_produk3')->nullable()->after('foto_produk2');
        });
    }

    public function down()
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropColumn(['foto_produk1', 'foto_produk2', 'foto_produk3']);
        });
    }
};
