# Rancangan Fitur Konsultasi Antar Dokter Lintas Rumah Sakit

## 1. Posisi fitur

Fitur ini diposisikan sebagai **modul konsultasi klinis lintas fasilitas** yang terpisah dari modul **rujukan resmi**.

Prinsip dasarnya:

- `1 pasien = 1 master patient` berbasis identitas nasional/SATUSEHAT MPI, bukan master pasien per rumah sakit.
- Yang dipisah per organisasi/fasyankes adalah `encounter`, `episode layanan`, `konsultasi`, `lampiran`, dan `rujukan`.
- Konsultasi lintas RS **default-nya hanya mengirim paket ringkas** yang relevan, bukan full chart.
- Jika hasil konsultasi mengarah ke tindak lanjut formal, konsultasi dapat di-`escalate` menjadi **rujukan resmi**.

## 2. Landasan regulasi dan interoperabilitas

Rancangan ini mengikuti poin utama berikut:

- PMK 24/2022 Pasal 21: Rekam Medis Elektronik wajib terhubung/interoperabel dengan platform data kesehatan Kemenkes.
- PMK 24/2022 Pasal 24: transfer isi Rekam Medis Elektronik untuk rujukan dilakukan melalui platform interoperabilitas dan integrasi data kesehatan Kemenkes.
- PMK 24/2022 Pasal 26 ayat (5): penyampaian rekam medis kepada pihak lain dilakukan setelah mendapat persetujuan pasien.
- PMK 24/2022 Pasal 26 ayat (8)-(10): rekam medis yang diberikan ke fasyankes penerima rujukan menjadi bagian dari surat rujukan.
- PMK 24/2022 Pasal 28: seluruh isi RME harus dibuka ke Kemenkes.
- PMK 24/2022 Pasal 29-31: keamanan, hak akses, integritas, ketersediaan, dan tanda tangan elektronik.
- PMK 24/2022 Pasal 32-36: kerahasiaan rekam medis dan pembukaan isi rekam medis harus terbatas sesuai kebutuhan.

Catatan desain:

- PMK 24/2022 mengatur **rujukan** secara eksplisit, tetapi tidak mendefinisikan workflow "pre-referral doctor-to-doctor consultation" sebagai modul tersendiri.
- Karena itu, modul ini ditafsirkan sebagai **pembukaan isi rekam medis secara terbatas atas persetujuan pasien untuk kepentingan pelayanan/pengobatan**.
- Saat statusnya berubah menjadi rujukan resmi, alur wajib pindah ke playbook rujukan formal dan transfer RME sesuai Pasal 24 serta Pasal 26 ayat (8)-(10).

## 3. Prinsip identitas dan referensi SATUSEHAT

Identitas utama yang dipakai:

- Pasien: `Patient/{ihs_number}` dari SATUSEHAT MPI.
- Fasyankes/RS: `Organization/{organization_ihs_number}`.
- Dokter/nakes: `Practitioner/{practitioner_ihs_number}`.
- Keterikatan dokter pada organisasi dan layanan: `PractitionerRole/{id}`.
- Kunjungan layanan: `Encounter/{id}` yang mereferensikan `Patient`, `Organization`, dan `Practitioner`.

Implikasinya ke aplikasi:

- Tabel `pasien` lokal tidak lagi menjadi master identitas global.
- Nomor RM lokal per RS ditempatkan sebagai identifier lokal yang melekat pada pasien per organisasi.
- `users` lokal tetap dipakai untuk autentikasi aplikasi, tetapi harus memetakan ke identitas SATUSEHAT `Practitioner` dan `PractitionerRole`.
- `rumah_sakit` lokal harus memetakan ke SATUSEHAT `Organization`.

## 4. Alur bisnis utama

### 4.1 Skenario dasar

1. Dokter A membuka pasien dari encounter aktif atau encounter terakhir yang relevan.
2. Sistem memastikan pasien sudah memiliki `IHS number`.
3. Jika belum ada `IHS number`, sistem melakukan lookup MPI SATUSEHAT berdasarkan NIK dan data demografis.
4. Dokter A memilih RS tujuan dan dokter B di RS tujuan.
5. Sistem memeriksa apakah sudah ada persetujuan pasien yang valid untuk pembukaan data lintas RS kepada tujuan tersebut.
6. Jika belum ada, sistem meminta consent elektronik pasien/keluarga/pengampu sesuai kondisi hukum pasien.
7. Dokter A menyusun permintaan konsultasi berisi pertanyaan klinis dan paket data minimum.
8. Sistem mengirim konsultasi ke inbox dokter B dan mencatat semua aktivitas ke audit trail.
9. Dokter B dapat memilih:
   - `accept`
   - `reject`
   - `ask_more_info`
