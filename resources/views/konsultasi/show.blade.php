@extends('layouts.app')
@section('title','Detail Konsultasi')

@section('content')
@php
  $user = auth()->user();
  $isSource = $user->isAdmin() || ((int) $user->id === (int) $konsultasi->dokter_pengirim_id) || ((int) $user->rumah_sakit_id === (int) $konsultasi->rumah_sakit_asal_id);
  $isTargetDoctorUser = (int) $user->id === (int) $konsultasi->dokter_tujuan_id;
  $isTargetDoctor = $user->isAdmin() || $isTargetDoctorUser;
  $isParticipant = $user->isAdmin() || in_array((int) $user->id, [(int) $konsultasi->dokter_pengirim_id, (int) $konsultasi->dokter_tujuan_id], true);
  $canEdit = $isSource && in_array($konsultasi->status, \App\Models\Konsultasi::sourceEditableStatuses(), true);
  $canSubmit = $isSource && in_array($konsultasi->status, ['draft', 'awaiting_consent'], true) && $konsultasi->consent_status === 'disetujui';
  $canAccept = $isTargetDoctor && in_array($konsultasi->status, ['submitted', 'read'], true);
  $canReply = $isParticipant && !$konsultasi->isTerminal() && $konsultasi->isReplyable();
  $canClose = $isParticipant && !$konsultasi->isTerminal() && !in_array($konsultasi->status, ['draft', 'awaiting_consent'], true);
  $canEscalate = $isSource && !$konsultasi->isTerminal() && !$konsultasi->escalated_to_rujukan_id && !in_array($konsultasi->status, ['draft', 'awaiting_consent'], true);
  $needsSourceSubmission = $isSource && in_array($konsultasi->status, ['draft', 'awaiting_consent'], true);
