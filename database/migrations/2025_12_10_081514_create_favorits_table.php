<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('favorits', function (Blueprint $table) {
        $table->id();
        $table->string('id_user'); 
        $table->string('produk_id');  // jika PK produk kamu bertipe string
        $table->timestamps();

        $table->foreign('produk_id')
              ->references('id_produk') // SESUAIKAN DENGAN TABEL KAMU
              ->on('produks')
              ->onDelete('cascade');
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorits');
    }
};