10. Jika diterima, diskusi klinis berjalan di thread konsultasi.
11. Jika hanya second opinion, dokter A atau dokter B menutup konsultasi.
12. Jika tindak lanjut formal dibutuhkan, dokter A menekan `escalate_to_referral` dan sistem membentuk objek rujukan resmi dari data konsultasi.

### 4.2 Batasan penting

- Tidak boleh mengirim full chart lintas RS secara default.
- Tidak boleh membuka data lintas RS tanpa consent, kecuali skenario khusus yang memang dibenarkan peraturan.
- Jika konsultasi berubah menjadi rujukan resmi, status dan data harus pindah ke alur rujukan formal.

## 5. Status proses konsultasi

Disarankan membedakan:

- `consultation.status` untuk status utama permintaan.
- `message.status` untuk status pesan individual.

### 5.1 Status utama

| Status | Arti | Aksi berikutnya |
|---|---|---|
| `draft` | Dokter A baru menyusun konsultasi, belum final | edit, hapus, minta consent |
| `awaiting_consent` | Menunggu persetujuan pasien/keluarga | capture consent, cancel |
| `ready_to_send` | Paket dan consent lengkap, siap kirim | submit |
| `submitted` | Konsultasi sudah dikirim ke dokter/RS tujuan | delivered, cancel sebelum dibuka |
| `delivered` | Notifikasi/inbox penerima berhasil | accept, reject, ask_more_info |
| `accepted` | Dokter B menerima kasus untuk dibahas | reply |
| `rejected` | Dokter B menolak konsultasi | closed atau retarget |
| `awaiting_more_info` | Dokter B meminta tambahan data | sender_reply |
| `in_discussion` | Sudah ada balasan klinis dua arah | close atau escalate |
| `answered` | Dokter B sudah memberi jawaban klinis final | close atau escalate |
| `closed` | Konsultasi selesai tanpa rujukan formal | terminal |
| `escalated_to_referral` | Konsultasi dilanjutkan menjadi rujukan resmi | terminal untuk modul konsultasi |
| `cancelled` | Dibatalkan pengirim sebelum diproses | terminal |
| `expired` | Tidak ada respons sampai SLA berakhir | terminal atau resend |

### 5.2 Status pesan

| Status | Arti |
|---|---|
| `sent` | Pesan dibuat dan disimpan |
| `delivered` | Pesan tersedia di inbox pihak tujuan |
| `read` | Pesan sudah dibuka |
| `replied` | Pesan sudah ditindaklanjuti |

### 5.3 Aturan transisi penting

- `draft -> awaiting_consent -> ready_to_send -> submitted`.
- `submitted -> delivered -> accepted/rejected/awaiting_more_info`.
- `accepted -> in_discussion -> answered -> closed`.
- `accepted/in_discussion/answered -> escalated_to_referral`.
- `submitted` hanya bisa `cancelled` bila data belum dibuka oleh pihak tujuan.
- Bila consent dicabut setelah data dibuka, status tidak dihapus; sistem menghentikan disclosure lanjutan dan mencatat revocation di audit trail.

## 6. Paket data minimum yang dipertukarkan

Paket konsultasi harus kecil, terstruktur, dan berbasis kebutuhan klinis.

### 6.1 Identitas dan konteks minimum

- `patient_ihs_number`
- `patient_identifiers`
  - `nik`
  - `mrn_local_asal`
- `patient_name`
- `birth_date`
- `gender`
- `source_organization_ihs`
- `source_practitioner_ihs`
- `source_practitioner_role_id`
- `source_encounter_id`
- `target_organization_ihs`
- `target_practitioner_ihs`
- `urgency`
- `reason_for_consult`
- `consent_id`

### 6.2 Ringkasan klinis minimum

