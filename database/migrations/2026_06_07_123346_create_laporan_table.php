<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('pelapor_id');
            $table->unsignedBigInteger('terlapor_id');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('pelapor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('terlapor_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('jenis_pelapor', ['petani', 'ahli', 'pedagang']);
            $table->text('alasan');
            $table->string('bukti')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'resolved'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};