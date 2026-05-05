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
        'tipe',
        'pesan',
    ];

    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class, 'konsultasi_id');
    }

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }

    public static function typeLabels(): array
    {
        return [
            'pesan' => 'Pesan / Diskusi',
            'jawaban' => 'Jawaban Klinis',
            'minta_info' => 'Minta Info Tambahan',
        ];
    }
}
