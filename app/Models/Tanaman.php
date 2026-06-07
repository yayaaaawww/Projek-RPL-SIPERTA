<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tanaman extends Model
{
    protected $table = 'tanaman';
    protected $fillable = ['user_id', 'jenis_tanaman', 'nama_lahan', 'alamat_lahan', 'status'];

    public function petani() { return $this->belongsTo(User::class, 'user_id'); }
    public function perawatan() { return $this->hasMany(Perawatan::class); }
    public function panen() { return $this->hasMany(Panen::class); }
}
