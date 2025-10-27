@extends('layouts.app')
@section('title','Daftar Rujukan')
@section('content')

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span><i class="fas fa-random me-2"></i> Daftar Rujukan</span>
        <div class="d-flex gap-2">
            <button class="btn btn-light btn-sm" type="button"
                    data-bs-toggle="collapse" data-bs-target="#filterBar"
                    aria-expanded="{{ request()->hasAny([
                        'keyword','status','rs_asal_id','rs_tujuan_id','dokter_tujuan_id',
                        'created_from','created_to','per_page','sort_by','sort_dir','tujuan_saya'
                    ]) ? 'true':'false' }}">
                🔎 Filter
            </button>
            <a href="{{ route('rujukan.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus"></i> Tambah Rujukan
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif

        {{-- FILTER BAR --}}
        <div class="collapse {{ request()->hasAny([
            'keyword','status','rs_asal_id','rs_tujuan_id','dokter_tujuan_id',
            'created_from','created_to','per_page','sort_by','sort_dir','tujuan_saya'
        ]) ? 'show':'' }}" id="filterBar">
            <form method="GET" action="{{ route('rujukan.index') }}" class="border rounded p-3 mb-3 bg-light">
                <div class="row g-3 align-items-end">

                    <div class="col-12 col-md-4">
                        <label class="form-label mb-1">Cari (No. Rawat / No RM / Nama / Alasan / Catatan)</label>
                        <input type="text" name="keyword" class="form-control"
                               value="{{ request('keyword') }}" placeholder="cth: 2025/10/01/00012 atau Siti / nyeri dada">
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label mb-1">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="menunggu" @selected(request('status')==='menunggu')>Menunggu</option>
                            <option value="diterima" @selected(request('status')==='diterima')>Diterima</option>
                            <option value="ditolak"  @selected(request('status')==='ditolak')>Ditolak</option>
                        </select>
                    </div>

                    <div class="col-6 col-md-3">
                        <label class="form-label mb-1">RS Asal</label>
                        <select name="rs_asal_id" class="form-select">
                            <option value="">Semua RS</option>
                            @foreach(($rsList ?? collect()) as $rs)
                                <option value="{{ $rs->id }}" @selected((string)$rs->id === request('rs_asal_id'))>
                                    {{ $rs->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-3">
                        <label class="form-label mb-1">RS Tujuan</label>
                        <select name="rs_tujuan_id" id="filter_rs_tujuan" class="form-select">
                            <option value="">Semua RS</option>
                            @foreach(($rsList ?? collect()) as $rs)
                                <option value="{{ $rs->id }}" @selected((string)$rs->id === request('rs_tujuan_id'))>
                                    {{ $rs->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-3">
                        <label class="form-label mb-1">Dokter Tujuan</label>
                        <select name="dokter_tujuan_id" id="filter_dokter_tujuan" class="form-select" {{ request('rs_tujuan_id') ? '' : 'disabled' }}>
                            <option value="">Semua</option>
                            @foreach(($dokterList ?? collect()) as $d)
                                <option value="{{ $d->id }}" @selected((string)$d->id === request('dokter_tujuan_id'))>
                                    {{ $d->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih RS Tujuan dulu agar daftar dokter muncul.</small>
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label mb-1">Dari Tgl (dibuat)</label>
                        <input type="date" name="created_from" class="form-control" value="{{ request('created_from') }}">
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label mb-1">s/d</label>
                        <input type="date" name="created_to" class="form-control" value="{{ request('created_to') }}">
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" name="tujuan_saya" id="tujuan_saya" value="1"
                                   @checked(request()->boolean('tujuan_saya'))>
                            <label class="form-check-label" for="tujuan_saya">
                                Hanya rujukan <strong>ke RS saya</strong>
                            </label>
                        </div>
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label mb-1">Per Halaman</label>
                        <select name="per_page" class="form-select">
                            @foreach([10,25,50,100] as $pp)
                                <option value="{{ $pp }}" @selected((int)request('per_page',10)===$pp)>{{ $pp }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-2">
                        <label class="form-label mb-1">Urutkan</label>
                        <select name="sort_by" class="form-select">
                            <option value="created_at" @selected(request('sort_by','created_at')==='created_at')>Tanggal Dibuat</option>
                            <option value="status"     @selected(request('sort_by')==='status')>Status</option>
                            <option value="id"         @selected(request('sort_by')==='id')>ID</option>
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
                            <a href="{{ route('rujukan.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>

                </div>
            </form>
        </div>

        {{-- Info ringkas --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <small class="text-muted">
                Menampilkan <strong>{{ $rujukan->firstItem() ?? 0 }}–{{ $rujukan->lastItem() ?? 0 }}</strong>
                dari <strong>{{ $rujukan->total() }}</strong> data.
            </small>
            @if(request()->anyFilled(['keyword','status','rs_asal_id','rs_tujuan_id','dokter_tujuan_id','created_from','created_to','tujuan_saya']))
                <span class="badge bg-info-subtle text-dark border">Filter aktif</span>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-dark sticky-top" style="z-index:1;">
                    <tr class="text-nowrap">
                        <th>No</th>
                        <th>No. Rawat</th>
                        <th>Pasien</th>
                        <th>RS Asal</th>
                        <th>RS Tujuan</th>
                        <th>Dokter Tujuan</th>
                        <th>Status</th>
                        <th>Penerima</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rujukan as $index => $r)
                        <tr>
                            <td>{{ $rujukan->firstItem() + $index }}</td>
                            <td class="fw-semibold">{{ $r->kunjungan->no_rawat ?? $r->kunjungan_id }}</td>
                            <td class="text-truncate" style="max-width:280px">
                                {{ $r->kunjungan->pasien->no_rkm_medis ?? '-' }} — {{ $r->kunjungan->pasien->nama ?? '-' }}
                            </td>
                            <td>{{ $r->rsAsal->nama ?? '-' }}</td>
                            <td>{{ $r->rsTujuan->nama ?? '-' }}</td>
                            <td>{{ $r->dokterTujuan->name ?? '-' }}</td>
                            <td>
                                @if($r->status === 'menunggu')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif($r->status === 'diterima')
                                    <span class="badge bg-success">Diterima</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>{{ $r->penerima->name ?? '-' }}</td>
                            <td class="text-center text-nowrap">
                                <a href="{{ route('rujukan.show',$r->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                <a href="{{ route('rujukan.edit',$r->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                {{-- Hanya ADMIN yang boleh menghapus --}}
                                @if(auth()->check() && auth()->user()->role === 'admin')
                                    <form action="{{ route('rujukan.destroy',$r->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin hapus rujukan ini? Tindakan tidak bisa dibatalkan.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                @endif

                                @php
                                    $canManage = auth()->check()
                                        && (int) auth()->user()->rumah_sakit_id === (int) $r->rumah_sakit_tujuan_id;
                                @endphp
                                @if($r->status === 'menunggu' && $canManage)
                                    <form action="{{ route('rujukan.ubahStatus',['rujukan'=>$r->id,'status'=>'diterima']) }}"
                                          method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-success btn-sm">Terima</button>
                                    </form>
                                    <form action="{{ route('rujukan.ubahStatus',['rujukan'=>$r->id,'status'=>'ditolak']) }}"
                                          method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-outline-danger btn-sm">Tolak</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Belum ada data rujukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $rujukan->appends(request()->query())->links() }}
        </div>
    </div>
</div>

{{-- QoL: auto-submit saat ubah sort/per_page, dan nonaktifkan dokter ketika RS tujuan diganti --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const form = document.querySelector('#filterBar form');
    ['sort_by','sort_dir','per_page'].forEach(n => {
        const el = form.querySelector(`[name="${n}"]`);
        if (el) el.addEventListener('change', () => form.submit());
    });
    const rsT = document.getElementById('filter_rs_tujuan');
    const drT = document.getElementById('filter_dokter_tujuan');
    if (rsT && drT) {
        rsT.addEventListener('change', () => {
            drT.selectedIndex = 0;
            drT.disabled = !rsT.value;
        });
    }
});
</script>
@endpush
@endsection
