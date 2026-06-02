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
        Schema::create('foto_kamar', function (Blueprint $table) {
            $table->bigIncrements('id_foto');
            $table->foreignId('id_kamar');
            $table->string('foto', 255);
            $table->integer('urutan')->default(1);
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();

            $table->foreign('id_kamar')
                  ->references('id_kamar')
                  ->on('kamar')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foto_kamar');
    }
};
