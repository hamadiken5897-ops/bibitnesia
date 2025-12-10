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

            if (!Schema::hasColumn('notifikasi_user', 'id_user')) {
                $table->string('id_user', 25)->after('id');
            }

            if (!Schema::hasColumn('notifikasi_user', 'judul')) {
                $table->string('judul')->after('id_user');
            }

            if (!Schema::hasColumn('notifikasi_user', 'pesan')) {
                $table->text('pesan')->after('judul');
            }

            if (!Schema::hasColumn('notifikasi_user', 'redirect_url')) {
                $table->string('redirect_url')->nullable()->after('pesan');
            }

            if (!Schema::hasColumn('notifikasi_user', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('redirect_url');
            }
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifikasi_user', function (Blueprint $table) {
            //
        });
    }
};
