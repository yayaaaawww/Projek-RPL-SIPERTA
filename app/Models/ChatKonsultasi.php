<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatKonsultasi extends Model
{
    protected $table = 'chat_konsultasi';
    protected $fillable = ['konsultasi_id', 'pengirim_id', 'pesan', 'status'];

    public function konsultasi() { return $this->belongsTo(Konsultasi::class); }
    public function pengirim() { return $this->belongsTo(User::class, 'pengirim_id'); }
}