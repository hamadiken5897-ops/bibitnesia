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
        Schema::table('pesanans', function (Blueprint $table) {
            $table->text('alamat')->after('id_user');
            $table->unsignedBigInteger('provinsi')->after('alamat');
        });
    }
    
    public function down()
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'provinsi']);
        });
    }
    
};
