@extends('layouts.app')
@section('title','Edit Rujukan')

@section('content')
<div class="card shadow-sm">
  <div class="card-header bg-warning text-white">Edit Rujukan</div>

  <div class="card-body">
    {{-- Notifikasi validasi --}}
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('rujukan.update', $rujukan->id) }}">
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

      {{-- RS Asal (readonly + hidden untuk validasi) --}}
      <div class="mb-3">
        <label class="form-label">Rumah Sakit Asal</label>
        <input class="form-control" value="{{ $rujukan->rsAsal->nama ?? '-' }}" readonly>
        <input type="hidden" name="rumah_sakit_asal_id" value="{{ $rsAsalId }}">
      </div>

      {{-- RS Tujuan (sudah diexclude RS asal dari controller) --}}
      <div class="mb-3">
        <label class="form-label">Rumah Sakit Tujuan</label>
        <select name="rumah_sakit_tujuan_id" id="rs_tujuan_id"
                class="form-select @error('rumah_sakit_tujuan_id') is-invalid @enderror" required
                data-url="{{ route('ajax.dokter-by-rs', ['rs' => '__ID__']) }}">
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

      {{-- Dokter Tujuan (dinamis by RS) --}}
      <div class="mb-3">
        <label class="form-label">Dokter Tujuan</label>
        <select name="dokter_tujuan_id" id="dokter_tujuan_id"
                class="form-select @error('dokter_tujuan_id') is-invalid @enderror" required>
          <option value="">-- Pilih Dokter --</option>
          {{-- Preload dokter untuk RS tujuan yang sudah tersimpan --}}
          @foreach($dokter as $d)
            <option value="{{ $d->id }}"
              {{ (int)old('dokter_tujuan_id', $rujukan->dokter_tujuan_id) === (int)$d->id ? 'selected' : '' }}>
              {{ $d->name }}
            </option>
          @endforeach
        </select>
        @error('dokter_tujuan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- Alasan ringkas --}}
      <div class="mb-3">
        <label class="form-label">Alasan</label>
        <input type="text" name="alasan" class="form-control @error('alasan') is-invalid @enderror"
               value="{{ old('alasan', $rujukan->alasan) }}" required>
        @error('alasan') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- Alasan detail (opsional) --}}
      <div class="mb-3">
        <label class="form-label">Alasan Rujukan (detail)</label>
        <textarea name="alasan_rujukan" rows="3" class="form-control @error('alasan_rujukan') is-invalid @enderror">{{ old('alasan_rujukan', $rujukan->alasan_rujukan) }}</textarea>
        @error('alasan_rujukan') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- Catatan (sinkron dengan controller) --}}
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

      <button class="btn btn-success">Update</button>
      <a href="{{ route('rujukan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const rsSelect = document.getElementById('rs_tujuan_id');
  const drSelect = document.getElementById('dokter_tujuan_id');
  const urlTpl   = rsSelect.getAttribute('data-url');

  function resetDr(){
    const keepSelected = "{{ (int)old('dokter_tujuan_id', (int)$rujukan->dokter_tujuan_id) }}";
    drSelect.innerHTML = '<option value="">-- Pilih Dokter --</option>';
    // biarkan tetap enabled; akan diisi ulang setelah fetch
  }

  async function loadDr(rsId, preselect){
    if(!rsId){ resetDr(); return; }
    try{
      const res = await fetch(urlTpl.replace('__ID__', rsId), {headers: {'X-Requested-With':'XMLHttpRequest'}});
      const list = await res.json();
      drSelect.innerHTML = '<option value="">-- Pilih Dokter --</option>';
      list.forEach(d => {
        const opt = document.createElement('option');
        opt.value = d.id; opt.textContent = d.name;
        if(String(preselect) === String(d.id)) opt.selected = true;
        drSelect.appendChild(opt);
      });
    }catch(e){ console.error(e); resetDr(); }
  }

  // reload dokter saat RS berubah
  rsSelect.addEventListener('change', e => loadDr(e.target.value, ''));

  // onload: kalau old RS beda dari yang tersimpan, isi berdasarkan old; jika tidak, biarkan dari server
  const oldRs = "{{ old('rumah_sakit_tujuan_id') }}";
  const selectedDr = "{{ old('dokter_tujuan_id', $rujukan->dokter_tujuan_id) }}";
  if (oldRs && String(oldRs) !== "") {
    loadDr(oldRs, selectedDr);
  }
})();
</script>
@endpush