- keluhan utama / masalah aktif
- diagnosis kerja / diagnosis banding
- ringkasan anamnesis yang relevan
- komorbid penting
- alergi penting
- tanda vital yang relevan
- temuan pemeriksaan fisik yang relevan
- hasil penunjang relevan beserta waktu pemeriksaannya
- terapi/obat yang sedang berjalan
- pertanyaan klinis yang ingin dijawab dokter B
- lampiran penting yang dipilih eksplisit

### 6.3 Data yang tidak otomatis dikirim

- seluruh histori rawat jalan/rawat inap
- seluruh catatan SOAP
- seluruh lampiran radiologi/lab
- data administratif yang tidak relevan
- data sensitif yang tidak diperlukan untuk pertanyaan klinis

## 7. Hak akses

### 7.1 Prinsip umum

- Akses berdasarkan `need to know`.
- Scope akses dipisahkan antara `view metadata`, `view clinical summary`, `view attachment`, `reply`, dan `escalate`.
- Semua hak akses harus terkait peran, organisasi, dan keterlibatan langsung pada kasus.

### 7.2 Matriks hak akses

| Peran | Buat | Lihat ringkasan | Lihat lampiran | Balas | Ubah tujuan | Tutup | Eskalasi ke rujukan | Lihat audit |
|---|---|---|---|---|---|---|---|---|
| Dokter A pengirim | Ya | Ya | Ya | Ya | Ya sebelum diterima | Ya | Ya | Metadata kasusnya |
| Dokter B tujuan | Tidak | Ya setelah consent + assignment | Ya sesuai scope consent | Ya | Tidak | Ya | Bisa usulkan, eksekusi oleh pengirim | Metadata kasusnya |
| Dokter CC / reviewer | Tidak | Ya jika ditambahkan | Ya jika diizinkan | Opsional | Tidak | Tidak | Tidak | Tidak |
| Perawat / case manager | Tidak | Metadata saja | Tidak default | Tidak default | Tidak | Tidak | Tidak | Tidak |
| Admin RS / perekam medis | Tidak | Metadata operasional | Tidak default | Tidak | Tidak | Tidak | Tidak | Audit terbatas |
| Compliance / auditor | Tidak | Metadata audit | Hanya melalui proses break-glass | Tidak | Tidak | Tidak | Tidak | Ya |
| Service account integrasi | Tidak | Tidak via UI | Tidak via UI | Tidak | Tidak | Tidak | Tidak | Log teknis saja |

### 7.3 Break-glass

Break-glass hanya boleh dipakai untuk kondisi yang sah secara regulasi, misalnya kondisi darurat tertentu yang dibenarkan kebijakan internal dan peraturan.

Syarat minimal:

- alasan wajib diisi
- persetujuan atasan klinis/compliance bila diperlukan
- audit trail khusus
- notifikasi ke DPO/compliance

## 8. Persetujuan pasien

### 8.1 Model consent yang disarankan

Gunakan consent eksplisit, elektronik, terbatas, dan dapat diaudit.

Field minimal consent:

- `consent_id`
- `patient_ihs_number`
- `grantor_type` = `patient | parent | guardian | next_of_kin`
- `grantor_name`
- `grantor_identifier`
- `relationship_to_patient`
- `source_organization_ihs`
- `target_organization_ihs`
- `target_practitioner_ihs`
- `purpose_of_use` = `clinical_consultation`
- `data_scope`
- `granted_at`
- `expires_at`
- `capture_method` = `signature_pad | OTP | biometric | assisted_registration`
- `evidence_document_id`
- `status` = `active | revoked | expired`

### 8.2 Cakupan consent

Consent sebaiknya memuat:

- siapa yang boleh menerima data
- data apa yang boleh dibuka
- untuk tujuan apa
- selama berapa lama
- apakah boleh melampirkan hasil penunjang
- apakah bila diperlukan boleh dilanjutkan menjadi rujukan resmi

### 8.3 Aturan operasional consent

- Untuk konsultasi lintas RS, consent harus spesifik per tujuan atau minimal per episode layanan.
- Consent generik "untuk seluruh RS" tidak disarankan.
- Jika pasien tidak cakap, consent diberikan oleh keluarga terdekat/pengampu sesuai PMK 24/2022 Pasal 34.
- Jika konsultasi berubah menjadi rujukan resmi dan scope consent awal belum mencakup rujukan, sistem harus meminta consent tambahan.

