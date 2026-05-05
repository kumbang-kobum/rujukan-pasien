<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    use HasFactory;

    protected $table = 'konsultasi';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_AWAITING_CONSENT = 'awaiting_consent';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_READ = 'read';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_AWAITING_MORE_INFO = 'awaiting_more_info';
    public const STATUS_IN_DISCUSSION = 'in_discussion';
    public const STATUS_ANSWERED = 'answered';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_ESCALATED = 'escalated_to_referral';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'no_konsultasi',
        'kunjungan_id',
        'pasien_id',
        'rumah_sakit_asal_id',
        'rumah_sakit_tujuan_id',
        'dokter_pengirim_id',
        'dokter_tujuan_id',
        'escalated_to_rujukan_id',
        'patient_ihs_number',
        'organization_ihs_asal',
        'organization_ihs_tujuan',
        'practitioner_ihs_pengirim',
        'practitioner_ihs_tujuan',
        'practitioner_role_pengirim',
        'practitioner_role_tujuan',
        'encounter_satusehat_id',
        'judul',
        'urgensi',
        'alasan_konsultasi',
        'pertanyaan_klinis',
        'ringkasan_klinis',
        'diagnosis_kerja',
        'hasil_penunjang',
        'terapi_berjalan',
        'consent_status',
        'consent_granted_by_name',
        'consent_granted_by_role',
        'consent_method',
        'consent_granted_at',
        'consent_expires_at',
        'consent_notes',
        'status',
        'submitted_at',
        'accepted_at',
        'answered_at',
        'closed_at',
        'last_message_at',
        'cancelled_reason',
    ];

    protected $casts = [
        'consent_granted_at' => 'datetime',
        'consent_expires_at' => 'datetime',
        'submitted_at' => 'datetime',
        'accepted_at' => 'datetime',
        'answered_at' => 'datetime',
        'closed_at' => 'datetime',
        'last_message_at' => 'datetime',
    ];

    public function scopeVisibleTo($query, User $user)
    {
        if ($user->isAdmin()) {
            return $query;
        }

        return $query->where(function ($q) use ($user) {
            $q->where('dokter_pengirim_id', $user->id)
                ->orWhere('rumah_sakit_asal_id', $user->rumah_sakit_id)
                ->orWhere(function ($target) use ($user) {
                    $target->where(function ($targetIdentity) use ($user) {
                        $targetIdentity->where('dokter_tujuan_id', $user->id)
                            ->orWhere('rumah_sakit_tujuan_id', $user->rumah_sakit_id);
                    })->whereIn('status', self::targetVisibleStatuses());
                });
        });
    }

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class, 'kunjungan_id');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
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
        return $this->belongsTo(Rujukan::class, 'escalated_to_rujukan_id');
    }

    public function pesan()
    {
        return $this->hasMany(KonsultasiPesan::class, 'konsultasi_id')->latest();
    }

    public function auditLogs()
    {
        return $this->hasMany(KonsultasiAuditLog::class, 'konsultasi_id')->latest();
    }

    public function isTerminal(): bool
    {
        return in_array($this->status, [
            self::STATUS_CLOSED,
            self::STATUS_ESCALATED,
            self::STATUS_CANCELLED,
        ], true);
    }

    public static function sourceEditableStatuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_AWAITING_CONSENT,
            self::STATUS_SUBMITTED,
            self::STATUS_READ,
            self::STATUS_AWAITING_MORE_INFO,
            self::STATUS_REJECTED,
        ];
    }

    public static function replyableStatuses(): array
    {
        return [
            self::STATUS_READ,
            self::STATUS_ACCEPTED,
            self::STATUS_AWAITING_MORE_INFO,
            self::STATUS_IN_DISCUSSION,
            self::STATUS_ANSWERED,
        ];
    }

    public static function targetVisibleStatuses(): array
    {
        return [
            self::STATUS_SUBMITTED,
            self::STATUS_READ,
            self::STATUS_ACCEPTED,
            self::STATUS_REJECTED,
            self::STATUS_AWAITING_MORE_INFO,
            self::STATUS_IN_DISCUSSION,
            self::STATUS_ANSWERED,
            self::STATUS_CLOSED,
            self::STATUS_ESCALATED,
            self::STATUS_CANCELLED,
        ];
    }

    public function isReplyable(): bool
    {
        return in_array($this->status, self::replyableStatuses(), true);
    }

    public function isVisibleTo(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (!$user->isDokter()) {
            return false;
        }

        $isSourceSide = (int) $user->id === (int) $this->dokter_pengirim_id
            || (int) $user->rumah_sakit_id === (int) $this->rumah_sakit_asal_id;

        if ($isSourceSide) {
            return true;
        }

        $isTargetSide = (int) $user->id === (int) $this->dokter_tujuan_id
            || (int) $user->rumah_sakit_id === (int) $this->rumah_sakit_tujuan_id;

        if (!$isTargetSide) {
            return false;
        }

        return in_array($this->status, self::targetVisibleStatuses(), true);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_AWAITING_CONSENT => 'Menunggu Consent',
            self::STATUS_SUBMITTED => 'Terkirim',
            self::STATUS_READ => 'Dibaca',
            self::STATUS_ACCEPTED => 'Diterima',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_AWAITING_MORE_INFO => 'Butuh Info Tambahan',
            self::STATUS_IN_DISCUSSION => 'Dalam Diskusi',
            self::STATUS_ANSWERED => 'Sudah Dijawab',
            self::STATUS_CLOSED => 'Ditutup',
            self::STATUS_ESCALATED => 'Dilanjutkan ke Rujukan',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'secondary',
            self::STATUS_AWAITING_CONSENT => 'warning text-dark',
            self::STATUS_SUBMITTED => 'primary',
            self::STATUS_READ => 'info text-dark',
            self::STATUS_ACCEPTED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_AWAITING_MORE_INFO => 'warning text-dark',
            self::STATUS_IN_DISCUSSION => 'info text-dark',
            self::STATUS_ANSWERED => 'success',
            self::STATUS_CLOSED => 'dark',
            self::STATUS_ESCALATED => 'success',
            self::STATUS_CANCELLED => 'secondary',
            default => 'secondary',
        };
    }

    public function urgencyLabel(): string
    {
        return match ($this->urgensi) {
            'gawat' => 'Gawat',
            'segera' => 'Segera',
            default => 'Rutin',
        };
    }
}
