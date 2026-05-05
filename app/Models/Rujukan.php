<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

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

    public function scopeVisibleTo($q, User $user)
    {
        // // Admin boleh melihat semua
        // if ($user->role === 'admin') return $q;

        $rsId = (int) $user->rumah_sakit_id;

        return $q->where(function ($qq) use ($rsId) {
            $qq->where('rumah_sakit_asal_id', $rsId)
               ->orWhere('rumah_sakit_tujuan_id', $rsId);
        });
    }
    
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
    
    public function dokterCc()
    {
        return $this->belongsToMany(User::class, 'rujukan_dokter_cc', 'rujukan_id', 'dokter_id')
                    ->withTimestamps();
    }
}
