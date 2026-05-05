@extends('layouts.app')
@section('title','Edit Rujukan')

@section('content')
<div class="card shadow-sm">
  <div class="card-header bg-warning text-white">Edit Rujukan</div>

  <div class="card-body">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form id="formRujukanEdit" method="POST" action="{{ route('rujukan.update', $rujukan->id) }}">
      @csrf @method('PUT')

      {{-- Kunjungan --}}
      <div class="mb-3">
        <label class="form-label">Kunjungan (No. Rawat — No RM — Nama)</label>
        <select name="kunjungan_id" class="form-select @error('kunjungan_id') is-invalid @enderror" required>
          @foreach($kunjungan as $k)
            <option value="{{ $k->id }}"
              {{ (int)old('kunjungan_id', $rujukan->kunjungan_id) === (int)$k->id ? 'selected' : '' }}>
              {{ $k->no_rawat ?? $k->id }} — {{ $k->pasien->no_rkm_medis ?? '-' }} / {{ $k->pasien->nama ?? '-' }}
            </option>
          @endforeach
        </select>
        @error('kunjungan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- RS Asal (readonly + hidden) --}}
      <div class="mb-3">
        <label class="form-label">Rumah Sakit Asal</label>
        <input class="form-control" value="{{ $rujukan->rsAsal->nama ?? '-' }}" readonly>
        <input type="hidden" name="rumah_sakit_asal_id" value="{{ $rsAsalId }}">
      </div>

      {{-- RS Tujuan --}}
      <div class="mb-3">
        <label class="form-label">Rumah Sakit Tujuan</label>
        <select name="rumah_sakit_tujuan_id" id="rs_tujuan_id"
                class="form-select @error('rumah_sakit_tujuan_id') is-invalid @enderror" required
                data-url="{{ url('/ajax/dokter-by-rs/__ID__') }}"
                onchange="window.__reloadDokterEdit && window.__reloadDokterEdit()">
          <option value="">-- Pilih RS --</option>
          @foreach($rumahSakitTujuan as $rs)
            <option value="{{ $rs->id }}"
              {{ (int)old('rumah_sakit_tujuan_id', $rujukan->rumah_sakit_tujuan_id) === (int)$rs->id ? 'selected' : '' }}>
              {{ $rs->nama }}
            </option>
          @endforeach
        </select>
        @error('rumah_sakit_tujuan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- Dokter Tujuan (utama) --}}
        <div class="mb-3">
          <label class="form-label">Dokter Tujuan (utama)</label>
          <select name="dokter_tujuan_id" id="dokter_tujuan_id"
                  class="form-select @error('dokter_tujuan_id') is-invalid @enderror" required>
            <option value="">-- Pilih Dokter --</option>
            @foreach($dokter as $d)
              <option value="{{ $d->id }}"
                {{ (int)old('dokter_tujuan_id', $rujukan->dokter_tujuan_id) === (int)$d->id ? 'selected' : '' }}>
                {{ $d->name }}
              </option>
            @endforeach
          </select>
          @error('dokter_tujuan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        
        {{-- Dokter Tujuan Tambahan (opsional) --}}
        <div class="mb-3">
          <label class="form-label">Dokter Tujuan Tambahan (opsional)</label>
          <select name="dokter_cc_ids[]" id="dokter_cc_ids"
                  class="form-select select2" multiple
                  data-placeholder="Pilih 1 atau lebih dokter"
                  data-selected='@json(old("dokter_cc_ids", $ccTerpilih ?? []))'>
            @foreach($dokter as $d)
              <option value="{{ $d->id }}">{{ $d->name }}</option>
            @endforeach
          </select>
          <small class="text-muted">Email rujukan juga dikirim ke semua dokter di sini.</small>
          @error('dokter_cc_ids.*')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

      {{-- Alasan --}}
      <div class="mb-3">
        <label class="form-label">Alasan</label>
        <input type="text" name="alasan" class="form-control @error('alasan') is-invalid @enderror"
               value="{{ old('alasan', $rujukan->alasan) }}" required>
        @error('alasan') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- Alasan detail --}}
      <div class="mb-3">
        <label class="form-label">Alasan Rujukan (detail)</label>
        <textarea name="alasan_rujukan" rows="3" class="form-control @error('alasan_rujukan') is-invalid @enderror">{{ old('alasan_rujukan', $rujukan->alasan_rujukan) }}</textarea>
        @error('alasan_rujukan') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- Catatan --}}
      <div class="mb-3">
        <label class="form-label">Catatan</label>
        <textarea name="catatan" rows="3" class="form-control @error('catatan') is-invalid @enderror">{{ old('catatan', $rujukan->catatan) }}</textarea>
        @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- Status --}}
      <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
          @foreach(['menunggu' => 'Menunggu', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'] as $k => $v)
            <option value="{{ $k }}" {{ old('status', $rujukan->status) === $k ? 'selected' : '' }}>{{ $v }}</option>
          @endforeach
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <button type="submit" class="btn btn-success" id="btnUpdate" data-loading-text="Mengupdate...">
        <span class="label">Update</span>
        <span class="spinner-border spinner-border-sm d-none ms-1" role="status" aria-hidden="true"></span>
      </button>
      <a href="{{ route('rujukan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const rs  = document.getElementById('rs_tujuan_id');
  const dr  = document.getElementById('dokter_tujuan_id');
  const cc  = document.getElementById('dokter_cc_ids');
  const url = rs.getAttribute('data-url');

  // Init Select2 (jika tersedia)
  if (window.jQuery && jQuery.fn.select2 && !jQuery(cc).hasClass('select2-hidden-accessible')) {
    jQuery(cc).select2({ width:'100%', placeholder: jQuery(cc).data('placeholder') });
  }

  function fillSelect(el, items, selectedIds = []) {
    const sel = selectedIds.map(String);
    el.innerHTML = '';
    items.forEach(i => {
      const o = document.createElement('option');
      o.value = i.id;
      o.textContent = i.name;
      if (sel.includes(String(i.id))) o.selected = true;
      el.appendChild(o);
    });
  }

  function selectedFromDataset(node) {
    try { return JSON.parse(node.dataset.selected || '[]'); }
    catch { return []; }
  }

  function setSelectedDataset(node, ids) {
    node.dataset.selected = JSON.stringify(ids || []);
  }

  async function reloadFromServer() {
    const rsId = rs.value;
    if (!rsId) return;

    // kosongkan dulu (hindari artefak)
    fillSelect(dr, [], []);
    fillSelect(cc, [], []);
    if (window.jQuery && jQuery.fn.select2) jQuery(cc).trigger('change.select2');

    const res  = await fetch(url.replace('__ID__', rsId), { headers: {'X-Requested-With':'XMLHttpRequest'} });
    const data = await res.json();
    const list = Array.isArray(data) ? data : (Array.isArray(data?.data) ? data.data : []);

    // preselect single dari old()/model
    const preMain = String(@json(old('dokter_tujuan_id', $rujukan->dokter_tujuan_id)));
    fillSelect(dr, list, preMain ? [preMain] : []);

    // preselect multi dari data-selected (old atau DB)
    const preCcs = selectedFromDataset(cc);
    fillSelect(cc, list, preCcs);
    if (window.jQuery && jQuery.fn.select2) jQuery(cc).trigger('change.select2');
  }

  // PRELOAD pertama: pakai data yang sudah dirender server ($dokter)
  const preload = @json($dokter->map(fn($d)=>['id'=>$d->id,'name'=>$d->name]));
  fillSelect(dr, preload, [String(@json(old('dokter_tujuan_id', $rujukan->dokter_tujuan_id)))]);
  fillSelect(cc, preload, selectedFromDataset(cc));
  if (window.jQuery && jQuery.fn.select2) jQuery(cc).trigger('change.select2');

  // Ganti RS → reset pilihan CC & muat ulang dari server
  rs.addEventListener('change', () => {
    setSelectedDataset(cc, []);  // kosongkan pilihan CC saat RS berubah
    reloadFromServer();
  });

  // Lock hanya tombol submit saat mengirim
  const form = document.getElementById('formRujukanEdit');
  const btn  = document.getElementById('btnUpdate');
  if (form && btn) {
    form.addEventListener('submit', () => {
      btn.disabled = true;
      btn.setAttribute('aria-disabled','true');
      const t = btn.dataset.loadingText || 'Mengupdate...';
      btn.querySelector('.label')?.textContent = t;
      btn.querySelector('.spinner-border')?.classList.remove('d-none');
    });
  }
})();
</script>
@endpush
