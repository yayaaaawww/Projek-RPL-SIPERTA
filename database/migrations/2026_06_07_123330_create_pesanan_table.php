<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('panen_id');
            $table->unsignedBigInteger('pedagang_id');
            $table->unsignedBigInteger('petani_id');
            $table->foreign('panen_id')->references('id')->on('panen')->onDelete('cascade');
            $table->foreign('pedagang_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('petani_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('jumlah_beli', 10, 2);
            $table->decimal('total_harga', 15, 2);
            $table->string('bukti_transfer')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'validated', 'preparing', 'ready', 'completed', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};