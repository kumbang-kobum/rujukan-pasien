@extends('layouts.app')
@section('title', 'Pasien')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h5 class="mb-0">📋 Data Pasien</h5>
        <div class="d-flex gap-2">
            {{-- Toggle filter --}}
            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filterBar" aria-expanded="{{ request()->hasAny(['keyword','jk','tgl_lahir_from','tgl_lahir_to','sort_by','sort_dir','per_page']) ? 'true':'false' }}">
                🔎 Filter
            </button>
            <a href="{{ route('pasien.create') }}" class="btn btn-light btn-sm">+ Tambah Pasien</a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif

        {{-- FILTER BAR --}}
        <div class="collapse {{ request()->hasAny(['keyword','jk','tgl_lahir_from','tgl_lahir_to','sort_by','sort_dir','per_page']) ? 'show':'' }}" id="filterBar">
            <form method="GET" action="{{ route('pasien.index') }}" class="border rounded p-3 mb-3 bg-light">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label mb-1">Cari (Nama / No RM / NIK)</label>
                        <input type="text" name="keyword" class="form-control"
                               value="{{ request('keyword') }}" placeholder="cth: Siti / 000123 / 1671xxxxxx">
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label mb-1">Jenis Kelamin</label>
                        <select name="jk" class="form-select">
                            <option value="">Semua</option>
                            <option value="L" @selected(request('jk')==='L')>Laki-laki</option>
                            <option value="P" @selected(request('jk')==='P')>Perempuan</option>
                        </select>
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label mb-1">Per Halaman</label>
                        <select name="per_page" class="form-select" onchange="this.form.submit()">
                            @foreach([10,25,50,100] as $pp)
                                <option value="{{ $pp }}" @selected((int)request('per_page',10)===$pp)>{{ $pp }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label mb-1">Urutkan</label>
                        <select name="sort_by" class="form-select">
                            <option value="nama" @selected(request('sort_by','nama')==='nama')>Nama</option>
                            <option value="no_rkm_medis" @selected(request('sort_by')==='no_rkm_medis')>No RM</option>
                            {{-- Ganti 'tanggal_lahir' sesuai kolom di DB (bisa 'tgl_lahir') --}}
                            <option value="tanggal_lahir" @selected(request('sort_by')==='tanggal_lahir')>Tanggal Lahir</option>
                            <option value="created_at" @selected(request('sort_by')==='created_at')>Dibuat</option>
                        </select>
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label mb-1">Arah</label>
                        <select name="sort_dir" class="form-select">
                            <option value="asc" @selected(request('sort_dir','asc')==='asc')>Naik (A→Z)</option>
                            <option value="desc" @selected(request('sort_dir')==='desc')>Turun (Z→A)</option>
                        </select>
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label mb-1">Lahir dari</label>
                        {{-- Ganti name jika kolom berbeda di controller --}}
                        <input type="date" name="tgl_lahir_from" class="form-control" value="{{ request('tgl_lahir_from') }}">
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label mb-1">s/d</label>
                        <input type="date" name="tgl_lahir_to" class="form-control" value="{{ request('tgl_lahir_to') }}">
                    </div>

                    <div class="col-12 col-md-4 text-end ms-auto">
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                Terapkan
                            </button>
                            <a href="{{ route('pasien.index') }}" class="btn btn-outline-secondary" id="btnResetFilter">
                                Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Info ringkas jumlah data --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <small class="text-muted">
                Menampilkan
                <strong>
                    {{ $pasien->firstItem() ? $pasien->firstItem() : 0 }}–{{ $pasien->lastItem() ? $pasien->lastItem() : 0 }}
                </strong>
                dari <strong>{{ $pasien->total() }}</strong> data.
            </small>
            @if(request()->anyFilled(['keyword','jk','tgl_lahir_from','tgl_lahir_to']))
                <span class="badge bg-info-subtle text-dark border">Filter aktif</span>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-sm align-middle">
                <thead class="table-dark sticky-top" style="z-index:1;">
                    <tr class="text-nowrap">
                        <th>No RM</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Tanggal Lahir</th>
                        <th>Tempat Lahir</th>
                        <th>Alamat</th>
                        <th>Jenis Kelamin</th>
                        <th>Telepon</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($pasien as $p)
                    @php
                        $jk = $p->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
                    @endphp
                    <tr>
                        <td class="fw-semibold">{{ $p->no_rkm_medis }}</td>
                        <td class="text-nowrap">{{ $p->nik ?? '-' }}</td>
                        <td class="text-nowrap">{{ $p->nama }}</td>
                        {{-- NOTE: ganti "tanggal_lahir" ke "tgl_lahir" bila nama kolomnya berbeda --}}
                        <td class="text-nowrap">
                            {{ $p->tanggal_lahir ? \Illuminate\Support\Carbon::parse($p->tanggal_lahir)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="text-nowrap">{{ $p->tempat_lahir ?? '-' }}</td>
                        <td class="text-truncate" style="max-width: 260px">{{ $p->alamat ?? '-' }}</td>
                        <td>
                            <span class="badge rounded-pill {{ $p->jenis_kelamin === 'L' ? 'bg-info' : 'bg-secondary' }}">
                                {{ $jk }}
                            </span>
                        </td>
                        <td class="text-nowrap">{{ $p->telepon ?? '-' }}</td>
                        <td class="text-center text-nowrap">
                            <a href="{{ route('pasien.show',$p->id) }}" class="btn btn-sm btn-info">Lihat</a>
                            <a href="{{ route('pasien.edit',$p->id) }}" class="btn btn-sm btn-warning">Edit</a>

                            {{-- Hanya admin yang boleh hapus --}}
                            @if(auth()->check() && auth()->user()->isAdmin())
                                <form action="{{ route('pasien.destroy',$p->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin hapus pasien ini? Tindakan tidak bisa dibatalkan.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            Tidak ada data yang cocok dengan filter.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{-- Pertahankan query saat pindah halaman --}}
            {{ $pasien->appends(request()->query())->links() }}
        </div>
    </div>
</div>

{{-- Sedikit kualitas hidup: submit otomatis saat ubah sort/per_page --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('#filterBar form');
    ['sort_by','sort_dir'].forEach(name => {
        const el = form.querySelector(`[name="${name}"]`);
        if (el) el.addEventListener('change', () => form.submit());
    });
    // Reset filter: biar jelas kembali ke index tanpa query
    const resetBtn = document.getElementById('btnResetFilter');
    if (resetBtn) resetBtn.addEventListener('click', function(e){
        // biarkan anchor bekerja (ke route index tanpa query)
    });
});
</script>
@endsection
