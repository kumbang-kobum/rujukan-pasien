<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SOAP extends Model
{
    use HasFactory;

    protected $table = 'soap';

    protected $fillable = [
        'kunjungan_id',
        'user_id',
        'subjektif',
        'objektif',
        'assessment',
        'plan',
        'advice',
        'td_sys',
        'td_dia',
        'map',
    ];

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        return $query->whereHas('kunjungan', function (Builder $kunjungan) use ($user) {
            $kunjungan->where('rumah_sakit_id', $user->rumah_sakit_id);
        });
    }

    public function berkas()
    { 
        return $this->hasMany(BerkasMedis::class,'soap_id'); 
    }
    
    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