## 9. Audit trail

Audit trail harus bersifat immutable atau append-only.

### 9.1 Event yang wajib dicatat

- pembuatan draft
- pemilihan RS tujuan dan dokter tujuan
- pengecekan dan hasil validasi consent
- capture consent, revocation, expiry
- pembentukan paket konsultasi
- pengiriman, delivery, read receipt
- accept, reject, ask more info
- setiap balasan klinis
- setiap akses ringkasan klinis
- setiap unduh atau buka lampiran
- setiap perubahan data scope
- break-glass access
- penutupan konsultasi
- eskalasi ke rujukan resmi
- push/pull ke SATUSEHAT atau MPI lookup

### 9.2 Struktur audit minimal

- `audit_id`
- `consultation_id`
- `event_type`
- `event_at`
- `actor_type`
- `actor_user_id`
- `actor_practitioner_ihs`
- `actor_organization_ihs`
- `target_resource_type`
- `target_resource_id`
- `before_json`
- `after_json`
- `ip_address`
- `user_agent`
- `session_id`
- `reason_code`
- `correlation_id`

### 9.3 Keluaran audit

- log operasional untuk supervisor
- log keamanan untuk compliance
- log integrasi untuk troubleshooting SATUSEHAT
- read access report per pasien/per episode

## 10. Rancangan struktur data aplikasi

### 10.1 Master identitas

#### `master_patients`

- `id`
- `patient_ihs_number` unique
- `nik`
- `name`
- `birth_date`
- `gender`
- `satusehat_last_synced_at`
- `mpi_status`
- `raw_satusehat_json`
- timestamps

#### `patient_identifiers`

- `id`
- `master_patient_id`
- `organization_ihs_number`
- `identifier_type` = `mrn_local | bpjs | other`
- `identifier_value`
- `is_active`
- timestamps

#### `organizations`

- `id`
- `organization_ihs_number` unique
- `name`
- `organization_type`
- `address`
- `contact`
- `raw_satusehat_json`
- timestamps

#### `practitioners`

- `id`
- `practitioner_ihs_number` unique
- `nik`
- `name`
- `specialty_code`
- `specialty_name`
- `raw_satusehat_json`
- timestamps

#### `practitioner_roles`

- `id`
- `satusehat_practitioner_role_id`
- `practitioner_id`
- `organization_id`
- `healthcare_service_id` nullable
- `role_code`
- `specialty_code`
- `is_active`
- timestamps

### 10.2 Episode layanan

#### `encounters`

- `id`
- `satusehat_encounter_id`
- `master_patient_id`
- `organization_id`
- `practitioner_role_id`
- `local_visit_number`
- `class_of_service`
- `status`
- `started_at`
- `ended_at`
- `raw_satusehat_json`
- timestamps

### 10.3 Modul konsultasi

#### `consultations`

- `id`
- `consultation_number`
- `master_patient_id`
- `source_encounter_id`
- `source_organization_id`
- `source_practitioner_role_id`
- `target_organization_id`
- `target_practitioner_role_id`
- `consent_id`
- `urgency_code`
- `reason_for_consult`
- `clinical_question`
- `status`
- `submitted_at`
- `accepted_at`
- `answered_at`
- `closed_at`
- `escalated_to_referral_id` nullable
- `cancel_reason`
- timestamps

#### `consultation_packets`

- `id`
- `consultation_id`
- `version`
- `data_scope_code`
- `summary_json`
- `satusehat_refs_json`
- `checksum`
- `created_by_user_id`
- `created_at`

#### `consultation_messages`

- `id`
- `consultation_id`
- `sender_user_id`
- `sender_practitioner_role_id`
- `message_type` = `question | answer | request_more_info | system_note`
- `message_text`
- `status`
- `sent_at`
- `read_at`
- timestamps

#### `consultation_attachments`

- `id`
- `consultation_id`
- `consultation_message_id` nullable
- `document_type`
- `file_name`
- `mime_type`
- `storage_path`
- `satusehat_document_reference_id` nullable
- `is_included_in_scope`
- `uploaded_by_user_id`
- timestamps

#### `consultation_consents`

