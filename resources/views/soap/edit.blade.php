@extends('layouts.app')
@section('title','Edit SOAP')
@section('content')
<div class="card shadow-sm">
  <div class="card-header bg-warning text-dark">Edit SOAP #{{ $soap->id }}</div>
  <div class="card-body">
    <form action="{{ route('soap.update', $soap->id) }}" method="POST" enctype="multipart/form-data">
      @csrf @method('PUT')

      {{-- Template SOAP --}}
      @php($cfg = config('soap_templates'))
      @php($tpls = collect($cfg)->filter(fn($v)=>is_array($v) && isset($v['label']))->all())
      @php($rsOpts = $cfg['riwayat_sekarang_options'] ?? [
          'Sakit kepala','Pandangan kabur','Nyeri epigastrium','Mual muntah','Bengkak wajah-tangan-tungkai','Kejang'
      ])

      <div class="mb-3">
        <label class="form-label">Pasien</label>
        <input type="hidden" name="kunjungan_id" value="{{ $soap->kunjungan_id }}">
        <select class="form-control" disabled aria-hidden="true">
          @foreach($kunjungan as $k)
            <option value="{{ $k->id }}" {{ $soap->kunjungan_id == $k->id ? 'selected' : '' }}>
              @if(isset($myRsId) && (int)$k->rumah_sakit_id !== $myRsId)
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
      </div>

      <div class="mb-3">
        <label class="form-label">Subjektif</label>
        <textarea name="subjektif" class="form-control" rows="4">{{ old('subjektif', $soap->subjektif) }}</textarea>
      </div>

      <div class="mb-3">
          <label>Objektif</label>
          <div class="border rounded p-3 mb-3">
            <label class="form-label fw-semibold">Tanda Vital (otomatis hitung MAP)</label>
            <div class="row g-2 align-items-end">
              <div class="col-6 col-md-3">
                <label class="form-label">TD Sistolik (mmHg)</label>
                <input type="number" min="40" max="300" name="td_sys"
                       class="form-control" value="{{ old('td_sys', $soap->td_sys ?? '') }}">
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label">TD Diastolik (mmHg)</label>
                <input type="number" min="20" max="200" name="td_dia"
                       class="form-control" value="{{ old('td_dia', $soap->td_dia ?? '') }}">
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label">MAP (mmHg)</label>
                <input type="number" name="map" class="form-control"
                       value="{{ old('map', $soap->map ?? '') }}" readonly>
              </div>
            </div>
          </div>
          
          {{-- Lampiran lama --}}
            @if($soap->relationLoaded('berkas') ? $soap->berkas->count() : $soap->berkas()->count())
              <div class="mb-3">
                <label class="form-label">Lampiran lama</label>
            
                @foreach($soap->berkas as $b)
                  <div class="row g-2 align-items-start mb-2">
                    <div class="col-12 col-md-3">
                      <select name="berkas_lama[{{ $b->id }}][kategori]" class="form-select form-select-sm">
                        <option value="USG"  @selected($b->kategori==='USG')>USG</option>
                        <option value="LAB"  @selected($b->kategori==='LAB')>LAB</option>
                        <option value="LAIN" @selected($b->kategori==='LAIN')>LAIN</option>
                      </select>
                    </div>
            
                    <div class="col-12 col-md-6">
                      <div class="small text-muted mb-1">
                        File saat ini:
                        <a href="{{ route('berkas.file', $b) }}" target="_blank">
                          {{ $b->nama_file }}
                        </a>
                      </div>
                      <input type="file"
                             name="berkas_lama[{{ $b->id }}][file]"
                             class="form-control form-control-sm"
                             accept="image/*,application/pdf">
                      <div class="form-text">Kosongkan jika tidak ingin ganti file.</div>
                    </div>
            
                    <div class="col-12 col-md-2 d-flex align-items-center">
                      <label class="form-check">
                        <input class="form-check-input" type="checkbox"
                               name="berkas_lama[{{ $b->id }}][_delete]" value="1">
                        <span class="ms-1">Hapus</span>
                      </label>
                    </div>
                  </div>
                @endforeach
              </div>
            @endif

          {{-- perbaikan: tambahkan fallback ke $soap->objektif --}}
          <textarea name="objektif" class="form-control" rows="4">{{ old('objektif', $soap->objektif) }}</textarea>
          <div class="my-3">
              <button type="button" id="btnTplUSG" class="btn btn-outline-info ms-auto">
              Isi Template USG → Objektif
              </button>
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
        
            <div id="dxChips" class="d-flex flex-wrap gap-2"></div>
          </div>
        
          <textarea name="assessment" class="form-control" rows="3">{{ old('assessment', $soap->assessment) }}</textarea>
      </div>


      <div class="mb-3">
        <label class="form-label">Plan</label>
        <textarea name="plan" class="form-control" rows="3">{{ old('plan', $soap->plan) }}</textarea>
      </div>
      
      <div class="mb-3">
        <label class="form-label">Advice</label>
        <textarea name="advice" class="form-control" rows="3">{{ old('advice', $soap->advice) }}</textarea>
      </div>

      <button type="submit" class="btn btn-success">Update</button>
      <a href="{{ route('soap.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>

