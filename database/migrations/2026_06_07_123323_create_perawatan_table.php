<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perawatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tanaman_id');
            $table->foreign('tanaman_id')->references('id')->on('tanaman')->onDelete('cascade');
            $table->date('tanggal_perawatan');
            $table->boolean('penyiraman')->default(false);
            $table->boolean('pemupukan')->default(false);
            $table->boolean('penyiangan')->default(false);
            $table->boolean('pestisida')->default(false);
            $table->text('catatan')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['in_progress', 'submitted', 'locked'])->default('submitted');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perawatan');
    }
};