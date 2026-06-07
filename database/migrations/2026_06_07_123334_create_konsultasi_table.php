<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konsultasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('petani_id');
            $table->unsignedBigInteger('ahli_id')->nullable();
            $table->foreign('petani_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ahli_id')->references('id')->on('users')->onDelete('set null');
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('foto')->nullable();
            $table->string('kategori_tanaman')->nullable();
            $table->text('jawaban')->nullable();
            $table->enum('status', ['sent', 'in_review', 'answered', 'closed'])->default('sent');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konsultasi');
    }
};