<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SOAP;
use App\Models\Pasien;
use App\Models\User;
use App\Models\Rujukan;
use App\Models\BerkasMedis;
use Illuminate\Database\Eloquent\Builder;

class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'kunjungan';

    protected $fillable = [
        'no_rawat',          // nomor rawat otomatis ala SIMRS
        'pasien_id',
        'dokter_id',
        'user_id',
        'rumah_sakit_id',
        'rajalranap',
        'tanggal_kunjungan',
        'waktu_masuk',
        'keluhan_utama',
        'status_pulang',     // 0 = Rawat, 1 = Pulang
        'waktu_pulang',      // kapan dipulangkan
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'waktu_masuk'       => 'datetime',
        'waktu_pulang'      => 'datetime',
        'status_pulang'     => 'boolean',
    ];

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($user) {
            $q->where('rumah_sakit_id', $user->rumah_sakit_id)
              ->orWhereHas('rujukan', function (Builder $r) use ($user) {
                  $r->where('rumah_sakit_tujuan_id', $user->rumah_sakit_id)
                    ->where('status', 'diterima');
              });
        });
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function soap()
    {
        return $this->hasMany(SOAP::class);
    }

    public function rujukan()
    {
        return $this->hasOne(Rujukan::class);
    }

    public function konsultasi()
    {
        return $this->hasMany(Konsultasi::class, 'kunjungan_id');
    }

    public function berkasMedis()
    {
        return $this->hasMany(BerkasMedis::class, 'kunjungan_id')
                    ->orderByDesc('created_at');;
    }
}
