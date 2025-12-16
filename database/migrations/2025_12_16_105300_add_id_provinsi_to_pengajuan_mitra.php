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
        Schema::table('pengajuan_mitra', function (Blueprint $table) {
            $table->unsignedBigInteger('id_provinsi')->after('id_user');
            $table->foreign('id_provinsi')
                ->references('id_provinsi')
                ->on('provinsis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_mitra', function (Blueprint $table) {
            //
        });
    }
};
