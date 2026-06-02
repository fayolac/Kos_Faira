<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservasi', function (Blueprint $table) {
            $table->bigIncrements('id_reservasi');
            $table->foreignId('id_penyewa');
            $table->foreignId('id_kamar');
            $table->datetime('tanggal_reservasi');
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable();
            $table->enum('status', ['Menunggu', 'Aktif', 'Nonaktif', 'Ditolak'])
                  ->default('Menunggu');
            $table->text('catatan')->nullable();
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();

            $table->foreign('id_penyewa')
                  ->references('id_penyewa')
                  ->on('penyewa')
                  ->onDelete('cascade');

            $table->foreign('id_kamar')
                  ->references('id_kamar')
                  ->on('kamar')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasi');
    }
};
