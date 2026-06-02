<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamar', function (Blueprint $table) {
            $table->bigIncrements('id_kamar');
            $table->string('nomor_kamar', 10)->unique();
            $table->enum('tipe_kamar', ['Basic', 'Plus']);
            $table->decimal('harga', 10, 2);
            $table->string('ukuran_kamar', 50)->nullable();
            $table->enum('status', ['Tersedia', 'Terisi', 'Nonaktif'])->default('Tersedia');
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};
