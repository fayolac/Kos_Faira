<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->bigIncrements('id_pengaduan');
            $table->foreignId('id_reservasi');
            $table->string('judul', 150);
            $table->text('keluhan');
            $table->string('foto', 255)->nullable();
            $table->datetime('tanggal_pengaduan');
            $table->enum('status', ['Diajukan', 'Diproses', 'Selesai'])
                  ->default('Diajukan');
            $table->text('tanggapan_admin')->nullable();
            $table->datetime('tanggal_update')->nullable();
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();

            $table->foreign('id_reservasi')
                  ->references('id_reservasi')
                  ->on('reservasi')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};
