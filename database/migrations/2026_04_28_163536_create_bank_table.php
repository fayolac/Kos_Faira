<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank', function (Blueprint $table) {
            $table->bigIncrements('id_bank');
            $table->string('nama_bank', 50);
            $table->string('atas_nama', 100);
            $table->string('nomor_rekening', 30);
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank');
    }
};
