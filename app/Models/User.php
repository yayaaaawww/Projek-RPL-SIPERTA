<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'no_hp', 'alamat', 'no_rekening', 'bidang', 'status'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function tanaman() { return $this->hasMany(Tanaman::class); }
    public function konsultasiSebagaiPetani() { return $this->hasMany(Konsultasi::class, 'petani_id'); }
    public function konsultasiSebagaiAhli() { return $this->hasMany(Konsultasi::class, 'ahli_id'); }
    public function pesananSebagaiPedagang() { return $this->hasMany(Pesanan::class, 'pedagang_id'); }
    public function pesananSebagaiPetani() { return $this->hasMany(Pesanan::class, 'petani_id'); }
    public function laporan() { return $this->hasMany(Laporan::class, 'pelapor_id'); }
}
