@extends('layouts.app')
@section('title','Daftar SOAP')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span class="fw-semibold"><i class="fas fa-notes-medical me-2"></i>Daftar SOAP</span>
        <div class="d-flex gap-2">
            <button class="btn btn-light btn-sm" type="button"
                    data-bs-toggle="collapse" data-bs-target="#filterBar"
                    aria-expanded="{{ request()->hasAny(['keyword','user_id','created_from','created_to','per_page','sort_by','sort_dir']) ? 'true':'false' }}">
                🔎 Filter
            </button>
            <a href="{{ route('soap.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif

        {{-- FILTER BAR --}}
        <div class="collapse {{ request()->hasAny(['keyword','user_id','created_from','created_to','per_page','sort_by','sort_dir']) ? 'show':'' }}" id="filterBar">
            <form method="GET" action="{{ route('soap.index') }}" class="border rounded p-3 mb-3 bg-light">
                <div class="row g-3 align-items-end">

                    <div class="col-12 col-md-4">
                        <label class="form-label mb-1">Cari (No. Rawat / No RM / Nama / Isi SOAP)</label>
                        <input type="text" name="keyword" class="form-control"
                               placeholder="cth: 2025/10/25/00012 atau Siti atau nyeri perut"
                               value="{{ request('keyword') }}">
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label mb-1">User Input</label>
                        <select name="user_id" class="form-select">
                            <option value="">Semua</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" @selected((string)$u->id === request('user_id'))>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label mb-1">Dari Tgl (dibuat)</label>
                        <input type="date" name="created_from" class="form-control" value="{{ request('created_from') }}">
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label mb-1">s/d</label>
                        <input type="date" name="created_to" class="form-control" value="{{ request('created_to') }}">
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
                            <option value="created_at" @selected(request('sort_by','created_at')==='created_at')>Tanggal Dibuat</option>
                            <option value="id" @selected(request('sort_by')==='id')>ID (No)</option>
                        </select>
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label mb-1">Arah</label>
                        <select name="sort_dir" class="form-select">
                            <option value="desc" @selected(request('sort_dir','desc')==='desc')>Terbaru → Lama</option>
                            <option value="asc"  @selected(request('sort_dir')==='asc')>Lama → Terbaru</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-3 ms-auto text-end">
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary">Terapkan</button>
                            <a href="{{ route('soap.index') }}" class="btn btn-outline-secondary" id="btnResetFilter">Reset</a>
                        </div>
                    </div>

                </div>
            </form>
        </div>

        {{-- Info ringkas --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <small class="text-muted">
                Menampilkan
                <strong>{{ $soap->firstItem() ? $soap->firstItem() : 0 }}–{{ $soap->lastItem() ? $soap->lastItem() : 0 }}</strong>
                dari <strong>{{ $soap->total() }}</strong> data.
            </small>
            @if(request()->anyFilled(['keyword','user_id','created_from','created_to']))
                <span class="badge bg-info-subtle text-dark border">Filter aktif</span>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-sm align-middle">
                <thead class="table-dark sticky-top" style="z-index:1;">
                    <tr class="text-nowrap">
                        <th>No</th>
                        <th>No. Rawat</th>
                        <th>Pasien</th>
                        <th>Subjektif</th>
                        <th>Objektif</th>
                        <th>Assessment</th>
                        <th>Plan</th>
                        <th>Advice</th>
                        <th>User Input</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($soap as $index => $s)
                    <tr>
                        <td class="text-nowrap">{{ $soap->firstItem() + $index }}</td>
                        <td class="fw-semibold text-nowrap">{{ $s->kunjungan->no_rawat ?? '-' }}</td>
                        <td class="text-truncate" style="max-width:280px">
                            {{ $s->kunjungan->pasien->no_rkm_medis ?? '-' }} — {{ $s->kunjungan->pasien->nama ?? '-' }}
                        </td>

                        {{-- tampil singkat + tooltip full --}}
                        <td title="{{ $s->subjektif ?? '' }}">{{ \Illuminate\Support\Str::limit($s->subjektif, 80) }}</td>
                        <td title="{{ $s->objektif ?? '' }}">{{ \Illuminate\Support\Str::limit($s->objektif, 80) }}</td>
                        <td title="{{ $s->assessment ?? '' }}">{{ \Illuminate\Support\Str::limit($s->assessment, 80) }}</td>
                        <td title="{{ $s->plan ?? '' }}">{{ \Illuminate\Support\Str::limit($s->plan, 80) }}</td>
                        <td title="{{ $s->advice ?? '' }}">{{ \Illuminate\Support\Str::limit($s->advice, 80) }}</td>

                        <td class="text-nowrap">{{ $s->user->name ?? '-' }}</td>

                        <td class="text-center text-nowrap">
                            <a href="{{ route('soap.show',$s->id) }}" class="btn btn-info btn-sm">Lihat</a>
                            <a href="{{ route('soap.edit',$s->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            @if(auth()->check() && auth()->user()->role === 'admin')
                                <form action="{{ route('soap.destroy',$s->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus data ini? Tindakan tidak bisa dibatalkan.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">Belum ada data SOAP.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $soap->appends(request()->query())->links() }}
        </div>
    </div>
</div>

{{-- QoL: submit otomatis saat ubah sort/per_page --}}
<script>
document.addEventListener('DOMContentLoaded', function(){
    const form = document.querySelector('#filterBar form');
    ['sort_by','sort_dir'].forEach(n => {
        const el = form.querySelector(`[name="${n}"]`);
        if (el) el.addEventListener('change', () => form.submit());
    });
});
</script>
@endsection
