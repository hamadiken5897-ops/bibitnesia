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
        Schema::create('pesan_cs', function (Blueprint $table) {
            $table->id('id_pesan_cs');
            $table->string('id_user');
            $table->string('id_admin')->nullable();
            $table->text('pesan');
            $table->boolean('is_read_admin')->default(false);
            $table->boolean('is_read_user')->default(false);
            $table->string('sender_role')->default('user'); // 'user' or 'admin'
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_admin')->references('id_user')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesan_cs');
    }
};
