@extends('layouts.app')
@section('title','Tambah Rujukan')

@section('content')
<div class="card shadow-sm">
  <div class="card-header bg-success text-white">+ Tambah Rujukan</div>
  <div class="card-body">
    <form id="formRujukan" method="POST" action="{{ route('rujukan.store') }}">
      @csrf

      {{-- Kunjungan --}}
      <div class="mb-3">
        <label class="form-label">Kunjungan (No Rawat - No RM - Nama)</label>
        <select name="kunjungan_id" class="form-select @error('kunjungan_id') is-invalid @enderror" required>
          <option value="" disabled {{ old('kunjungan_id') ? '' : 'selected' }}>Pilih...</option>
          @foreach($kunjungan as $k)
            <option value="{{ $k->id }}" {{ old('kunjungan_id') == $k->id ? 'selected' : '' }}>
              {{ $k->no_rawat ?? $k->id }} - {{ $k->pasien->no_rkm_medis ?? '-' }} - {{ $k->pasien->nama ?? 'Tanpa Nama' }}
            </option>
          @endforeach
        </select>
        @error('kunjungan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- RS Asal (readonly + hidden) --}}
      <div class="mb-3">
        <label class="form-label">Rumah Sakit Asal</label>
        <input class="form-control" value="{{ auth()->user()->rumahSakit->nama ?? '-' }}" readonly>
        <input type="hidden" name="rumah_sakit_asal_id" value="{{ $rsAsalId }}">
      </div>

      {{-- RS Tujuan --}}
      <div class="mb-3">
        <label class="form-label">Rumah Sakit Tujuan</label>
        <select name="rumah_sakit_tujuan_id" id="rs_tujuan_id" class="form-select" required
                data-url="{{ url('/ajax/dokter-by-rs/__ID__') }}"
                onchange="window.__reloadDokter && window.__reloadDokter()">
          <option value="">-- Pilih RS --</option>
          @foreach($rumahSakit as $rs)
            <option value="{{ $rs->id }}" {{ old('rumah_sakit_tujuan_id')==$rs->id?'selected':'' }}>
              {{ $rs->nama }}
            </option>
          @endforeach
        </select>
        @error('rumah_sakit_tujuan_id')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>
      
      {{-- Dokter Tujuan (utama) --}}
        <div class="mb-3">
          <label class="form-label">Dokter Tujuan (utama)</label>
          <select name="dokter_tujuan_id" id="dokter_tujuan_id" class="form-select" required>
            <option value="">-- Pilih Dokter --</option>
          </select>
          @error('dokter_tujuan_id')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        
        {{-- Dokter Tujuan Tambahan (tembusan, opsional) --}}
        <div class="mb-3">
          <label class="form-label">Dokter Tujuan Tambahan (opsional)</label>
          <select name="dokter_cc_ids[]" id="dokter_cc_ids" class="form-select select2" multiple
                  data-placeholder="Pilih 1 atau lebih dokter">
          </select>
          <small class="text-muted">Email rujukan juga dikirim ke semua dokter di sini.</small>
          @error('dokter_cc_ids.*')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

      {{-- Alasan --}}
      <div class="mb-3">
        <label class="form-label">Alasan</label>
        <input type="text" name="alasan"
               value="{{ old('alasan') }}"
               class="form-control @error('alasan') is-invalid @enderror" required>
        @error('alasan') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- Alasan Rujukan --}}
      <div class="mb-3">
        <label class="form-label">Alasan Rujukan (detail)</label>
        <textarea name="alasan_rujukan" class="form-control @error('alasan_rujukan') is-invalid @enderror" rows="2">{{ old('alasan_rujukan') }}</textarea>
        @error('alasan_rujukan') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- Catatan --}}
      <div class="mb-3">
        <label class="form-label">Catatan</label>
        <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="2">{{ old('catatan') }}</textarea>
        @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <button type="submit" id="btnSimpan" class="btn btn-success" data-loading-text="Menyimpan...">
        <span class="label">Simpan</span>
        <span class="spinner-border spinner-border-sm d-none ms-1" role="status" aria-hidden="true"></span>
      </button>
      <a href="{{ route('rujukan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
  const rs = document.getElementById('rs_tujuan_id');
  const dr = document.getElementById('dokter_tujuan_id');   // single
  const cc = document.getElementById('dokter_cc_ids');      // multi
  if (!rs || !dr || !cc) return;

  // init Select2 (kalau ada)
  if (window.jQuery && jQuery.fn.select2 && !jQuery(cc).hasClass('select2-hidden-accessible')) {
    jQuery(cc).select2({ width: '100%', placeholder: jQuery(cc).data('placeholder') });
  }

  const urlTpl = rs.getAttribute('data-url');
  const preMain = @json(old('dokter_tujuan_id'));
  const preCC   = @json(old('dokter_cc_ids', [])).map(String);

  const on = el => { if (el) { el.disabled = false; el.removeAttribute('disabled');
    if (window.jQuery && jQuery.fn.select2 && jQuery(el).hasClass('select2')) {
      jQuery(el).prop('disabled', false).trigger('change.select2');
    }}};
  const off = el => { if (el) { el.disabled = true; el.setAttribute('disabled','disabled');
    if (window.jQuery && jQuery.fn.select2 && jQuery(el).hasClass('select2')) {
      jQuery(el).prop('disabled', true).trigger('change.select2');
    }}};

  function resetOptions() {
    dr.innerHTML = '<option value="">-- Pilih Dokter --</option>';
    cc.innerHTML = '';
  }

  async function reload(preMainId = preMain, preCcs = preCC) {
    if (!rs.value) { resetOptions(); off(dr); off(cc); return; }
    dr.innerHTML = '<option value="">Memuat…</option>'; off(dr); off(cc);

    try {
      const url = urlTpl.replace('__ID__', rs.value);
      const res = await fetch(url, {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
      });

      const data = await res.json();
      const list = Array.isArray(data) ? data : (Array.isArray(data?.data) ? data.data : []);

      // isi single
      dr.innerHTML = '<option value="">-- Pilih Dokter --</option>';
      list.forEach(d => {
        const opt = document.createElement('option');
        opt.value = d.id; opt.textContent = d.name;
        if (preMainId && String(preMainId) === String(d.id)) opt.selected = true;
        dr.appendChild(opt);
      });
      if (list.length === 0) {
        dr.innerHTML = '<option value="">— Tidak ada dokter di RS ini —</option>';
      }

      // isi multi
      cc.innerHTML = '';
      list.forEach(d => {
        const opt = document.createElement('option');
        opt.value = d.id; opt.textContent = d.name;
        if (preCcs.includes(String(d.id))) opt.selected = true;
        cc.appendChild(opt);
      });
      if (window.jQuery && jQuery.fn.select2) jQuery(cc).trigger('change.select2');

      // setelah sukses, **paksa enable**
      on(dr); on(cc);
    } catch (e) {
      console.error('Load dokter gagal:', e);
      resetOptions();
      // tetap enable supaya user bisa ganti RS / mencoba lagi
      on(dr); on(cc);
    }
  }

  // exposé, biar bisa dipanggil dari atribut onchange
  window.__reloadDokter = reload;

  // load awal (kalau RS sudah terpilih)
  if (rs.value) reload(); else { resetOptions(); off(dr); off(cc); }

  // watchdog: kalau ada script lain yang re-disable, kita paksa enable saat RS ada
  setInterval(() => { if (rs.value) { on(dr); on(cc); } }, 800);

  // kalau RS diganti manual (cadangan selain atribut onchange)
  rs.addEventListener('change', () => reload(null, []));
})();
</script>
@endpush

