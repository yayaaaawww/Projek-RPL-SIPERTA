<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perawatan extends Model
{
    protected $table = 'perawatan';
    protected $fillable = [
        'tanaman_id', 'tanggal_perawatan', 'penyiraman',
        'pemupukan', 'penyiangan', 'pestisida', 'catatan', 'foto', 'status'
    ];

    public function tanaman() { return $this->belongsTo(Tanaman::class); }
}
