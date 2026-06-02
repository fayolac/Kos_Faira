<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->bigIncrements('id_pembayaran');
            $table->foreignId('id_reservasi');
            $table->foreignId('id_bank');
            $table->enum('tipe_pembayaran', ['Reservasi', 'Perpanjangan']);
            $table->date('bulan_tagihan');
            $table->decimal('jumlah', 10, 2);
            $table->string('bukti_transfer', 255)->nullable();
            $table->datetime('tanggal_bayar')->nullable();
            $table->datetime('tanggal_konfirmasi')->nullable();
            $table->enum('status', ['Dikirim', 'Ditolak', 'Diterima'])->default('Dikirim');
            $table->text('catatan_admin')->nullable();
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();

            $table->foreign('id_reservasi')
                  ->references('id_reservasi')
                  ->on('reservasi')
                  ->onDelete('cascade');

            $table->foreign('id_bank')
                  ->references('id_bank')
                  ->on('bank')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
