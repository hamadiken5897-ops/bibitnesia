<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('id_user', 25);
            $table->text('pesan');
            $table->timestamps();

            // Opsional: FK
            // $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_conversations');
    }
};
