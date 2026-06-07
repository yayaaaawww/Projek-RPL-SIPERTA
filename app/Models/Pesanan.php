<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    
    protected $fillable = [
        'panen_id', 'pedagang_id', 'petani_id',
        'jumlah_beli', 'total_harga', 'bukti_transfer', 'catatan', 'status'
    ];

    public function panen() { return $this->belongsTo(Panen::class); }
    public function pedagang() { return $this->belongsTo(User::class, 'pedagang_id'); }
    public function petani() { return $this->belongsTo(User::class, 'petani_id'); }
    public function chatTransaksi() { return $this->hasMany(ChatTransaksi::class); }
}