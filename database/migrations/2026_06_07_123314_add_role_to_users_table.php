<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['petani', 'ahli', 'pedagang', 'admin'])->default('petani');
            $table->string('no_hp')->nullable();
            $table->string('alamat')->nullable();
            $table->string('no_rekening')->nullable();
            $table->string('bidang')->nullable();
            $table->enum('status', ['aktif', 'suspended'])->default('aktif');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'no_hp', 'alamat', 'no_rekening', 'bidang', 'status']);
        });
    }
};