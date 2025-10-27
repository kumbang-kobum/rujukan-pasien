<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    ];

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}