@if($berkasKunjungan->isNotEmpty())
  <hr class="my-4">
  <h5>Berkas Medis (kunjungan ini)</h5>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>No</th>
        <th>Kategori</th>
        <th>Nama File</th>
        <th>Uploader</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($berkasKunjungan as $i => $b)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ strtoupper($b->kategori) }}</td>
          <td>
            <a href="{{ route('berkas.file', $b) }}" target="_blank">
              {{ $b->nama_file }}
            </a>
          </td>
          <td>{{ $b->uploader->name ?? '-' }}</td>
          <td class="text-nowrap">
            <a href="{{ route('berkas.edit', ['berka' => $b->id, 'redirect' => route('soap.show', $soap->id)]) }}"
               class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('berkas.destroy', ['berka' => $b->id]) }}?redirect={{ urlencode(route('soap.show',$soap->id)) }}"
                  method="POST" class="d-inline">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-sm">Hapus</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@else
  <p class="text-muted">Belum ada berkas pada kunjungan ini.</p>
@endif

<script>
  const SOAPCFG = @json(config('soap_templates'));

  // Kumpulkan template bertag 'label'
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

  // Prefill checklist dari teks saat edit
  (function syncChecklistFromText(){
    const txt = subjektifEl.value || '';
    const m = txt.match(/Riwayat sekarang\s*:\s*[-–]\s*(.*?)(?=\nRiwayat dahulu\s*:|\r?\nRiwayat dahulu\s*:|$)/is);
    const line = m ? m[1] : '';
    rsChecks.forEach(cb => cb.checked = line.toLowerCase().includes(cb.value.toLowerCase()));
    if (kejangCb && rsKejang) {
      const mm = line.match(/kejang\s*\((\d+)\s*x?\)/i);
      kejangCb.checked = /kejang/i.test(line);
      rsKejang.disabled = !kejangCb.checked;
      rsKejang.value = mm ? mm[1] : '';
    }
  })();

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

    if (txt.includes(VT_TOKEN)) { objektifTA.value = txt.replaceAll(VT_TOKEN, line); return; }

    const tdRow = /^\s*-\s*TD\s*:\s*.*?(?:\r?\n|$)/mi;
    if (tdRow.test(txt)) { objektifTA.value = txt.replace(tdRow, `- ${line}\n`); return; }

    const marker = /Pemeriksaan fisik\s*:\s*/i;
    objektifTA.value = marker.test(txt) ? txt.replace(marker, m => `${m}\n- ${line}\n`)
                                        : (`- ${line}\n\n` + txt).trim();
  }
  ['td_sys','td_dia'].forEach(n=>{
    document.querySelector(`[name="${n}"]`)?.addEventListener('input', upsertVitalsIntoObjektif);
  });

  /* ===== USG → Objektif (replace-only) ===== */
  const USG_TEMPLATE = (SOAPCFG?.usg_template || "").trim()
    || "USG Obstetri:\n- Biometri: ...\n- AFI/ICA: ...\n- Posisi plasenta: ...\n- Doppler: S/D AU: ... | PI AU: ... | CPR: ...\n";
  function insertUSGTemplate(){
    const ta = objektifTA; let txt = ta.value || '';
    const usgBlock = /(?:^|\n)USG\s+Obstetri\s*:[\s\S]*?(?=(\n(?:Penunjang|Pemeriksaan|Objektif|Assessment|Plan)\s*:|\s*$))/i;
    txt = txt.replace(usgBlock, '\n'); // hapus blok lama
    const penunjang = /(?:^|\n)Penunjang\s*:\s*/i;
    ta.value = penunjang.test(txt)
      ? txt.replace(penunjang, m => `${m}\n${USG_TEMPLATE}\n`)
      : (txt ? (txt.trim() + '\n\n' + USG_TEMPLATE) : USG_TEMPLATE).trim();
  }
  document.getElementById('btnTplUSG')?.addEventListener('click', insertUSGTemplate);

  /* ===== Assessment: dropdown → chips ===== */
  const assessTA  = document.querySelector('[name="assessment"]');
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
      if (el && tpl[name]) el.value = tpl[name]; // replace-only
    });
    applyRiwayatSekarang();
    upsertVitalsIntoObjektif();
  });
</script>
@endsection
