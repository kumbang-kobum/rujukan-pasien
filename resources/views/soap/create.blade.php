@extends('layouts.app')
@section('title','Tambah SOAP')
@section('content')

<div class="card shadow-sm">
  <div class="card-header bg-primary text-white">Tambah SOAP</div>
  <div class="card-body">
    <form action="{{ route('soap.store') }}" method="POST">
      @csrf

      {{-- Template SOAP --}}
      @php($cfg = config('soap_templates'))
      @php($tpls = collect($cfg)->filter(fn($v)=>is_array($v) && isset($v['label']))->all())
      @php($rsOpts = $cfg['riwayat_sekarang_options'] ?? [
          'Sakit kepala','Pandangan kabur','Nyeri epigastrium','Mual muntah','Bengkak wajah-tangan-tungkai','Kejang'
      ])

      <div class="d-flex align-items-center gap-2 mb-3">
        <select id="templateSelect" class="form-select" style="max-width:420px">
          <option value="">— Template SOAP —</option>
          @foreach($tpls as $key => $tpl)
            <option value="{{ $key }}">{{ $tpl['label'] }}</option>
          @endforeach
        </select>

        <div class="form-check ms-2">
          <input id="appendMode" class="form-check-input" type="checkbox">
          <label class="form-check-label" for="appendMode">Tambahkan (jangan timpa)</label>
        </div>

        <button type="button" id="btnApplyTpl" class="btn btn-outline-primary">Isi Otomatis</button>
      </div>

      <div class="mb-3">
        <label>Pasien (No Rawat - No RM - Nama)</label>
        <select name="kunjungan_id" class="form-select" required>
          <option value="">-- Pilih Pasien --</option>
          @foreach($kunjungan as $k)
            <option value="{{ $k->id }}" @selected(old('kunjungan_id')==$k->id)>
              {{ $k->no_rawat }} - {{ $k->pasien->no_rkm_medis }} - {{ $k->pasien->nama }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- === CHECKBOX RIWAYAT SEKARANG === --}}
      <div class="mb-2">
        <label class="form-label fw-semibold">Riwayat sekarang (Checklist)</label>
        <div class="d-flex flex-wrap gap-3 align-items-center" id="rs-now-wrapper">
          @foreach($rsOpts as $opt)
            @if($opt === 'Kejang')
              <label class="form-check form-check-inline d-flex align-items-center">
                <input class="form-check-input rs-now" type="checkbox" value="Kejang">
                <span class="ms-1">Kejang</span>
                <input type="number" min="0" class="form-control form-control-sm ms-2 rs-kejang-count"
                       placeholder="…x" style="width:90px" disabled>
              </label>
            @else
              <label class="form-check form-check-inline">
                <input class="form-check-input rs-now" type="checkbox" value="{{ $opt }}">
                <span class="ms-1">{{ $opt }}</span>
              </label>
            @endif
          @endforeach
        </div>
        <div class="form-text">Centang gejala, angka kejang opsional.</div>
      </div>

      <div class="mb-3">
        <label>Subjektif</label>
        <textarea name="subjektif" class="form-control" rows="4">{{ old('subjektif') }}</textarea>
      </div>

      <div class="mb-3">
        <label>Objektif</label>
        <textarea name="objektif" class="form-control" rows="4">{{ old('objektif') }}</textarea>
      </div>

      <div class="mb-3">
        <label>Assessment</label>
        <textarea name="assessment" class="form-control" rows="3">{{ old('assessment') }}</textarea>
      </div>

      <div class="mb-3">
        <label>Plan</label>
        <textarea name="plan" class="form-control" rows="3">{{ old('plan') }}</textarea>
      </div>

      <button class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>

