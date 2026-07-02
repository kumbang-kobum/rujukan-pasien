<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BerkasMedis extends Model
{
    use HasFactory;

    protected $table = 'berkas_medis';

    protected $fillable = [
        'kunjungan_id','soap_id','kategori','nama_file','path','uploader_id'
    ];

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        return $query->whereHas('kunjungan', function (Builder $kunjungan) use ($user) {
            $kunjungan->where(function (Builder $q) use ($user) {
                $q->where('rumah_sakit_id', $user->rumah_sakit_id)
                  ->orWhereHas('rujukan', function (Builder $r) use ($user) {
                      $r->where('rumah_sakit_tujuan_id', $user->rumah_sakit_id)
                        ->where('status', 'diterima');
                  });
            });
        });
    }

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
