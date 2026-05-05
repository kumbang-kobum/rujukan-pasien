<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasMedis extends Model
{
    use HasFactory;

    protected $table = 'berkas_medis';

    protected $fillable = [
        'kunjungan_id','soap_id','kategori','nama_file','path','mime','uploader_id'
    ];

    public function soap()
    { 
        return $this->belongsTo(SOAP::class, 'soap_id'); 
    }

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class, 'kunjungan_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }
}