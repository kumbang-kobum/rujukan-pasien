@extends('layouts.app')
@section('title','Edit SOAP')
@section('content')
<div class="card shadow-sm">
  <div class="card-header bg-warning text-dark">Edit SOAP #{{ $soap->id }}</div>
  <div class="card-body">
    <form action="{{ route('soap.update', $soap->id) }}" method="POST">
      @csrf @method('PUT')

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
        <label class="form-label">Pasien</label>
        <select name="kunjungan_id" class="form-control" required>
          @foreach($kunjungan as $k)
            <option value="{{ $k->id }}" {{ $soap->kunjungan_id == $k->id ? 'selected' : '' }}>
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
      </div>

      <div class="mb-3">
        <label class="form-label">Subjektif</label>
        <textarea name="subjektif" class="form-control" rows="4">{{ old('subjektif', $soap->subjektif) }}</textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Objektif</label>
        <textarea name="objektif" class="form-control" rows="4">{{ old('objektif', $soap->objektif) }}</textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Assessment</label>
        <textarea name="assessment" class="form-control" rows="3">{{ old('assessment', $soap->assessment) }}</textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Plan</label>
        <textarea name="plan" class="form-control" rows="3">{{ old('plan', $soap->plan) }}</textarea>
      </div>

      <button type="submit" class="btn btn-success">Update</button>
      <a href="{{ route('soap.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>

<script>
  const SOAPCFG = @json(config('soap_templates'));
  const SOAPTEMPLATES = Object.fromEntries(Object.entries(SOAPCFG).filter(([k,v]) => v && v.label));

  const subjektifEl = document.querySelector('[name="subjektif"]');
  const rsChecks = Array.from(document.querySelectorAll('.rs-now'));
  const rsKejang = document.querySelector('.rs-kejang-count');
  const kejangCb = rsChecks.find(cb => cb.value === 'Kejang');

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
    if (text.includes(token)) return text.replace(token, rsText);
    const re = /Riwayat sekarang\s*:\s*[\s\S]*?(?=Riwayat dahulu\s*:)/i;
    if (re.test(text)) return text.replace(re, `Riwayat sekarang :\n- ${rsText}\n`);
    return (`Riwayat sekarang :\n- ${rsText}\n\n` + text).trim();
  }

  function applyRiwayatSekarang() {
    const rsText = composeRiwayatSekarang();
    subjektifEl.value = replaceRiwayatSekarangBlock(subjektifEl.value || '', rsText);
  }

  // Prefill checkbox dari teks saat edit (best effort)
  function syncChecklistFromText() {
    const txt = subjektifEl.value || '';
    const blockMatch = txt.match(/Riwayat sekarang\s*:\s*[-–]\s*(.*?)(?=\nRiwayat dahulu\s*:|\r?\nRiwayat dahulu\s*:|$)/is);
    const line = blockMatch ? blockMatch[1] : '';
    rsChecks.forEach(cb => {
      const needle = cb.value.toLowerCase();
      cb.checked = line.toLowerCase().includes(needle);
    });
    if (kejangCb && rsKejang) {
      const m = line.match(/kejang\s*\((\d+)\s*x?\)/i);
      kejangCb.checked = /kejang/i.test(line);
      rsKejang.disabled = !kejangCb.checked;
      rsKejang.value = m ? m[1] : '';
    }
  }

  if (kejangCb && rsKejang) {
    kejangCb.addEventListener('change', () => {
      rsKejang.disabled = !kejangCb.checked;
      if (!kejangCb.checked) rsKejang.value = '';
      applyRiwayatSekarang();
    });
    rsKejang.addEventListener('input', applyRiwayatSekarang);
  }
  rsChecks.forEach(cb => cb.addEventListener('change', applyRiwayatSekarang));

  // Jalankan prefill saat halaman buka
  syncChecklistFromText();

  // Isi Otomatis + terapkan checklist
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

    applyRiwayatSekarang();
  });
</script>
@endsection
