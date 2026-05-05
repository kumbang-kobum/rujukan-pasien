@php
    $model = $konsultasi ?? null;
    $selectedKunjunganId = old('kunjungan_id', $model?->kunjungan_id ?? $selectedKunjungan?->id);
    $selectedRsTujuanId = old('rumah_sakit_tujuan_id', $model?->rumah_sakit_tujuan_id);
    $selectedDokterTujuanId = old('dokter_tujuan_id', $model?->dokter_tujuan_id);
@endphp

<form method="POST" action="{{ $formAction }}">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-comment-medical me-2"></i>Data Konsultasi
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Kunjungan</label>
                        <select name="kunjungan_id" class="form-select @error('kunjungan_id') is-invalid @enderror" required>
                            <option value="">Pilih kunjungan</option>
                            @foreach($kunjungan as $item)
                                <option value="{{ $item->id }}" @selected((string) $selectedKunjunganId === (string) $item->id)>
                                    {{ $item->no_rawat }} - {{ $item->pasien->no_rkm_medis ?? '-' }} - {{ $item->pasien->nama ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('kunjungan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Rumah Sakit Tujuan</label>
                            <select
                                name="rumah_sakit_tujuan_id"
                                id="rs_tujuan_id"
                                class="form-select @error('rumah_sakit_tujuan_id') is-invalid @enderror"
                                data-url="{{ url('/ajax/dokter-by-rs/__ID__') }}"
                                required
                            >
                                <option value="">Pilih rumah sakit tujuan</option>
                                @foreach($rumahSakitTujuan as $rs)
                                    <option value="{{ $rs->id }}" @selected((string) $selectedRsTujuanId === (string) $rs->id)>
                                        {{ $rs->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('rumah_sakit_tujuan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dokter Tujuan</label>
                            <select name="dokter_tujuan_id" id="dokter_tujuan_id" class="form-select @error('dokter_tujuan_id') is-invalid @enderror" required>
                                <option value="">Pilih dokter tujuan</option>
                                @foreach($dokterTujuan as $dokter)
                                    <option value="{{ $dokter->id }}" @selected((string) $selectedDokterTujuanId === (string) $dokter->id)>
                                        {{ $dokter->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dokter_tujuan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Judul Konsultasi</label>
                        <input
                            type="text"
                            name="judul"
                            class="form-control @error('judul') is-invalid @enderror"
                            value="{{ old('judul', $model?->judul ?? '') }}"
                            placeholder="Contoh: Konsultasi nyeri dada dengan dugaan ACS"
                            required
                        >
                        @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Alasan Konsultasi</label>
                        <textarea name="alasan_konsultasi" rows="3" class="form-control @error('alasan_konsultasi') is-invalid @enderror" required>{{ old('alasan_konsultasi', $model?->alasan_konsultasi ?? '') }}</textarea>
                        @error('alasan_konsultasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Pertanyaan Klinis</label>
                        <textarea name="pertanyaan_konsultasi" rows="3" class="form-control @error('pertanyaan_konsultasi') is-invalid @enderror">{{ old('pertanyaan_konsultasi', $model?->pertanyaan_konsultasi ?? '') }}</textarea>
                        @error('pertanyaan_konsultasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Ringkasan Klinis</label>
                        <textarea name="ringkasan_klinis" rows="4" class="form-control @error('ringkasan_klinis') is-invalid @enderror">{{ old('ringkasan_klinis', $model?->ringkasan_klinis ?? $latestSoap?->subjektif ?? '') }}</textarea>
                        @error('ringkasan_klinis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mt-0">
                        <div class="col-md-6">
                            <label class="form-label">Diagnosis Kerja</label>
                            <textarea name="diagnosis_kerja" rows="3" class="form-control @error('diagnosis_kerja') is-invalid @enderror">{{ old('diagnosis_kerja', $model?->diagnosis_kerja ?? $latestSoap?->assessment ?? '') }}</textarea>
                            @error('diagnosis_kerja') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Terapi Berjalan</label>
                            <textarea name="terapi_berjalan" rows="3" class="form-control @error('terapi_berjalan') is-invalid @enderror">{{ old('terapi_berjalan', $model?->terapi_berjalan ?? $latestSoap?->plan ?? '') }}</textarea>
                            @error('terapi_berjalan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Hasil Penunjang Relevan</label>
                        <textarea name="hasil_penunjang" rows="3" class="form-control @error('hasil_penunjang') is-invalid @enderror">{{ old('hasil_penunjang', $model?->hasil_penunjang ?? '') }}</textarea>
                        @error('hasil_penunjang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-light fw-semibold">Persetujuan Pasien</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Status Consent</label>
                        <select name="consent_status" id="consent_status" class="form-select @error('consent_status') is-invalid @enderror" required>
                            @foreach($consentOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('consent_status', $model?->consent_status ?? 'menunggu') === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('consent_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div id="consentFields">
                        <div class="mb-3">
                            <label class="form-label">Nama Pemberi Consent</label>
                            <input type="text" name="consent_nama_pemberi" class="form-control @error('consent_nama_pemberi') is-invalid @enderror" value="{{ old('consent_nama_pemberi', $model?->consent_nama_pemberi ?? '') }}">
                            @error('consent_nama_pemberi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hubungan Dengan Pasien</label>
                            <input type="text" name="consent_hubungan" class="form-control @error('consent_hubungan') is-invalid @enderror" value="{{ old('consent_hubungan', $model?->consent_hubungan ?? '') }}" placeholder="Pasien sendiri / Suami / Anak">
                            @error('consent_hubungan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Metode Consent</label>
                            <select name="consent_metode" class="form-select @error('consent_metode') is-invalid @enderror">
                                <option value="">Pilih metode</option>
                                @foreach($consentMethods as $value => $label)
                                    <option value="{{ $value }}" @selected(old('consent_metode', $model?->consent_metode ?? '') === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('consent_metode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Waktu Consent</label>
                            <input type="datetime-local" name="consent_diberikan_pada" class="form-control @error('consent_diberikan_pada') is-invalid @enderror" value="{{ old('consent_diberikan_pada', optional($model?->consent_diberikan_pada)->format('Y-m-d\\TH:i')) }}">
                            @error('consent_diberikan_pada') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Catatan Consent</label>
                            <textarea name="consent_catatan" rows="3" class="form-control @error('consent_catatan') is-invalid @enderror">{{ old('consent_catatan', $model?->consent_catatan ?? '') }}</textarea>
                            @error('consent_catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-light fw-semibold">Ringkasan Kunjungan</div>
                <div class="card-body small">
                    @if($selectedKunjungan)
                        <div><strong>No Rawat:</strong> {{ $selectedKunjungan->no_rawat }}</div>
                        <div><strong>Pasien:</strong> {{ $selectedKunjungan->pasien->nama ?? '-' }}</div>
                        <div><strong>No RM:</strong> {{ $selectedKunjungan->pasien->no_rkm_medis ?? '-' }}</div>
                        <div><strong>Dokter:</strong> {{ $selectedKunjungan->dokter->name ?? '-' }}</div>
                        <div><strong>Keluhan:</strong> {{ $selectedKunjungan->keluhan_utama ?? '-' }}</div>
                    @else
                        <div class="text-muted">Pilih kunjungan dulu agar ringkasan kasus lebih mudah dicek sebelum dikirim.</div>
                    @endif

                    @if($latestSoap)
                        <hr>
                        <div class="fw-semibold mb-1">SOAP Terakhir</div>
                        <div class="mb-1"><strong>Assessment:</strong> {{ $latestSoap->assessment ?? '-' }}</div>
                        <div><strong>Plan:</strong> {{ $latestSoap->plan ?? '-' }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 mt-3">
        <button type="submit" name="submit_action" value="draft" class="btn btn-outline-secondary">
            Simpan Draft
        </button>
        <button type="submit" name="submit_action" value="submit" class="btn btn-success">
            {{ $isEdit ? 'Perbarui & Kirim' : 'Simpan & Kirim' }}
        </button>
        <a href="{{ route('konsultasi.index') }}" class="btn btn-light border">Batal</a>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rsSelect = document.getElementById('rs_tujuan_id');
    const dokterSelect = document.getElementById('dokter_tujuan_id');
    const consentStatus = document.getElementById('consent_status');
    const consentFields = document.getElementById('consentFields');
    const selectedDokterId = @json((string) $selectedDokterTujuanId);

    function toggleConsentFields() {
        if (!consentStatus || !consentFields) {
            return;
        }

        consentFields.style.display = consentStatus.value === 'diberikan' ? 'block' : 'none';
    }

    async function reloadDoctors() {
        if (!rsSelect || !dokterSelect) {
            return;
        }

        if (!rsSelect.value) {
            dokterSelect.innerHTML = '<option value="">Pilih dokter tujuan</option>';
            return;
        }

        dokterSelect.innerHTML = '<option value="">Memuat dokter...</option>';

        try {
            const url = rsSelect.dataset.url.replace('__ID__', rsSelect.value);
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });
            const doctors = await response.json();
            dokterSelect.innerHTML = '<option value="">Pilih dokter tujuan</option>';

            doctors.forEach(function (doctor) {
                const option = document.createElement('option');
                option.value = doctor.id;
                option.textContent = doctor.name;
                if (selectedDokterId && String(doctor.id) === String(selectedDokterId)) {
                    option.selected = true;
                }
                dokterSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Gagal memuat dokter tujuan', error);
            dokterSelect.innerHTML = '<option value="">Dokter tidak dapat dimuat</option>';
        }
    }

    toggleConsentFields();
    if (consentStatus) {
        consentStatus.addEventListener('change', toggleConsentFields);
    }

    if (rsSelect) {
        rsSelect.addEventListener('change', function () {
            dokterSelect.dataset.selected = '';
            reloadDoctors();
        });

        if (rsSelect.value && dokterSelect.options.length <= 1) {
            reloadDoctors();
        }
    }
});
</script>
@endpush
