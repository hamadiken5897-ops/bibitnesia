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
        if (Schema::hasColumn('komplains', 'id_produk')) {
            Schema::table('komplains', function (Blueprint $table) {
                $table->dropForeign(['id_produk']);
                $table->dropColumn(['id_produk', 'id_ulasan', 'kategori_laporan']);
            });
        }
        
        Schema::table('komplains', function (Blueprint $table) {
            $table->string('id_produk', 25)->nullable()->after('id_terlapor');
            $table->unsignedBigInteger('id_ulasan')->nullable()->after('id_produk');
            $table->string('kategori_laporan', 100)->nullable()->after('id_ulasan');
            $table->string('judul_laporan', 255)->nullable()->change();
            $table->text('deskripsi_laporan')->nullable()->change();

            $table->foreign('id_produk')->references('id_produk')->on('produks')->onDelete('set null');
            $table->foreign('id_ulasan')->references('id')->on('ulasans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komplains', function (Blueprint $table) {
            $table->dropForeign(['id_produk']);
            $table->dropForeign(['id_ulasan']);
            $table->dropColumn(['id_produk', 'id_ulasan', 'kategori_laporan']);
            $table->string('judul_laporan', 255)->nullable(false)->change();
            $table->text('deskripsi_laporan')->nullable(false)->change();
        });
    }
};
