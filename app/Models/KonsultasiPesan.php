<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonsultasiPesan extends Model
{
    use HasFactory;

    protected $table = 'konsultasi_pesan';

    protected $fillable = [
        'konsultasi_id',
        'pengirim_id',
        'jenis_pesan',
        'isi_pesan',
        'status',
        'dibaca_at',
    ];

    protected $casts = [
        'dibaca_at' => 'datetime',
    ];

    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class, 'konsultasi_id');
    }

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }
}
