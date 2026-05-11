@extends('layouts.app')
@section('title','Tambah SOAP')
@section('content')

<div class="card shadow-sm">
  <div class="card-header bg-primary text-white">Tambah SOAP</div>
  <div class="card-body">
    <form action="{{ route('soap.store') }}" method="POST" enctype="multipart/form-data">
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
            <option value="{{ $k->id }}" @selected((int) old('kunjungan_id', $selectedKunjunganId ?? 0) === (int) $k->id)>
              @if((int)$k->rumah_sakit_id !== $myRsId)
                [Rujukan: {{ $k->rumahSakit?->nama ?? 'RS Lain' }}]
              @endif
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
        <div class="border rounded p-3 mb-3">
          <label class="form-label fw-semibold">Tanda Vital (otomatis hitung MAP)</label>
          <div class="row g-2 align-items-end">
            <div class="col-6 col-md-3">
              <label class="form-label">TD Sistolik (mmHg)</label>
              <input type="number" min="40" max="300" name="td_sys" class="form-control"
                     value="{{ old('td_sys', $soap->td_sys ?? '') }}">
            </div>
            <div class="col-6 col-md-3">
              <label class="form-label">TD Diastolik (mmHg)</label>
              <input type="number" min="20" max="200" name="td_dia" class="form-control"
                     value="{{ old('td_dia', $soap->td_dia ?? '') }}">
            </div>
            <div class="col-6 col-md-3">
              <label class="form-label">MAP (mmHg)</label>
              <input type="number" name="map" class="form-control"
                     value="{{ old('map', $soap->map ?? '') }}" readonly>
            </div>
          </div>
        </div>
        <textarea name="objektif" class="form-control" rows="4">{{ old('objektif') }}</textarea>
        <div class='my-3'>
        <button type="button" id="btnTplUSG" class="btn btn-outline-info ms-auto">
              Isi Template USG → Objektif
        </button>
        </div>
        {{-- Lampiran berkas (tiap file bisa pilih kategori) --}}
        <div class="mb-3">
          <label class="form-label">Lampiran (opsional)</label>
        
          <div id="lampiranList" class="d-flex flex-column gap-2">
            <div class="row g-2 align-items-center lampiran-item">
              <div class="col-12 col-md-3">
                <select name="lampiran_kategori[]" class="form-select form-select-sm">
                  <option value="USG">USG</option>
                  <option value="LAB">LAB</option>
                  <option value="LAIN" selected>LAIN</option>
                </select>
              </div>
              <div class="col-12 col-md-7">
                <input type="file" name="lampiran_file[]" class="form-control form-control-sm"
                       accept="image/*,application/pdf">
              </div>
              <div class="col-12 col-md-2">
                <button type="button" class="btn btn-sm btn-outline-danger w-100 btnDelLampiran">Hapus</button>
              </div>
            </div>
          </div>
        
          <button type="button" id="btnAddLampiran" class="btn btn-sm btn-outline-secondary mt-2">
            + Tambah Lampiran
          </button>
          <div class="form-text">Dukungan gambar (JPG/PNG/WebP) & PDF, maks ±5 MB per file.</div>
        </div>
      </div>

      <div class="mb-3">
          <label class="form-label">Assessment</label>
          @php($assessOpts = config('soap_templates.assessment_presets', []))
          <div class="mb-2 d-flex flex-wrap gap-2 align-items-center">
            <select id="assessSelect" class="form-select" style="max-width:340px">
              <option value="">— Pilih Diagnosis —</option>
              @foreach($assessOpts as $opt)
                <option value="{{ $opt }}">{{ $opt }}</option>
              @endforeach
            </select>
        
            <!-- chips akan muncul di sini -->
            <div id="dxChips" class="d-flex flex-wrap gap-2"></div>
          </div>
        
          <textarea name="assessment" class="form-control" rows="3">{{ old('assessment', $soap->assessment ?? '') }}</textarea>
      </div>

      <div class="mb-3">
        <label>Plan</label>
        <textarea name="plan" class="form-control" rows="3">{{ old('plan') }}</textarea>
      </div>
      
      <div class="mb-3">
        <label>Advice</label>
        <textarea name="advice" class="form-control" rows="3">{{ old('advice') }}</textarea>
      </div>

      <button class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>

