<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatTransaksi extends Model
{
    protected $table = 'chat_transaksi';
    protected $fillable = ['pesanan_id', 'pengirim_id', 'pesan', 'status'];

    public function pesanan() { return $this->belongsTo(Pesanan::class); }
    public function pengirim() { return $this->belongsTo(User::class, 'pengirim_id'); }
}