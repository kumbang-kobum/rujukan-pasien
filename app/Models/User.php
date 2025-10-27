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
        'password',
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
}