@extends('layouts.app')
@section('title','Daftar Kunjungan')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <div><i class="fas fa-stethoscope me-2"></i>Daftar Kunjungan</div>
        <div class="d-flex gap-2">
            <a href="{{ route('kunjungan.cetak', request()->query()) }}" target="_blank" class="btn btn-light btn-sm">
                <i class="fas fa-print"></i> Cetak
            </a>
            <a href="{{ route('kunjungan.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>
    </div>

    <div class="card-body">
        {{-- Filter --}}
        <form method="GET" action="{{ route('kunjungan.index') }}" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="pasien" value="{{ request('pasien') }}" class="form-control"
                       placeholder="Cari pasien / No RM">
            </div>
            <div class="col-md-3">
                <select name="dokter_id" class="form-control">
                    <option value="">-- Semua Dokter --</option>
                    @foreach($dokter as $d)
                        <option value="{{ $d->id }}" {{ request('dokter_id') == $d->id ? 'selected' : '' }}>
                            {{ $d->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-control">
                    <option value="">-- Semua Status --</option>
                    <option value="rawat"  {{ request('status') == 'rawat'  ? 'selected' : '' }}>Rawat</option>
                    <option value="pulang" {{ request('status') == 'pulang' ? 'selected' : '' }}>Pulang</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>
            <div class="col-md-2">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('kunjungan.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
        </form>

        {{-- Pesan sukses --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Tabel kunjungan --}}
        <div class="table-responsive">
            <table class="table table-hover table-sm align-middle">
                <thead class="table-dark">
                    <tr class="text-nowrap">
                        <th>No</th>
                        <th>No. Rawat</th>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th>Rawat Jalan / Rawat Inap</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Penerima</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($kunjungan as $index => $k)
                    <tr>
                        <td>{{ $kunjungan->firstItem() + $index }}</td>
                        <td class="fw-semibold">{{ $k->no_rawat }}</td>
                        <td class="text-truncate" style="max-width: 280px">
                            {{ $k->pasien->no_rkm_medis ?? '-' }} — {{ $k->pasien->nama ?? '-' }}
                        </td>
                        <td>{{ $k->dokter->name ?? '-' }}</td>
                        <td>{{ $k->rajalranap }}</td>
                        <td class="text-nowrap">
                            {{ \Carbon\Carbon::parse($k->tanggal_kunjungan)->format('d/m/Y') }}
                            @if($k->waktu_masuk)
                                • {{ \Carbon\Carbon::parse($k->waktu_masuk)->format('H:i') }}
                            @endif
                        </td>
                        <td>
                            @if($k->status_pulang == 1)
                                <span class="badge bg-success">Pulang</span>
                            @else
                                <span class="badge bg-warning text-dark">Rawat</span>
                            @endif
                        </td>
                        <td>{{ $k->penerima->name ?? '-' }}</td> {{-- fix: $r -> $k --}}
                        <td class="text-center text-nowrap">
                            <a href="{{ route('kunjungan.show', $k->id) }}" class="btn btn-info btn-sm">Lihat</a>
                            <a href="{{ route('kunjungan.edit', $k->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            {{-- Hanya admin yang boleh menghapus --}}
                            @if(auth()->check() && auth()->user()->isAdmin())
                                <form action="{{ route('kunjungan.destroy', array_merge(['kunjungan' => $k->id], request()->query())) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus kunjungan ini? Tindakan tidak bisa dibatalkan.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            @endif

                            @if($k->status_pulang == 0)
                                <form action="{{ route('kunjungan.pulangkan', array_merge(['kunjungan' => $k->id], request()->query())) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-success btn-sm">Pulangkan</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">Belum ada data kunjungan.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $kunjungan->links() }}
        </div>
    </div>
</div>
@endsection
