<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tanaman_id');
            $table->foreign('tanaman_id')->references('id')->on('tanaman')->onDelete('cascade');
            $table->string('nama_komoditas');
            $table->decimal('jumlah_kg', 10, 2);
            $table->decimal('harga_per_kg', 15, 2);
            $table->string('foto')->nullable();
            $table->string('lokasi_lahan')->nullable();
            $table->enum('status', ['listed', 'available', 'sold_out', 'archived'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panen');
    }
};