{{-- JS isi otomatis + sinkron checkbox --}}
<script>
  const SOAPCFG = @json(config('soap_templates'));
  const SOAPTEMPLATES = {};
  Object.keys(SOAPCFG).forEach(k=>{
    const v = SOAPCFG[k];
    if (v && typeof v === 'object' && v.label) SOAPTEMPLATES[k] = v;
  });

  /* ===== Subjektif: Riwayat Sekarang ===== */
  const subjektifEl = document.querySelector('[name="subjektif"]');
  const rsChecks = Array.from(document.querySelectorAll('.rs-now'));
  const rsKejang = document.querySelector('.rs-kejang-count');
  const kejangCb = rsChecks.find(cb => cb.value === 'Kejang');

  function composeRiwayatSekarang(){
    const picked = rsChecks.filter(c=>c.checked).map(c=>c.value);
    if (picked.includes('Kejang') && rsKejang && rsKejang.value) {
      picked[picked.indexOf('Kejang')] = `Kejang (${rsKejang.value}x)`;
    }
    return picked.length ? picked.join(' / ') : '—';
  }
  function replaceRiwayatSekarangBlock(text, rsText){
    const token = '[[RIWAYAT_SEKARANG]]';
    if ((text||'').includes(token)) return text.replace(token, rsText);
    const rsBlock = /(?:^|\n)\s*Riwayat sekarang\s*:\s*[\s\S]*?(?=(\n(?:Riwayat dahulu|Riwayat obstetri|Faktor risiko|Keluhan utama|Pemeriksaan|Objektif|Assessment|Plan)\s*:|\s*$))/gi;
    let cleaned = (text || '').replace(rsBlock, '\n').replace(/\n{3,}/g, '\n\n').trim();
    const block = `Riwayat sekarang :\n- ${rsText}\n`;
    const kuMatch = cleaned.match(/(?:^|\n)Keluhan utama\s*:[^\n]*\n?/i);
    if (kuMatch) {
      const idx = cleaned.indexOf(kuMatch[0]) + kuMatch[0].length;
      return (cleaned.slice(0,idx) + block + cleaned.slice(idx)).replace(/\n{3,}/g, '\n\n').trim();
    }
    return (block + '\n' + cleaned).replace(/\n{3,}/g, '\n\n').trim();
  }
  function applyRiwayatSekarang(){
    const rsText = composeRiwayatSekarang();
    subjektifEl.value = replaceRiwayatSekarangBlock(subjektifEl.value || '', rsText);
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

  /* ===== Objektif: TD/MAP (replace-only) ===== */
  const objektifTA = document.querySelector('[name="objektif"]');
  const VT = SOAPCFG?.vitals || {};
  const VT_TOKEN = VT.token || '[[VITALS]]';
  const VT_LINE  = VT.line  || 'TD: [[TD_SYS]]/[[TD_DIA]] mmHg, MAP: [[MAP]] mmHg';

  function calcMAP(){
    const sys = parseInt(document.querySelector('[name="td_sys"]').value || 0,10);
    const dia = parseInt(document.querySelector('[name="td_dia"]').value || 0,10);
    const map = (sys && dia) ? Math.round(dia + (sys - dia)/3) : '';
    const mapEl = document.querySelector('[name="map"]');
    if (mapEl) mapEl.value = map;
    return { sys, dia, map };
  }
  function vitalsLine(){
    const { sys, dia, map } = calcMAP();
    return VT_LINE
      .replaceAll('[[TD_SYS]]', sys || '…')
      .replaceAll('[[TD_DIA]]', dia || '…')
      .replaceAll('[[MAP]]',  map || '…');
  }
  function upsertVitalsIntoObjektif(){
    let txt = objektifTA.value || '';
    const line = vitalsLine();

    if (txt.includes(VT_TOKEN)) {
      objektifTA.value = txt.replaceAll(VT_TOKEN, line);
      return;
    }
    const tdRow = /^\s*-\s*TD\s*:\s*.*?(?:\r?\n|$)/mi;
    if (tdRow.test(txt)) {
      objektifTA.value = txt.replace(tdRow, `- ${line}\n`);
      return;
    }
    const marker = /Pemeriksaan fisik\s*:\s*/i;
    objektifTA.value = marker.test(txt)
      ? txt.replace(marker, m => `${m}\n- ${line}\n`)
      : (`- ${line}\n\n` + txt).trim();
  }
  ['td_sys','td_dia'].forEach(n=>{
    document.querySelector(`[name="${n}"]`)?.addEventListener('input', upsertVitalsIntoObjektif);
  });

  /* ===== USG → Objektif ===== */
  const USG_TEMPLATE = (SOAPCFG?.usg_template || "").trim()
    || "USG Obstetri:\n- Biometri: ...\n- AFI/ICA: ...\n- Posisi plasenta: ...\n- Doppler: S/D AU: ... | PI AU: ... | CPR: ...\n";
  function insertUSGTemplate(){
    const ta = objektifTA; let txt = ta.value || '';
    const usgBlock = /(?:^|\n)USG\s+Obstetri\s*:[\s\S]*?(?=(\n(?:Penunjang|Pemeriksaan|Objektif|Assessment|Plan)\s*:|\s*$))/i;
    txt = txt.replace(usgBlock, '\n');
    const penunjang = /(?:^|\n)Penunjang\s*:\s*/i;
    ta.value = penunjang.test(txt)
      ? txt.replace(penunjang, m => `${m}\n${USG_TEMPLATE}\n`)
      : (txt ? (txt.trim() + '\n\n' + USG_TEMPLATE) : USG_TEMPLATE).trim();
  }
  document.getElementById('btnTplUSG')?.addEventListener('click', insertUSGTemplate);

  /* ===== Assessment chips ===== */
  const assessTA  = document.querySelector('[name="assessment"]'); // <— didefinisikan SEKALI saja
  const assessSel = document.getElementById('assessSelect');
  const chipsBox  = document.getElementById('dxChips');
  const selectedDx = new Set();

  (function prefillDx(){
    const txt = assessTA.value || '';
    const m = txt.match(/Diagnosis\s*:\s*([\s\S]*?)(?=\n\s*Diagnosis banding\s*:|\s*$)/i);
    if (m) {
      m[1].split(/\r?\n/).map(s=>s.trim()).filter(Boolean).forEach(line=>{
        const item = line.replace(/^[-•]\s*/, '').replace(/^~~|~~$/g,'').trim();
        if (item) selectedDx.add(item);
      });
    }
    renderChips(); syncAssessment();
  })();
  function renderChips(){
    chipsBox.innerHTML = '';
    Array.from(selectedDx).forEach(val=>{
      const chip = document.createElement('span');
      chip.className = 'chip';
      chip.textContent = val;
      const x = document.createElement('span');
      x.className = 'x'; x.textContent = '×'; x.title = 'Hapus';
      x.onclick = ()=>{ selectedDx.delete(val); renderChips(); syncAssessment(); };
      chip.appendChild(x);
      chipsBox.appendChild(chip);
    });
  }
  function syncAssessment(){
    let base = assessTA.value || '';
    const blockRe = /(?:^|\n)\s*Diagnosis\s*:\s*[\s\S]*?(?=(\n\s*Diagnosis banding\s*:|\s*$))/i;
    base = base.replace(blockRe, '\n');
    const dxBlock = 'Diagnosis :\n' + Array.from(selectedDx).map(v=>'- '+v).join('\n') + '\n';
    if (!/\n\s*Diagnosis banding\s*:/i.test(base)) {
      base = (base.trimEnd() + '\n\nDiagnosis banding: …').trim();
    }
    assessTA.value = base.replace(/\n\s*Diagnosis banding\s*:/i, '\n' + dxBlock + 'Diagnosis banding:');
  }
  assessSel?.addEventListener('change', ()=>{
    const v = assessSel.value; if(!v) return;
    selectedDx.add(v); assessSel.value = '';
    renderChips(); syncAssessment();
  });

  /* ===== Apply Template (replace-only) ===== */
  document.getElementById('btnApplyTpl')?.addEventListener('click', () => {
    const key = document.getElementById('templateSelect').value;
    if (!key) return;
    const tpl = SOAPTEMPLATES[key];
    ['subjektif','objektif','assessment','plan'].forEach(name=>{
      const el = document.querySelector(`[name="${name}"]`);
      if (el && tpl[name]) el.value = tpl[name]; // timpa (bukan append)
    });
    applyRiwayatSekarang();
    upsertVitalsIntoObjektif();
  });
  
   // Repeater lampiran
  const list = document.getElementById('lampiranList');
  document.getElementById('btnAddLampiran').addEventListener('click', () => {
    const item = list.querySelector('.lampiran-item').cloneNode(true);
    // reset input file
    item.querySelector('input[type="file"]').value = '';
    // reset kategori ke LAIN (opsional)
    item.querySelector('select').value = 'LAIN';
    list.appendChild(item);
  });

  document.addEventListener('click', (e)=>{
    if(e.target.classList.contains('btnDelLampiran')){
      const items = list.querySelectorAll('.lampiran-item');
      if(items.length > 1){
        e.target.closest('.lampiran-item').remove();
      } else {
        // kalau tinggal 1, cukup kosongkan isinya
        items[0].querySelector('input[type="file"]').value = '';
        items[0].querySelector('select').value = 'LAIN';
      }
    }
  });
  
</script>
@endsection
