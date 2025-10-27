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
                data-url="{{ route('ajax.dokter-by-rs', ['rs'=>'__ID__']) }}">
          <option value="">-- Pilih RS --</option>
          @foreach($rumahSakit as $rs)
            <option value="{{ $rs->id }}" {{ old('rumah_sakit_tujuan_id')==$rs->id?'selected':'' }}>
              {{ $rs->nama }}
            </option>
          @endforeach
        </select>
        @error('rumah_sakit_tujuan_id')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>

      {{-- Dokter Tujuan --}}
      <div class="mb-3">
        <label class="form-label">Dokter Tujuan</label>
        <select name="dokter_tujuan_id" id="dokter_tujuan_id" class="form-select" required disabled>
          <option value="">-- Pilih Dokter --</option>
        </select>
        @error('dokter_tujuan_id')<div class="text-danger small">{{ $message }}</div>@enderror
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
(() => {
  // ==== RS → Dokter (AJAX) ====
  const rsSelect = document.getElementById('rs_tujuan_id');
  const drSelect = document.getElementById('dokter_tujuan_id');
  if (rsSelect && drSelect) {
    const urlTpl = rsSelect.getAttribute('data-url');
    function resetDr() {
      drSelect.innerHTML = '<option value="">-- Pilih Dokter --</option>';
      drSelect.disabled = true;
    }
    async function loadDr(rsId, preselect = @json(old('dokter_tujuan_id'))) {
      if (!rsId) { resetDr(); return; }
      try {
        const res  = await fetch(urlTpl.replace('__ID__', rsId), { headers: {'X-Requested-With':'XMLHttpRequest'} });
        const list = await res.json();
        drSelect.innerHTML = '<option value="">-- Pilih Dokter --</option>';
        list.forEach(d => {
          const opt = document.createElement('option');
          opt.value = d.id; opt.textContent = d.name;
          if (preselect && String(preselect) === String(d.id)) opt.selected = true;
          drSelect.appendChild(opt);
        });
        drSelect.disabled = false;
      } catch (e) { console.error(e); resetDr(); }
    }
    rsSelect.addEventListener('change', e => loadDr(e.target.value));
    const oldRs = @json(old('rumah_sakit_tujuan_id'));
    if (oldRs) loadDr(oldRs);
  }

  // ==== Anti double-submit + spinner ====
  const form = document.getElementById('formRujukan');
  const btn  = document.getElementById('btnSimpan');
  if (!form || !btn) return;

  let locked = false;

  function lockUI() {
    if (locked) return;
    locked = true;

    // disable submit buttons
    form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach(b => {
      b.disabled = true;
      b.setAttribute('aria-disabled', 'true');
      b.classList.add('disabled');
      const loadingText = b.dataset.loadingText || 'Menyimpan...';
      if (b.tagName === 'BUTTON') {
        const label = b.querySelector('.label');
        const spin  = b.querySelector('.spinner-border');
        if (label) label.textContent = loadingText;
        if (spin)  spin.classList.remove('d-none');
      } else {
        b.value = loadingText;
      }
    });
  }

  // kunci segera saat tombol diklik (tangkis double click cepat)
  btn.addEventListener('click', () => {
    setTimeout(() => { if (form.checkValidity()) lockUI(); }, 0);
  });

  // kunci saat submit; cegah submit kedua
  form.addEventListener('submit', (e) => {
    if (!form.checkValidity()) { locked = false; return; }
    if (locked) { e.preventDefault(); return false; }
    lockUI();
  });
})();
</script>
@endpush
