<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_TERKIRIM = 'terkirim';
    public const STATUS_DIBACA = 'dibaca';
    public const STATUS_DITERIMA = 'diterima';
    public const STATUS_DISKUSI = 'diskusi';
    public const STATUS_BUTUH_INFO = 'butuh_info';
    public const STATUS_DIJAWAB = 'dijawab';
    public const STATUS_DITUTUP = 'ditutup';
    public const STATUS_DIRUJUK = 'dijadikan_rujukan';

    public const CONSENT_MENUNGGU = 'menunggu';
    public const CONSENT_DIBERIKAN = 'diberikan';
    public const CONSENT_DITOLAK = 'ditolak';

    protected $table = 'konsultasi';

    protected $fillable = [
        'kunjungan_id',
        'rumah_sakit_asal_id',
        'rumah_sakit_tujuan_id',
        'dokter_pengirim_id',
        'dokter_tujuan_id',
        'rujukan_id',
        'judul',
        'ringkasan_klinis',
        'diagnosis_kerja',
        'terapi_berjalan',
        'hasil_penunjang',
        'alasan_konsultasi',
        'pertanyaan_konsultasi',
        'status',
        'consent_status',
        'consent_nama_pemberi',
        'consent_hubungan',
        'consent_metode',
        'consent_diberikan_pada',
        'consent_catatan',
        'submitted_at',
        'read_at',
        'accepted_at',
        'closed_at',
        'closed_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'read_at' => 'datetime',
        'accepted_at' => 'datetime',
        'closed_at' => 'datetime',
        'consent_diberikan_pada' => 'datetime',
    ];

    public function scopeVisibleTo($query, User $user)
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        if ($user->isAdminRs()) {
            return $query->where(function ($inner) use ($user) {
                $inner->where('rumah_sakit_asal_id', $user->rumah_sakit_id)
                    ->orWhere('rumah_sakit_tujuan_id', $user->rumah_sakit_id);
            });
        }

        return $query->where(function ($inner) use ($user) {
            $inner->where('dokter_pengirim_id', $user->id)
                ->orWhere(function ($received) use ($user) {
                    $received->where('dokter_tujuan_id', $user->id)
                        ->where('status', '!=', self::STATUS_DRAFT)
                        ->where('consent_status', self::CONSENT_DIBERIKAN);
                });
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

    public function dokterPengirim()
    {
        return $this->belongsTo(User::class, 'dokter_pengirim_id');
    }

    public function dokterTujuan()
    {
        return $this->belongsTo(User::class, 'dokter_tujuan_id');
    }

    public function rujukan()
    {
        return $this->belongsTo(Rujukan::class, 'rujukan_id');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function pesan()
    {
        return $this->hasMany(KonsultasiPesan::class, 'konsultasi_id')->orderBy('created_at');
    }

    public function auditLogs()
    {
        return $this->hasMany(KonsultasiAuditLog::class, 'konsultasi_id')->latest();
    }

    public function latestMessage()
    {
        return $this->hasOne(KonsultasiPesan::class, 'konsultasi_id')->latestOfMany();
    }

    public function markAsRead(): void
    {
        if ($this->status === self::STATUS_TERKIRIM) {
            $this->forceFill([
                'status' => self::STATUS_DIBACA,
                'read_at' => $this->read_at ?? now(),
            ])->save();
        }
    }

    public function canReply(): bool
    {
        return !in_array($this->status, [self::STATUS_DRAFT, self::STATUS_DITUTUP, self::STATUS_DIRUJUK], true);
    }

    public function isSender(User $user): bool
    {
        return (int) $this->dokter_pengirim_id === (int) $user->id;
    }

    public function isTarget(User $user): bool
    {
        return (int) $this->dokter_tujuan_id === (int) $user->id;
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_TERKIRIM => 'Terkirim',
            self::STATUS_DIBACA => 'Dibaca',
            self::STATUS_DITERIMA => 'Diterima',
            self::STATUS_DISKUSI => 'Diskusi',
            self::STATUS_BUTUH_INFO => 'Butuh Info',
            self::STATUS_DIJAWAB => 'Dijawab',
            self::STATUS_DITUTUP => 'Ditutup',
            self::STATUS_DIRUJUK => 'Jadi Rujukan',
        ];
    }

    public static function statusBadgeClasses(): array
    {
        return [
            self::STATUS_DRAFT => 'bg-secondary',
            self::STATUS_TERKIRIM => 'bg-primary',
            self::STATUS_DIBACA => 'bg-info text-dark',
            self::STATUS_DITERIMA => 'bg-success',
            self::STATUS_DISKUSI => 'bg-dark',
            self::STATUS_BUTUH_INFO => 'bg-warning text-dark',
            self::STATUS_DIJAWAB => 'bg-success',
            self::STATUS_DITUTUP => 'bg-secondary',
            self::STATUS_DIRUJUK => 'bg-success',
        ];
    }
}
