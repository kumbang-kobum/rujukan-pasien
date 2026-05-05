@extends('layouts.app')
@section('title','Daftar Konsultasi')

@section('content')
@php
  $currentUser = auth()->user();
@endphp
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <div>
    <h4 class="mb-1">Konsultasi Antar Dokter</h4>
    <div class="text-muted small">Daftar konsultasi klinis lintas rumah sakit dan status tindak lanjutnya.</div>
  </div>
  @if(auth()->user()->isDokter() || auth()->user()->isAdmin())
    <a href="{{ route('konsultasi.create') }}" class="btn btn-primary">
      <i class="fas fa-plus me-1"></i> Buat Konsultasi
    </a>
  @endif
</div>

<div class="card shadow-sm mb-3">
  <div class="card-body">
    <form method="GET" class="row g-3">
      <div class="col-md-5">
        <label class="form-label">Cari</label>
        <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}"
               placeholder="No konsultasi, no rawat, pasien, judul, alasan...">
      </div>
      <div class="col-md-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="">Semua status</option>
          @foreach([
            'draft' => 'Draft',
            'awaiting_consent' => 'Menunggu Consent',
            'submitted' => 'Terkirim',
            'read' => 'Dibaca',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
            'awaiting_more_info' => 'Butuh Info Tambahan',
            'in_discussion' => 'Dalam Diskusi',
            'answered' => 'Sudah Dijawab',
            'closed' => 'Ditutup',
            'escalated_to_referral' => 'Dilanjutkan ke Rujukan',
          ] as $value => $label)
            <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Urgensi</label>
        <select name="urgensi" class="form-select">
          <option value="">Semua</option>
          <option value="rutin" {{ request('urgensi') === 'rutin' ? 'selected' : '' }}>Rutin</option>
          <option value="segera" {{ request('urgensi') === 'segera' ? 'selected' : '' }}>Segera</option>
          <option value="gawat" {{ request('urgensi') === 'gawat' ? 'selected' : '' }}>Gawat</option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label d-block">&nbsp;</label>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="1" id="tujuan_saya" name="tujuan_saya" {{ request()->boolean('tujuan_saya') ? 'checked' : '' }}>
          <label class="form-check-label" for="tujuan_saya">Tujuan saya</label>
        </div>
      </div>
      <div class="col-12 d-flex gap-2">
        <button class="btn btn-outline-primary"><i class="fas fa-search me-1"></i> Filter</button>
        <a href="{{ route('konsultasi.index') }}" class="btn btn-outline-secondary">Reset</a>
      </div>
    </form>
  </div>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Pasien</th>
            <th>Judul</th>
            <th>Asal</th>
            <th>Tujuan</th>
            <th>Urgensi</th>
            <th>Status</th>
            <th>Dibuat</th>
            <th class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($konsultasi as $item)
            @php
              $isSource = $currentUser->isAdmin() || ((int) $currentUser->id === (int) $item->dokter_pengirim_id) || ((int) $currentUser->rumah_sakit_id === (int) $item->rumah_sakit_asal_id);
              $isTargetDoctorUser = (int) $currentUser->id === (int) $item->dokter_tujuan_id;
              $isTargetDoctor = $currentUser->isAdmin() || $isTargetDoctorUser;
              $isParticipant = $currentUser->isAdmin() || in_array((int) $currentUser->id, [(int) $item->dokter_pengirim_id, (int) $item->dokter_tujuan_id], true);
              $canEdit = $isSource && in_array($item->status, \App\Models\Konsultasi::sourceEditableStatuses(), true);
              $canAccept = $isTargetDoctor && in_array($item->status, ['submitted', 'read'], true);
              $canQuickReply = $isParticipant && !$item->isTerminal() && $item->isReplyable();
              $needsAttention = $canAccept || (($item->unread_messages_count ?? 0) > 0);
              $replyCollapseId = 'quick-reply-' . $item->id;
            @endphp
            <tr class="{{ $needsAttention ? 'table-warning' : '' }}">
              <td>
                <div class="fw-semibold">{{ $item->no_konsultasi }}</div>
                <div class="small text-muted">{{ $item->kunjungan->no_rawat ?? '-' }}</div>
              </td>
              <td>
                <div class="fw-semibold">{{ $item->kunjungan->pasien->nama ?? '-' }}</div>
                <div class="small text-muted">{{ $item->kunjungan->pasien->no_rkm_medis ?? '-' }}</div>
              </td>
              <td>
                <div class="fw-semibold">{{ $item->judul }}</div>
                <div class="small text-muted text-truncate" style="max-width: 280px;">{{ $item->alasan_konsultasi }}</div>
              </td>
              <td>
                <div>{{ $item->rsAsal->nama ?? '-' }}</div>
                <div class="small text-muted">{{ $item->dokterPengirim->name ?? '-' }}</div>
              </td>
              <td>
                <div>{{ $item->rsTujuan->nama ?? '-' }}</div>
                <div class="small text-muted">{{ $item->dokterTujuan->name ?? '-' }}</div>
              </td>
              <td>
                @php
                  $urgencyClass = match($item->urgensi) {
                    'gawat' => 'danger',
                    'segera' => 'warning text-dark',
                    default => 'secondary',
                  };
                @endphp
                <span class="badge bg-{{ $urgencyClass }}">{{ $item->urgencyLabel() }}</span>
              </td>
              <td><span class="badge bg-{{ $item->statusBadgeClass() }}">{{ $item->statusLabel() }}</span></td>
              <td>{{ $item->created_at?->format('d/m/Y H:i') }}</td>
              <td class="text-end">
                <div class="d-flex justify-content-end gap-2 flex-wrap">
                  @if(($item->unread_messages_count ?? 0) > 0)
                    <span class="badge bg-danger align-self-center">{{ $item->unread_messages_count }} pesan baru</span>
                  @endif
                  @if($canAccept)
                    <span class="badge bg-primary align-self-center">Menunggu respon Anda</span>
                  @endif
                  <a href="{{ route('konsultasi.show', $item) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                  @if($canEdit)
                    <a href="{{ route('konsultasi.edit', $item) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                  @endif
                  @if($canAccept)
                    <form method="POST" action="{{ route('konsultasi.ubahStatus', [$item, 'accepted']) }}">
                      @csrf
                      @method('PATCH')
                      <button class="btn btn-sm btn-success" onclick="return confirm('Terima konsultasi ini?')">Terima</button>
                    </form>
                    <form method="POST" action="{{ route('konsultasi.ubahStatus', [$item, 'rejected']) }}">
                      @csrf
                      @method('PATCH')
                      <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Tolak konsultasi ini?')">Tolak</button>
                    </form>
                  @endif
                  @if($canQuickReply)
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $replyCollapseId }}">
                      Balas Cepat
                    </button>
                  @endif
                </div>
              </td>
            </tr>
            @if($canQuickReply)
              <tr class="{{ $needsAttention ? 'table-warning' : '' }}">
                <td colspan="9" class="pt-0">
                  <div class="collapse" id="{{ $replyCollapseId }}">
                    <div class="border rounded bg-white p-3 my-2">
                      <div class="fw-semibold mb-2">Balas Cepat</div>
                      <form method="POST" action="{{ route('konsultasi.balas', $item) }}" class="row g-2">
                        @csrf
                        <div class="col-md-3">
                          <select name="jenis_pesan" class="form-select" required>
                            <option value="message">Pesan / Diskusi</option>
                            @if($isTargetDoctorUser)
                              <option value="answer">Jawaban Klinis</option>
                              <option value="request_more_info">Minta Info Tambahan</option>
                            @endif
                          </select>
                        </div>
                        <div class="col-md-7">
                          <input type="text" name="isi_pesan" class="form-control" placeholder="Tulis balasan singkat..." required>
                        </div>
                        <div class="col-md-2 d-grid">
                          <button class="btn btn-primary">Kirim</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </td>
              </tr>
            @endif
          @empty
            <tr>
              <td colspan="9" class="text-center py-4 text-muted">Belum ada konsultasi.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="mt-3">
  {{ $konsultasi->links() }}
</div>
@endsection
