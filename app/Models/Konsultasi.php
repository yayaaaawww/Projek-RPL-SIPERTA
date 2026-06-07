<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    protected $fillable = [
        'petani_id', 'ahli_id', 'judul', 'deskripsi',
        'foto', 'kategori_tanaman', 'jawaban', 'status'
    ];

    public function petani() { return $this->belongsTo(User::class, 'petani_id'); }
    public function ahli() { return $this->belongsTo(User::class, 'ahli_id'); }
    public function chat() { return $this->hasMany(ChatKonsultasi::class); }
}
