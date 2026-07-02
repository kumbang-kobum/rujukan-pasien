# Catatan Perbaikan Bug - Rujukan Pasien

Tanggal: 2 Juli 2026

---

## Ringkasan

Ditemukan **8 bug** dalam project. Seluruh bug telah diperbaiki.

| No | Severity | File | Status |
|----|----------|------|--------|
| 1 | 🔴 High | DashboardController.php | ✅ Fixed |
| 2 | 🔴 High | PasienController.php | ✅ Fixed |
| 3 | 🟡 Low | Kunjungan.php | ✅ Fixed |
| 4 | 🟠 Medium | BerkasMedis.php | ✅ Fixed |
| 5 | 🟠 Medium | PasienController.php | ✅ Fixed |
| 6 | 🟡 Low | soap/edit.blade.php | ✅ Fixed |
| 7 | 🟡 Low | Migration filename | ✅ Fixed |
| 8 | 🟡 Low | BerkasMedisController.php | ✅ Fixed |

---

## Detail Bug & Perbaikan

### Bug 1 — DashboardController: `rujukanTerimaCount` Salah untuk Super Admin

**File:** `app/Http/Controllers/DashboardController.php` (baris 37-39)

**Masalah:**
Untuk user super_admin, nilai `rujukanTerimaCount` menggunakan `RumahSakit::count()` (jumlah total rumah sakit), bukan jumlah rujukan yang diterima. Label di dashboard menampilkan "Rujukan Diterima" tapi angkanya adalah jumlah RS.

**Sebelum:**
```php
$rujukanTerimaCount = $user->isSuperAdmin()
    ? RumahSakit::count()
    : Rujukan::where('rumah_sakit_tujuan_id', $user->rumah_sakit_id)->count();
```

**Sesudah:**
```php
$rujukanTerimaCount = $user->isSuperAdmin()
    ? Rujukan::where('status', 'diterima')->count()
    : Rujukan::where('rumah_sakit_tujuan_id', $user->rumah_sakit_id)->count();
```

---

### Bug 2 — PasienController: Mass Assignment Vulnerability

**File:** `app/Http/Controllers/PasienController.php` (baris 115)

**Masalah:**
`$pasien->update($request->all())` memungkinkan user mengirim field tambahan (misal `no_rkm_medis`) yang tidak divalidasi, sehingga bisa mengubah nomor RM secara ilegal.

**Sebelum:**
```php
$pasien->update($request->all());
```

**Sesudah:**
```php
$pasien->update($request->validated());
```

---

### Bug 3 — Kunjungan Model: Double Semicolon

**File:** `app/Models/Kunjungan.php` (baris 94)

**Masalah:**
Ada double semicolon `;;` di akhir statement. Tidak menyebabkan error tapi merupakan typo.

**Sebelum:**
```php
->orderByDesc('created_at');;
```

**Sesudah:**
```php
->orderByDesc('created_at');
```

---

### Bug 4 — BerkasMedis Model: `$fillable` Berisi Kolom Tidak Ada

**File:** `app/Models/BerkasMedis.php` (baris 16)

**Masalah:**
- `'jenis'` — kolom ini sudah di-drop oleh migration `2025_11_11_081154`
- `'mime'` — kolom ini tidak pernah ada di migration manapun

**Sebelum:**
```php
protected $fillable = [
    'kunjungan_id','soap_id','kategori','nama_file','path','mime','uploader_id'
];
```

**Sesudah:**
```php
protected $fillable = [
    'kunjungan_id','soap_id','kategori','nama_file','path','uploader_id'
];
```

---

### Bug 5 — PasienController: Race Condition pada Generate `no_rkm_medis`

**File:** `app/Http/Controllers/PasienController.php` (method `store`)

**Masalah:**
Nomor RM di-generate di method `create()` dan dikirim via form. Jika dua user membuka form bersamaan, mereka mendapat nomor yang sama. Selain itu, user bisa memanipulasi nilai `no_rkm_medis` dari form.

**Perbaikan:**
- `create()` hanya menampilkan preview nomor RM
- `store()` meng-generate nomor RM secara internal menggunakan `DB::transaction` + `lockForUpdate()` untuk mencegah duplikasi

**Sesudah:**
```php
// Di method store():
$no_rkm_medis = DB::transaction(function () {
    $last = Pasien::lockForUpdate()->orderBy('id', 'desc')->first();
    $nextNo = $last ? intval($last->no_rkm_medis) + 1 : 1;
    return str_pad($nextNo, 6, '0', STR_PAD_LEFT);
});
```

---

### Bug 6 — SOAP Edit: Hidden Input & Disabled Select Nama Sama

**File:** `resources/views/soap/edit.blade.php` (baris 19-20)

