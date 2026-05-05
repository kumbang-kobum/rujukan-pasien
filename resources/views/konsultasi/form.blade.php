@php
    $selectedKunjungan = old('kunjungan_id', $selectedKunjunganId ?? ($konsultasi->kunjungan_id ?? ''));
    $selectedRsTujuan = old('rumah_sakit_tujuan_id', $konsultasi->rumah_sakit_tujuan_id ?? '');
    $selectedDokterTujuan = old('dokter_tujuan_id', $konsultasi->dokter_tujuan_id ?? '');
@endphp

<div class="mb-3">
  <label class="form-label">Kunjungan (No Rawat - No RM - Nama)</label>
  <select name="kunjungan_id" class="form-select select2 @error('kunjungan_id') is-invalid @enderror" required>
    <option value="">Pilih kunjungan...</option>
    @foreach($kunjungan as $k)
      <option value="{{ $k->id }}" {{ (string)$selectedKunjungan === (string)$k->id ? 'selected' : '' }}>
        {{ $k->no_rawat }} - {{ $k->pasien->no_rkm_medis ?? '-' }} - {{ $k->pasien->nama ?? '-' }}
      </option>
    @endforeach
  </select>
  @error('kunjungan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Rumah Sakit Tujuan</label>
    <select name="rumah_sakit_tujuan_id" id="rs_tujuan_id" class="form-select @error('rumah_sakit_tujuan_id') is-invalid @enderror" required
            data-url="{{ url('/ajax/dokter-by-rs/__ID__') }}">
      <option value="">Pilih RS tujuan...</option>
      @foreach($rumahSakitTujuan as $rs)
        <option value="{{ $rs->id }}" {{ (string)$selectedRsTujuan === (string)$rs->id ? 'selected' : '' }}>
          {{ $rs->nama }}
        </option>
      @endforeach
    </select>
    @error('rumah_sakit_tujuan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">Dokter Tujuan</label>
    <select name="dokter_tujuan_id" id="dokter_tujuan_id" class="form-select @error('dokter_tujuan_id') is-invalid @enderror" required>
      <option value="">Pilih dokter tujuan...</option>
      @foreach($dokterTujuan as $dokter)
        <option value="{{ $dokter->id }}" {{ (string)$selectedDokterTujuan === (string)$dokter->id ? 'selected' : '' }}>
          {{ $dokter->name }}
        </option>
      @endforeach
    </select>
    @error('dokter_tujuan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="row g-3 mt-1">
  <div class="col-md-8">
    <label class="form-label">Judul Konsultasi</label>
    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
           value="{{ old('judul', $konsultasi->judul ?? '') }}" required
           placeholder="Contoh: Mohon second opinion kardiologi untuk nyeri dada akut">
    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">Urgensi</label>
    <select name="urgensi" class="form-select @error('urgensi') is-invalid @enderror" required>
      @foreach(['rutin' => 'Rutin', 'segera' => 'Segera', 'gawat' => 'Gawat'] as $value => $label)
        <option value="{{ $value }}" {{ old('urgensi', $konsultasi->urgensi ?? 'rutin') === $value ? 'selected' : '' }}>
          {{ $label }}
        </option>
      @endforeach
    </select>
    @error('urgensi') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="mt-3">
  <label class="form-label">Alasan Konsultasi</label>
  <textarea name="alasan_konsultasi" rows="3" class="form-control @error('alasan_konsultasi') is-invalid @enderror" required>{{ old('alasan_konsultasi', $konsultasi->alasan_konsultasi ?? '') }}</textarea>
  @error('alasan_konsultasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mt-3">
  <label class="form-label">Pertanyaan Klinis ke Dokter Tujuan</label>
  <textarea name="pertanyaan_klinis" rows="3" class="form-control @error('pertanyaan_klinis') is-invalid @enderror" required>{{ old('pertanyaan_klinis', $konsultasi->pertanyaan_klinis ?? '') }}</textarea>
  @error('pertanyaan_klinis') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row g-3 mt-1">
  <div class="col-md-6">
    <label class="form-label">Ringkasan Klinis</label>
    <textarea name="ringkasan_klinis" rows="5" class="form-control @error('ringkasan_klinis') is-invalid @enderror">{{ old('ringkasan_klinis', $konsultasi->ringkasan_klinis ?? '') }}</textarea>
    @error('ringkasan_klinis') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">Diagnosis Kerja</label>
    <textarea name="diagnosis_kerja" rows="5" class="form-control @error('diagnosis_kerja') is-invalid @enderror">{{ old('diagnosis_kerja', $konsultasi->diagnosis_kerja ?? '') }}</textarea>
    @error('diagnosis_kerja') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="row g-3 mt-1">
  <div class="col-md-6">
    <label class="form-label">Hasil Penunjang Relevan</label>
    <textarea name="hasil_penunjang" rows="4" class="form-control @error('hasil_penunjang') is-invalid @enderror">{{ old('hasil_penunjang', $konsultasi->hasil_penunjang ?? '') }}</textarea>
    @error('hasil_penunjang') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">Terapi Berjalan</label>
    <textarea name="terapi_berjalan" rows="4" class="form-control @error('terapi_berjalan') is-invalid @enderror">{{ old('terapi_berjalan', $konsultasi->terapi_berjalan ?? '') }}</textarea>
    @error('terapi_berjalan') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<hr class="my-4">

<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
  <div>
    <h6 class="mb-1">Consent Pembukaan Data Lintas RS</h6>
    <small class="text-muted">Consent minimal harus mencatat siapa pemberi persetujuan, metode, dan waktunya.</small>
  </div>
  <div class="form-check">
    <input type="checkbox" class="form-check-input" id="consent_confirmed" name="consent_confirmed" value="1"
           {{ old('consent_confirmed', ($konsultasi->consent_status ?? null) === 'disetujui' ? 1 : 0) ? 'checked' : '' }}>
    <label class="form-check-label" for="consent_confirmed">Consent sudah didapat</label>
  </div>
</div>

<div class="row g-3 mt-1">
  <div class="col-md-4">
    <label class="form-label">Nama Pemberi Consent</label>
    <input type="text" name="consent_granted_by_name" class="form-control @error('consent_granted_by_name') is-invalid @enderror"
           value="{{ old('consent_granted_by_name', $konsultasi->consent_granted_by_name ?? '') }}"
           placeholder="Pasien / wali / keluarga">
    @error('consent_granted_by_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">Peran Pemberi Consent</label>
    <input type="text" name="consent_granted_by_role" class="form-control @error('consent_granted_by_role') is-invalid @enderror"
           value="{{ old('consent_granted_by_role', $konsultasi->consent_granted_by_role ?? '') }}"
           placeholder="Pasien / suami / istri / orang tua / wali">
    @error('consent_granted_by_role') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">Metode Consent</label>
    <select name="consent_method" class="form-select @error('consent_method') is-invalid @enderror">
      <option value="">Pilih metode...</option>
      @foreach(['ttd_elektronik' => 'Tanda tangan elektronik', 'otp' => 'OTP', 'verbal_tercatat' => 'Verbal tercatat', 'form_manual' => 'Form manual'] as $value => $label)
        <option value="{{ $value }}" {{ old('consent_method', $konsultasi->consent_method ?? '') === $value ? 'selected' : '' }}>
          {{ $label }}
        </option>
      @endforeach
    </select>
    @error('consent_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="row g-3 mt-1">
  <div class="col-md-6">
    <label class="form-label">Waktu Consent</label>
    <input type="datetime-local" name="consent_granted_at" class="form-control @error('consent_granted_at') is-invalid @enderror"
           value="{{ old('consent_granted_at', isset($konsultasi?->consent_granted_at) ? $konsultasi->consent_granted_at->format('Y-m-d\TH:i') : '') }}">
    @error('consent_granted_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">Berlaku Sampai</label>
    <input type="datetime-local" name="consent_expires_at" class="form-control @error('consent_expires_at') is-invalid @enderror"
           value="{{ old('consent_expires_at', isset($konsultasi?->consent_expires_at) ? $konsultasi->consent_expires_at->format('Y-m-d\TH:i') : '') }}">
    @error('consent_expires_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

<div class="mt-3">
  <label class="form-label">Catatan Consent</label>
  <textarea name="consent_notes" rows="2" class="form-control @error('consent_notes') is-invalid @enderror">{{ old('consent_notes', $konsultasi->consent_notes ?? '') }}</textarea>
  @error('consent_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

@push('scripts')
<script>
(function () {
  const rs = document.getElementById('rs_tujuan_id');
  const dr = document.getElementById('dokter_tujuan_id');
  if (!rs || !dr) return;

  const urlTpl = rs.getAttribute('data-url');
  const selectedDoctor = @json((string) $selectedDokterTujuan);

  async function loadDoctors() {
    if (!rs.value) {
      dr.innerHTML = '<option value="">Pilih dokter tujuan...</option>';
      dr.disabled = true;
      return;
    }

    dr.disabled = true;
    dr.innerHTML = '<option value="">Memuat dokter...</option>';

    try {
      const response = await fetch(urlTpl.replace('__ID__', rs.value), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
      });
      const data = await response.json();
      const list = Array.isArray(data) ? data : [];

      dr.innerHTML = '<option value="">Pilih dokter tujuan...</option>';
      list.forEach(function (dokter) {
        const option = document.createElement('option');
        option.value = dokter.id;
        option.textContent = dokter.name;
        if (String(dokter.id) === String(selectedDoctor)) option.selected = true;
        dr.appendChild(option);
      });
      dr.disabled = false;
    } catch (error) {
      console.error(error);
      dr.innerHTML = '<option value="">Gagal memuat dokter</option>';
      dr.disabled = false;
    }
  }

  rs.addEventListener('change', loadDoctors);

  if (window.jQuery && jQuery.fn.select2) {
    jQuery('select.select2').select2({ width: '100%' });
  }

  if (rs.value && dr.options.length <= 1) loadDoctors();
})();
</script>
@endpush
