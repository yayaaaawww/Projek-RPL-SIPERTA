<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panen extends Model
{
    protected $table = 'panen';
    protected $fillable = [
        'tanaman_id', 'nama_komoditas', 'jumlah_kg',
        'harga_per_kg', 'foto', 'lokasi_lahan', 'status'
    ];

    public function tanaman() { return $this->belongsTo(Tanaman::class); }
    public function pesanan() { return $this->hasMany(Pesanan::class); }
}