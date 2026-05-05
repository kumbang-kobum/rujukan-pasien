<?php

return [
    // Opsi yang akan muncul sebagai checkbox untuk "Riwayat sekarang"
    'riwayat_sekarang_options' => [
        'Sakit kepala',
        'Pandangan kabur',
        'Nyeri epigastrium',
        'Mual muntah',
        'Bengkak wajah-tangan-tungkai',
        'Kejang', // punya input frekuensi (…x)
    ],
    
    'vitals' => [
        'token'    => '[[VITALS]]', // penanda untuk disubstitusi
        'line'     => 'TD: [[TD_SYS]]/[[TD_DIA]] mmHg, MAP: [[MAP]] mmHg , N: …/menit, RR: …/menit, Suhu: …°C',
        // opsional kalau mau ikut tampilkan yang lain:
        // 'extra' => 'N: [[N]] x/menit, RR: [[RR]] x/menit, Suhu: [[SUHU]] °C',
    ],

    // === TEMPLATE ===
    'preeklampsia_berat' => [
        'label' => 'Template: Preeklampsia Berat',

        // Subjektif: pakai token [[RIWAYAT_SEKARANG]] (lebih mudah di-replace).
        // Kalau kamu belum sempat ganti, JS di bawah tetap bisa (fallback regex).
        'subjektif' => <<<TXT
Keluhan utama: …
Riwayat sekarang :
- [[RIWAYAT_SEKARANG]]
Riwayat dahulu : HT kronik / DM / ginjal / jantung / autoimun / tidak ada
Riwayat obstetri : ANC rutin ya/tidak; hipertensi sejak TM …; kenaikan BB cepat ya/tidak
Faktor risiko : usia <20/>35, obesitas, riwayat PE, multi-fetal, dsb
TXT,

        'objektif' => <<<TXT
Pemeriksaan fisik :
- Kesadaran: compos mentis / …
- Refleks patella: (+)/(-), edema: …, diuresis: … ml/jam
Penunjang :
- Protein urin: …
- Trombosit: …
- SGOT/SGPT: …
- Kreatinin: …
- LDH: …
- USG/NST/BPP: …
TXT,

        'assessment' => <<<TXT
Diagnosis :
- Preeklampsia dengan/ tanpa severe / Eklampsia / HELLP / lainnya
Diagnosis banding: …
TXT,

        'plan' => <<<TXT
Rencana :
- Rawat inap / ICU bila indikasi
- MgSO₄ sesuai protokol; monitor refleks & diuresis
- Antihipertensi (Labetalol/Nifedipin/Hydralazin)
- Serial lab fungsi hati, ginjal, darah
- Penilaian janin (USG/NST/BPP)
- Pertimbangkan terminasi sesuai usia gestasi & kondisi ibu-janin
- Edukasi keluarga
TXT,
    ],
    
'assessment_presets' => [
    'Preeklampsia berat',
    'Eklampsia',
    'HELLP',
    'Edema paru',
    'Acute Kidney Injury (AKI)',
  ],
  
  // template snippet USG Objektif opsional:
  'usg_template' => "USG Obstetri:\n- Biometri: ...\n- AFI/ICA: ...\n- Posisi plasenta: ...\n- Doppler: S/D AU: ... | PI AU: ... | CPR: ...\n",
  
];
