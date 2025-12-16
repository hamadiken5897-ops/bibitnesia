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
        Schema::table('penjuals', function (Blueprint $table) {
            $table->unsignedBigInteger('id_provinsi')
                ->nullable()
                ->after('id_user');
        });

        Schema::table('penjuals', function (Blueprint $table) {
            $table->foreign('id_provinsi')
                ->references('id_provinsi')
                ->on('provinsis')
                ->nullOnDelete();
        });;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjuals', function (Blueprint $table) {
            //
        });
    }
};
