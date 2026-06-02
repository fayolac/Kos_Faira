<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->bigIncrements('id_pengeluaran');
            $table->enum('kategori', ['Wifi', 'Gas', 'Air', 'Listrik','Sampah', 'Pemeliharaan', 'Lainnya']);
            $table->string('keterangan', 255)->nullable();
            $table->decimal('jumlah', 10, 2);
            $table->date('tanggal');
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};
