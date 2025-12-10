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
        Schema::table('notifikasi_user', function (Blueprint $table) {

            // Hanya tambah FK jika belum ada
            if (!Schema::hasColumn('notifikasi_user', 'id_user')) return;

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('notifikasi_user', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
        });
    }
};
