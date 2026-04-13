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
        Schema::create('pesanans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('makanan_id')->constrained('makanans')->onDelete('cascade');
        $table->string('nama_pemesan');
        $table->string('nomor_telepon');
        $table->enum('status', ['menunggu', 'diproses', 'stok_habis'])->default('menunggu');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
