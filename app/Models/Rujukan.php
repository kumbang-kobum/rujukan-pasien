<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rujukan extends Model
{
    use HasFactory;

    protected $table = 'rujukan';
    protected $fillable = [
        'kunjungan_id',
        'rumah_sakit_asal_id',
        'rumah_sakit_tujuan_id',
        'dokter_tujuan_id',
        'alasan',
        'alasan_rujukan',
        'catatan',
        'status',
        'penerima_id',
        'catatan_penerima',
    ];

    protected $casts = [
    'rumah_sakit_asal_id'   => 'integer',
    'rumah_sakit_tujuan_id' => 'integer',
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class, 'kunjungan_id');
    }

    public function rsAsal()
    {
        return $this->belongsTo(RumahSakit::class, 'rumah_sakit_asal_id');
    }

    public function rsTujuan()
    {
        return $this->belongsTo(RumahSakit::class, 'rumah_sakit_tujuan_id');
    }

    public function dokterTujuan()
    {
        return $this->belongsTo(User::class, 'dokter_tujuan_id');
    }

    public function penerima()
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }
}