- `id`
- `master_patient_id`
- `source_organization_id`
- `target_organization_id`
- `target_practitioner_role_id` nullable
- `grantor_type`
- `grantor_name`
- `grantor_identifier`
- `purpose_of_use`
- `data_scope_json`
- `granted_at`
- `expires_at`
- `revoked_at` nullable
- `status`
- `evidence_storage_path`
- timestamps

#### `consultation_audit_logs`

- `id`
- `consultation_id`
- `event_type`
- `event_payload_json`
- `actor_user_id`
- `actor_organization_id`
- `occurred_at`
- `ip_address`
- `user_agent`
- `correlation_id`

### 10.4 Modul rujukan resmi

Tabel `rujukan` tetap dipertahankan sebagai objek terpisah.

Tambahan yang disarankan:

- `origin_consultation_id` nullable
- `satusehat_referral_bundle_id` nullable
- `referral_status`
- `referred_at`
- `accepted_at`

## 11. Contoh payload paket konsultasi

```json
{
  "consultation_number": "KON-2026-000123",
  "patient": {
    "ihs_number": "P12345678901",
    "identifiers": [
      { "type": "nik", "value": "3173xxxxxxxxxxxx" },
      { "type": "mrn_local_asal", "value": "RM-001245" }
    ],
    "name": "Pasien A",
    "birth_date": "1980-05-20",
    "gender": "male"
  },
  "source": {
    "organization": "Organization/1000004",
    "practitioner": "Practitioner/10014784",
    "practitioner_role": "PractitionerRole/7a44b421-677e-45a1-b7c0-5249264a3189",
    "encounter": "Encounter/0a26ca28-0ea3-486d-8fa9-6f9edd37e567"
  },
  "target": {
    "organization": "Organization/1000008",
    "practitioner": "Practitioner/10022891"
  },
  "consent": {
    "consent_id": "CONS-2026-8891",
    "scope": "clinical_consultation_summary_with_selected_attachments",
    "granted_at": "2026-04-10T09:20:00+07:00",
    "expires_at": "2026-04-17T23:59:59+07:00"
  },
  "clinical_summary": {
    "urgency": "urgent",
    "reason_for_consult": "Mohon second opinion kardiologi",
    "working_diagnosis": [
      "ACS rule out NSTEMI"
    ],
    "comorbidities": [
      "DM tipe 2",
      "Hipertensi"
    ],
    "allergies": [
      "Tidak ada alergi obat yang diketahui"
    ],
    "vital_signs": {
      "blood_pressure": "160/95",
      "heart_rate": 112,
      "spo2": 96
    },
    "relevant_findings": "Nyeri dada 3 jam, EKG ada ST depresi ringan",
    "relevant_tests": [
      "EKG 2026-04-10 08:55",
      "Troponin awal 2026-04-10 09:00"
    ],
    "current_therapy": [
      "Oksigen",
      "Aspirin loading dose",
      "Nitrat"
    ],
    "clinical_question": "Apakah perlu transfer segera untuk cath lab atau observasi serial biomarker?"
  },
  "satusehat_refs": [
    { "resourceType": "Condition", "id": "cond-001" },
    { "resourceType": "Observation", "id": "obs-002" },
    { "resourceType": "DiagnosticReport", "id": "dr-003" }
  ]
}
```

## 12. Mapping ke SATUSEHAT/FHIR

### 12.1 Resource referensi utama

- Identitas pasien: `Patient`
- Identitas RS/fasyankes: `Organization`
- Identitas dokter: `Practitioner`
- Keterikatan dokter ke RS/unit layanan: `PractitionerRole`
- Episode kunjungan: `Encounter`

### 12.2 Resource klinis yang paling relevan

- masalah/diagnosis: `Condition`
- alergi: `AllergyIntolerance`
- tanda vital/hasil terukur: `Observation`
- ringkasan hasil penunjang: `DiagnosticReport`
- obat aktif: `MedicationRequest` atau `MedicationStatement`
- ringkasan klinis: `ClinicalImpression` atau `Composition`
- lampiran dokumen: `DocumentReference`

### 12.3 Strategi pertukaran data

Untuk kebutuhan konsultasi, gunakan dua lapis:

1. **App-native consultation packet**
   - cepat ditampilkan
   - terbatas sesuai consent
   - mudah diaudit