**Masalah:**
Ada `<input type="hidden" name="kunjungan_id">` dan `<select name="kunjungan_id" disabled>`. Keduanya punya `name` yang sama. Disabled select tidak akan di-submit, tapi membingungkan dan bisa menyebabkan masalah jika ada JS yang mengaktifkan select tersebut.

**Sebelum:**
```html
<input type="hidden" name="kunjungan_id" value="{{ $soap->kunjungan_id }}">
<select name="kunjungan_id" class="form-control" disabled>
```

**Sesudah:**
```html
<input type="hidden" name="kunjungan_id" value="{{ $soap->kunjungan_id }}">
<select class="form-control" disabled aria-hidden="true">
```

---

### Bug 7 — Nama File Migration Double Extension `.php.php`

**File:** `database/migrations/2025_11_11_081154_add_soap_id_and_kategori_to_berkas_medis.php.php`

**Masalah:**
File migration memiliki ekstensi ganda `.php.php`. Laravel migration runner mungkin tidak mendeteksi file ini dengan benar.

**Perbaikan:** Rename file menjadi `...berkas_medis.php`

---

### Bug 8 — BerkasMedisController: Kode Mati (Commented Code)

**File:** `app/Http/Controllers/BerkasMedisController.php`

**Masalah:**
Terdapat ~73 baris kode yang di-comment out (old create, store, edit, update, destroy methods). Kode mati ini mengganggu readability dan maintenance.

**Perbaikan:** Hapus seluruh commented code.

---

## File yang Diubah

1. `app/Http/Controllers/DashboardController.php`
2. `app/Http/Controllers/PasienController.php`
3. `app/Models/Kunjungan.php`
4. `app/Models/BerkasMedis.php`
5. `resources/views/soap/edit.blade.php`
6. `app/Http/Controllers/BerkasMedisController.php`
7. `database/migrations/2025_11_11_081154_add_soap_id_and_kategori_to_berkas_medis.php` (rename)

---

## Perbaikan Tambahan — Bug Laporan Produksi (2 Juli 2026)

**Laporan:** 8 pasien sudah didaftarkan tapi namanya tidak tampil saat tambah SOAP.

### Bug 9 — SOAP Create/Edit: Filter `status_pulang` Mengexclude Pasien dengan Nilai NULL

**File:** `app/Http/Controllers/SOAPController.php` (method `create` dan `edit`)

**Masalah:**
Query menggunakan `where('status_pulang', 0)` yang hanya menampilkan kunjungan aktif. Namun:
- Kunjungan yang dibuat **sebelum migration `status_pulang` berjalan** memiliki nilai `NULL` (bukan `0`), sehingga tidak ter-include.
- Migration `2025_09_18_044216_add_status_pulang_to_kunjungan_table.php` menambahkan kolom dengan `->default(0)`, tapi di MySQL, `DEFAULT` hanya berlaku untuk **insert baru**, bukan update baris yang sudah ada. Baris existing mendapat `NULL`.

**Sebelum:**
```php
$q->where('status_pulang', 0)
  ->orWhereHas('rujukan', ...);
```

**Sesudah:**
```php
$q->where(function ($sub) {
    $sub->where('status_pulang', 0)
        ->orWhereNull('status_pulang');
})
->orWhereHas('rujukan', ...);
```

**Dampak:** 8 pasien yang kunjungan-nya memiliki `status_pulang = NULL` sekarang muncul di dropdown SOAP.

---

### Bug 10 — SOAP Create View: Tidak Ada Null Safety untuk `$k->pasien`

**File:** `resources/views/soap/create.blade.php` (baris 38-45)

**Masalah:**
Jika relasi `pasien` null (pasien dihapus tapi kunjungan masih ada), `$k->pasien->nama` akan menyebabkan error "Attempt to read property on null".

**Perbaikan:** Tambahkan `@if($k->pasien)` guard di dalam loop.

**Sesudah:**
```blade
@foreach($kunjungan as $k)
  @if($k->pasien)
  <option value="{{ $k->id }}">
    {{ $k->no_rawat }} - {{ $k->pasien->no_rkm_medis }} - {{ $k->pasien->nama }}
  </option>
  @endif
@endforeach
```

---

### Rekomendasi Tambahan

Untuk memastikan data konsisten, jalankan query SQL ini untuk memperbaiki `status_pulang` yang masih NULL:

```sql
UPDATE kunjungan SET status_pulang = 0 WHERE status_pulang IS NULL;
```

---

## File yang Diubah (Perbaikan Tambahan)

1. `app/Http/Controllers/SOAPController.php` — method `create()` dan `edit()`
2. `resources/views/soap/create.blade.php` — null safety untuk pasien
