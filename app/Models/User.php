<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN_RS = 'admin_rs';
    public const ROLE_DOKTER = 'dokter';
    public const ROLE_PETUGAS = 'petugas';

    protected $casts = [
        'rumah_sakit_id' => 'integer',
    ];
    
    protected $fillable = [
        'name',
        'email',
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
    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdminRs()
    {
        return $this->role === self::ROLE_ADMIN_RS;
    }

    public function isAdmin()
    {
        return $this->isSuperAdmin() || $this->isAdminRs();
    }

    public function isDokter()
    {
        return $this->role === self::ROLE_DOKTER;
    }

    public function isPetugas()
    {
        return $this->role === self::ROLE_PETUGAS;
    }

    public function isPerawat()
    {
        return $this->isPetugas();
    }

    public function canManageHospitals(): bool
    {
        return $this->isSuperAdmin();
    }

    public function canManageUsers(): bool
    {
        return $this->isSuperAdmin() || $this->isAdminRs();
    }

    public function canAccessClinical(): bool
    {
        return $this->isAdminRs() || $this->isDokter() || $this->isPetugas();
    }

    public function getRoleLabelAttribute(): string
    {
        return self::roleLabels()[$this->role] ?? ucfirst(str_replace('_', ' ', (string) $this->role));
    }

    public static function roleLabels(): array
    {
        return [
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_ADMIN_RS => 'Admin RS',
            self::ROLE_DOKTER => 'Dokter',
            self::ROLE_PETUGAS => 'Petugas',
        ];
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
}
