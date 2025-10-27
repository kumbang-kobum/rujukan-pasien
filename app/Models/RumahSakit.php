<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RumahSakit extends Model
{
    protected $table = 'rumah_sakit'; // nama tabel di DB
    protected $fillable = ['nama','alamat','telepon'];
    protected $casts = [
        'id' => 'integer',
    ];
    
    public function users()
    {
        return $this->hasMany(User::class, 'rumah_sakit_id');
    }
}