{{-- JS isi otomatis + sinkron checkbox --}}
<script>
  const SOAPCFG = @json(config('soap_templates'));
  const SOAPTEMPLATES = Object.fromEntries(Object.entries(SOAPCFG).filter(([k,v]) => v && v.label));

  const subjektifEl = document.querySelector('[name="subjektif"]');
  const rsChecks = Array.from(document.querySelectorAll('.rs-now'));
  const rsKejang = document.querySelector('.rs-kejang-count');

  // enable/disable input kejang
  const kejangCb = rsChecks.find(cb => cb.value === 'Kejang');
  if (kejangCb && rsKejang) {
    kejangCb.addEventListener('change', () => {
      rsKejang.disabled = !kejangCb.checked;
      if (!kejangCb.checked) rsKejang.value = '';
      applyRiwayatSekarang();
    });
    rsKejang.addEventListener('input', applyRiwayatSekarang);
  }

  rsChecks.forEach(cb => cb.addEventListener('change', applyRiwayatSekarang));

  function composeRiwayatSekarang() {
    const picked = rsChecks.filter(c => c.checked).map(c => c.value);
    const hasKejang = picked.includes('Kejang');
    if (hasKejang && rsKejang && rsKejang.value) {
      const i = picked.indexOf('Kejang');
      picked[i] = `Kejang (${rsKejang.value}x)`;
    }
    return picked.length ? picked.join(' / ') : '—';
  }

  function replaceRiwayatSekarangBlock(text, rsText) {
    const token = '[[RIWAYAT_SEKARANG]]';
    if (text.includes(token)) {
      return text.replace(token, rsText);
    }

    // 1) Bersihkan SEMUA blok "Riwayat sekarang :" yang sudah ada
    //    (hingga heading berikutnya atau akhir teks)
    const rsBlock = /(?:^|\n)\s*Riwayat sekarang\s*:\s*[\s\S]*?(?=(\n(?:Riwayat dahulu|Riwayat obstetri|Faktor risiko|Keluhan utama|Pemeriksaan|Objektif|Assessment|Plan)\s*:|\s*$))/gi;
    let cleaned = (text || '').replace(rsBlock, '\n');

    // Rapikan newline berlebih
    cleaned = cleaned.replace(/\n{3,}/g, '\n\n').trim();

    const block = `Riwayat sekarang :\n- ${rsText}\n`;

    // 2) Kalau kosong, langsung kembalikan blok
    if (!cleaned) return block.trim();

    // 3) Jika ada "Keluhan utama", sisipkan setelah baris itu.
    const kuMatch = cleaned.match(/(?:^|\n)Keluhan utama\s*:[^\n]*\n?/i);
    if (kuMatch) {
      const idx = cleaned.indexOf(kuMatch[0]) + kuMatch[0].length;
      const out = cleaned.slice(0, idx) + block + cleaned.slice(idx);
      return out.replace(/\n{3,}/g, '\n\n').trim();
    }

    // 4) Kalau tidak ada heading "Keluhan utama", prepend saja.
    return (block + '\n' + cleaned).replace(/\n{3,}/g, '\n\n').trim();
  }

  function applyRiwayatSekarang() {
    const rsText = composeRiwayatSekarang();
    subjektifEl.value = replaceRiwayatSekarangBlock(subjektifEl.value || '', rsText);
  }

  // Isi Otomatis (tetap seperti punyamu) + sinkron checklist
  document.getElementById('btnApplyTpl').addEventListener('click', () => {
    const key = document.getElementById('templateSelect').value;
    if (!key) return;
    const tpl = SOAPTEMPLATES[key];
    const append = document.getElementById('appendMode').checked;

    ['subjektif','objektif','assessment','plan'].forEach(name => {
      const el = document.querySelector(`[name="${name}"]`);
      if (!el || !tpl[name]) return;
      el.value = append && el.value.trim()
        ? (el.value.trim() + '\n\n' + tpl[name])
        : tpl[name];
    });

    // setelah template diisi, terapkan checklist yang sudah dicentang ke Subjektif
    applyRiwayatSekarang();
  });
</script>
@endsection
