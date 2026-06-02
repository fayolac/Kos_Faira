<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fasilitas', function (Blueprint $table) {
            $table->bigIncrements('id_fasilitas');
            $table->string('nama_fasilitas', 100);
            $table->string('ikon', 50)->nullable();
            $table->string('deskripsi', 255)->nullable();
            $table->enum('tipe', ['Bersama', 'Pribadi']);
            $table->string('foto', 255)->nullable();
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fasilitas');
    }
};
