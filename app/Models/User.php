<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $casts = [
    'rumah_sakit_id' => 'integer',
    ];
    
    protected $fillable = [
        'name',
        'email',
        'practitioner_ihs_number',
        'satusehat_practitioner_role_id',
        'spesialisasi',
        'password',
        'avatar_path',
        'role',
        'rumah_sakit_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke Rumah Sakit
    public function rumahSakit()
    {
        return $this->belongsTo(\App\Models\RumahSakit::class);
    }
    
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar_path
            ? asset('storage/'.$this->avatar_path)
            : 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=2563eb&color=fff&size=64';
    }

    // Helpers role
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDokter()
    {
        return $this->role === 'dokter';
    }

    public function isPerawat()
    {
        return $this->role === 'perawat';
    }
    
    public function rujukanCc() {
        return $this->belongsToMany(Rujukan::class, 'rujukan_dokter_cc', 'dokter_id', 'rujukan_id');
    }

    public function konsultasiDikirim()
    {
        return $this->hasMany(Konsultasi::class, 'dokter_pengirim_id');
    }

    public function konsultasiDiterima()
    {
        return $this->hasMany(Konsultasi::class, 'dokter_tujuan_id');
    }

    public function pesanKonsultasi()
    {
        return $this->hasMany(KonsultasiPesan::class, 'pengirim_id');
    }
}