@endphp

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div>
    <h4 class="mb-1">Konsultasi {{ $konsultasi->no_konsultasi }}</h4>
    <div class="text-muted small">Kasus konsultasi klinis lintas rumah sakit.</div>
  </div>
  <div class="d-flex gap-2 flex-wrap">
    <a href="{{ route('konsultasi.index') }}" class="btn btn-secondary">Kembali</a>
    @if($canEdit)
      <a href="{{ route('konsultasi.edit', $konsultasi) }}" class="btn btn-warning">Edit</a>
    @endif
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="card shadow-sm mb-3">
      <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="fw-semibold">{{ $konsultasi->judul }}</div>
        <div class="d-flex gap-2 flex-wrap">
          @php
            $urgencyClass = match($konsultasi->urgensi) {
              'gawat' => 'danger',
              'segera' => 'warning text-dark',
              default => 'secondary',
            };
          @endphp
          <span class="badge bg-{{ $urgencyClass }}">{{ $konsultasi->urgencyLabel() }}</span>
          <span class="badge bg-{{ $konsultasi->statusBadgeClass() }}">{{ $konsultasi->statusLabel() }}</span>
        </div>
      </div>
      <div class="card-body">
        <table class="table table-bordered mb-0">
          <tr>
            <th width="28%">No Rawat</th>
            <td>{{ $konsultasi->kunjungan->no_rawat ?? '-' }}</td>
          </tr>
          <tr>
            <th>Pasien</th>
            <td>{{ $konsultasi->kunjungan->pasien->no_rkm_medis ?? '-' }} - {{ $konsultasi->kunjungan->pasien->nama ?? '-' }}</td>
          </tr>
          <tr>
            <th>Identitas SATUSEHAT Pasien</th>
            <td>{{ $konsultasi->patient_ihs_number ?? '-' }}</td>
          </tr>
          <tr>
            <th>Rumah Sakit Asal</th>
            <td>{{ $konsultasi->rsAsal->nama ?? '-' }} <span class="text-muted">({{ $konsultasi->organization_ihs_asal ?? '-' }})</span></td>
          </tr>
          <tr>
            <th>Dokter Pengirim</th>
            <td>{{ $konsultasi->dokterPengirim->name ?? '-' }} <span class="text-muted">({{ $konsultasi->practitioner_ihs_pengirim ?? '-' }})</span></td>
          </tr>
          <tr>
            <th>Rumah Sakit Tujuan</th>
            <td>{{ $konsultasi->rsTujuan->nama ?? '-' }} <span class="text-muted">({{ $konsultasi->organization_ihs_tujuan ?? '-' }})</span></td>
          </tr>
          <tr>
            <th>Dokter Tujuan</th>
            <td>{{ $konsultasi->dokterTujuan->name ?? '-' }} <span class="text-muted">({{ $konsultasi->practitioner_ihs_tujuan ?? '-' }})</span></td>
          </tr>
          <tr>
            <th>Encounter SATUSEHAT</th>
            <td>{{ $konsultasi->encounter_satusehat_id ?? '-' }}</td>
          </tr>
          <tr>
            <th>Alasan Konsultasi</th>
            <td class="multiline">{{ $konsultasi->alasan_konsultasi }}</td>
          </tr>
          <tr>
            <th>Pertanyaan Klinis</th>
            <td class="multiline">{{ $konsultasi->pertanyaan_klinis }}</td>
          </tr>
          <tr>
            <th>Ringkasan Klinis</th>
            <td class="multiline">{{ $konsultasi->ringkasan_klinis ?: '-' }}</td>
          </tr>
          <tr>
            <th>Diagnosis Kerja</th>
            <td class="multiline">{{ $konsultasi->diagnosis_kerja ?: '-' }}</td>
          </tr>
          <tr>
            <th>Hasil Penunjang</th>
            <td class="multiline">{{ $konsultasi->hasil_penunjang ?: '-' }}</td>
          </tr>
          <tr>
            <th>Terapi Berjalan</th>
            <td class="multiline">{{ $konsultasi->terapi_berjalan ?: '-' }}</td>
          </tr>
        </table>
      </div>
    </div>

    <div class="card shadow-sm mb-3">
      <div class="card-header bg-light fw-semibold">Thread Diskusi</div>
      <div class="card-body">
        @if($canAccept && $konsultasi->status === 'submitted')
          <div class="alert alert-info">
            Konsultasi ini baru terkirim. Saat dokter tujuan membuka detail, status akan berubah menjadi <strong>Dibaca</strong>.
          </div>
        @endif
        @if($canAccept && $konsultasi->status === 'read')
          <div class="alert alert-info">
            Konsultasi ini sudah dibaca. Dokter tujuan bisa langsung membalas, atau klik <strong>Terima Konsultasi</strong> jika ingin memberi ACC eksplisit.
          </div>
        @endif
        @forelse($konsultasi->pesan->sortBy('created_at') as $pesan)
          <div class="border rounded p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
              <div>
                <div class="fw-semibold">{{ $pesan->pengirim->name ?? 'Sistem' }}</div>
                <div class="small text-muted">{{ $pesan->created_at->format('d/m/Y H:i') }}</div>
              </div>
              <div class="d-flex gap-2 flex-wrap">
                <span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_', ' ', $pesan->jenis_pesan)) }}</span>
                <span class="badge bg-{{ $pesan->status === 'read' ? 'success' : 'secondary' }}">{{ $pesan->status }}</span>
              </div>
            </div>
            <div class="multiline">{{ $pesan->isi_pesan }}</div>
          </div>
        @empty
          <p class="text-muted mb-0">Belum ada pesan.</p>
        @endforelse
      </div>
    </div>

    @if($canReply)
      <div class="card shadow-sm">
        <div class="card-header bg-light fw-semibold">Balas Konsultasi</div>
        <div class="card-body">
          <form method="POST" action="{{ route('konsultasi.balas', $konsultasi) }}">
            @csrf
            <div class="mb-3">
              <label class="form-label">Jenis Pesan</label>
              <select name="jenis_pesan" class="form-select" required>
                <option value="message">Pesan / Diskusi</option>
                @if($isTargetDoctorUser)
                  <option value="answer">Jawaban Klinis</option>
                  <option value="request_more_info">Minta Info Tambahan</option>
                @endif
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Isi Pesan</label>
              <textarea name="isi_pesan" rows="4" class="form-control" required></textarea>
            </div>
            <button class="btn btn-primary">
              <i class="fas fa-paper-plane me-1"></i> Kirim Balasan
            </button>
          </form>
        </div>
      </div>
    @endif
  </div>

  <div class="col-lg-4">
    <div class="card shadow-sm mb-3">
      <div class="card-header bg-light fw-semibold">Consent</div>
      <div class="card-body">
        <table class="table table-sm mb-0">
          <tr>
            <th>Status</th>
            <td>{{ ucfirst(str_replace('_', ' ', $konsultasi->consent_status)) }}</td>
          </tr>
          <tr>
            <th>Pemberi</th>
            <td>{{ $konsultasi->consent_granted_by_name ?: '-' }}</td>
          </tr>
          <tr>
            <th>Peran</th>
            <td>{{ $konsultasi->consent_granted_by_role ?: '-' }}</td>
          </tr>
          <tr>
            <th>Metode</th>
            <td>{{ $konsultasi->consent_method ?: '-' }}</td>
          </tr>
          <tr>
            <th>Waktu</th>
            <td>{{ $konsultasi->consent_granted_at?->format('d/m/Y H:i') ?? '-' }}</td>
          </tr>
          <tr>
            <th>Berlaku Sampai</th>
            <td>{{ $konsultasi->consent_expires_at?->format('d/m/Y H:i') ?? '-' }}</td>
          </tr>
          <tr>
            <th>Catatan</th>
            <td class="multiline">{{ $konsultasi->consent_notes ?: '-' }}</td>
          </tr>
        </table>
      </div>
    </div>

    <div class="card shadow-sm mb-3">
      <div class="card-header bg-light fw-semibold">Aksi</div>
      <div class="card-body d-grid gap-2">
        @if($needsSourceSubmission)
          <div class="alert alert-warning mb-0">
            Konsultasi ini belum terkirim ke dokter tujuan. Lengkapi consent pasien lewat tombol <strong>Edit</strong>, lalu gunakan <strong>Kirim Konsultasi</strong> agar dokter tujuan bisa menerima dan membalas.
          </div>
        @endif

        @if($canSubmit)
          <form method="POST" action="{{ route('konsultasi.submit', $konsultasi) }}">
            @csrf
            @method('PATCH')
            <button class="btn btn-primary w-100" onclick="return confirm('Kirim konsultasi ini sekarang?')">
              <i class="fas fa-paper-plane me-1"></i> Kirim Konsultasi
            </button>
          </form>
        @endif

        @if($canAccept)
          <form method="POST" action="{{ route('konsultasi.ubahStatus', [$konsultasi, 'accepted']) }}">
            @csrf
            @method('PATCH')
            <button class="btn btn-success w-100" onclick="return confirm('Terima konsultasi ini?')">
              <i class="fas fa-check me-1"></i> Terima Konsultasi
            </button>
          </form>

          <form method="POST" action="{{ route('konsultasi.ubahStatus', [$konsultasi, 'rejected']) }}">
            @csrf
            @method('PATCH')
            <button class="btn btn-outline-danger w-100" onclick="return confirm('Tolak konsultasi ini?')">
              <i class="fas fa-times me-1"></i> Tolak Konsultasi
            </button>
          </form>
        @endif

        @if($canClose)
          <form method="POST" action="{{ route('konsultasi.tutup', $konsultasi) }}">
            @csrf
            @method('PATCH')
            <button class="btn btn-outline-dark w-100" onclick="return confirm('Tutup konsultasi ini?')">
              <i class="fas fa-box-archive me-1"></i> Tutup Konsultasi
            </button>
          </form>
        @endif

        @if($canEscalate)
          <form method="POST" action="{{ route('konsultasi.eskalasi', $konsultasi) }}">
            @csrf
            <button class="btn btn-warning w-100" onclick="return confirm('Lanjutkan konsultasi ini menjadi rujukan resmi?')">
              <i class="fas fa-arrow-right-arrow-left me-1"></i> Eskalasi ke Rujukan
            </button>
          </form>
        @endif

        @if($konsultasi->rujukan)
          <a href="{{ route('rujukan.show', $konsultasi->rujukan) }}" class="btn btn-outline-success">
            <i class="fas fa-file-medical me-1"></i> Lihat Rujukan
          </a>
        @endif

        @if(!$needsSourceSubmission && !$canSubmit && !$canAccept && !$canClose && !$canEscalate)
          <div class="text-muted small">
            Belum ada aksi yang tersedia untuk status konsultasi saat ini.
          </div>
        @endif
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-header bg-light fw-semibold">Audit Trail</div>
      <div class="card-body">
        @forelse($konsultasi->auditLogs as $log)
          <div class="border-start border-3 ps-3 mb-3">
            <div class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $log->event_type)) }}</div>
            <div class="small text-muted">{{ $log->created_at->format('d/m/Y H:i:s') }}</div>
            <div class="small text-muted">{{ $log->actor->name ?? 'Sistem' }}</div>
            <div>{{ $log->deskripsi }}</div>
          </div>
        @empty
          <p class="text-muted mb-0">Belum ada audit log.</p>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