2. **FHIR references**
   - menjaga interoperabilitas
   - memudahkan sinkronisasi ke SATUSEHAT
   - menghindari duplikasi tidak perlu

Jadi, sistem tidak bergantung pada full fetch seluruh resource SATUSEHAT saat dokter membuka konsultasi, tetapi tetap menyimpan referensi resource resmi.

## 13. Aturan eskalasi menjadi rujukan resmi

Konsultasi dapat diubah menjadi rujukan resmi bila:

- dokter B menyarankan tindak lanjut di RS tujuan, atau
- dokter A memutuskan pasien perlu dipindahkan/dirujuk setelah konsultasi.

Saat eskalasi:

- sistem membuat record `rujukan` baru dengan `origin_consultation_id`
- ringkasan konsultasi terakhir menjadi bagian dasar surat rujukan
- paket data rujukan mengikuti kebutuhan rujukan formal, bukan lagi paket konsultasi terbatas
- status konsultasi menjadi `escalated_to_referral`
- status rujukan mengikuti workflow rujukan resmi

## 14. Rekomendasi implementasi untuk repo saat ini

Repo saat ini sudah memiliki entitas `Pasien`, `Kunjungan`, `Rujukan`, `RumahSakit`, dan `User`.

### Tahap 1 - Normalisasi identitas

- Tambah `patient_ihs_number` pada pasien
- Tambah `organization_ihs_number` pada rumah sakit
- Tambah `practitioner_ihs_number` dan `practitioner_role_id` pada user dokter
- Tambah `satusehat_encounter_id` pada kunjungan

### Tahap 2 - Pisahkan konsultasi dari rujukan

- Buat tabel `consultations`, `consultation_packets`, `consultation_messages`, `consultation_consents`, `consultation_audit_logs`
- Jangan campurkan status konsultasi ke tabel `rujukan`

### Tahap 3 - Consent dan data scope

- Tambahkan UI capture consent
- Tambahkan pilihan scope data yang dikirim
- Tambahkan redaksi persetujuan yang spesifik lintas RS

### Tahap 4 - Inbox dan diskusi dokter

- Buat inbox dokter tujuan
- Tambahkan aksi `accept`, `reject`, `ask_more_info`, `reply`, `close`, `escalate_to_referral`

### Tahap 5 - Interoperabilitas

- Integrasi lookup MPI pasien
- Sinkronisasi master Organization dan Practitioner
- Simpan referensi FHIR untuk encounter dan resource klinis pendukung

## 15. Keputusan desain final

Keputusan yang paling tepat untuk kasus ini adalah:

- pasien tetap satu master berbasis `Patient/IHS`
- konsultasi dan rujukan adalah dua objek proses yang berbeda
- konsultasi lintas RS memakai consent eksplisit dan disclosure minimum
- rujukan resmi memakai alur formal tersendiri
- semua aktor lintas RS harus direferensikan memakai identitas nasional SATUSEHAT, bukan identifier lokal semata
- semua akses dan pembukaan data harus tercatat di audit trail

## 16. Referensi resmi

- PMK 24 Tahun 2022 tentang Rekam Medis: https://jdih.kemkes.go.id/documents/peraturan-menteri-kesehatan-nomor-24-tahun-2022
- PDF resmi PMK 24/2022: https://jdih.kemkes.go.id/storage/documents/pdfs/2022permenkes024.pdf
- SATUSEHAT MPI pasien dengan NIK: https://satusehat.kemkes.go.id/platform/docs/id/master-data/master-patient-index/pasien-nik/
- SATUSEHAT Patient: https://satusehat.kemkes.go.id/platform/docs/id/fhir/resources/patient/
- SATUSEHAT Encounter: https://satusehat.kemkes.go.id/platform/docs/id/fhir/resources/encounter/
- SATUSEHAT Practitioner: https://satusehat.kemkes.go.id/platform/docs/id/fhir/resources/practitioner/
- SATUSEHAT PractitionerRole API: https://satusehat.kemkes.go.id/platform/docs/id/api-catalogue/integrations/apis/practitioner-role/
- SATUSEHAT Organization API: https://satusehat.kemkes.go.id/platform/docs/id/api-catalogue/onboardings/apis/organization/
