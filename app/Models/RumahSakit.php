<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RumahSakit extends Model
{
    use HasFactory;

    protected $table = 'rumah_sakit'; // nama tabel di DB
    protected $fillable = ['nama','organization_ihs_number','alamat','telepon'];
    protected $casts = [
        'id' => 'integer',
    ];
    
    public function users()
    {
        return $this->hasMany(User::class, 'rumah_sakit_id');
    }

    public function konsultasiAsal()
    {
        return $this->hasMany(Konsultasi::class, 'rumah_sakit_asal_id');
    }

    public function konsultasiTujuan()
    {
        return $this->hasMany(Konsultasi::class, 'rumah_sakit_tujuan_id');
    }
}
