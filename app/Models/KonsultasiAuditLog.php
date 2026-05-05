<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonsultasiAuditLog extends Model
{
    use HasFactory;

    protected $table = 'konsultasi_audit_logs';

    protected $fillable = [
        'konsultasi_id',
        'user_id',
        'aksi',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class, 'konsultasi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
