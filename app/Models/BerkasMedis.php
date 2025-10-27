<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasMedis extends Model
{
    use HasFactory;

    protected $table = 'berkas_medis';

    protected $fillable = [
        'kunjungan_id',
        'uploader_id',
        'jenis',
        'nama_file',
        'path',
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }
}