<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $fillable = [
        'admin_id', 'pelapor_id', 'terlapor_id',
        'jenis_pelapor', 'alasan', 'bukti', 'status'
    ];

    public function admin() { return $this->belongsTo(User::class, 'admin_id'); }
    public function pelapor() { return $this->belongsTo(User::class, 'pelapor_id'); }
    public function terlapor() { return $this->belongsTo(User::class, 'terlapor_id'); }
}
