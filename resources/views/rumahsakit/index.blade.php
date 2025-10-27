@extends('layouts.app')
@section('title','Kelola Rumah Sakit')

@section('content')
<div class="card shadow-sm">
  <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <span><i class="fas fa-hospital me-2"></i> Kelola Rumah Sakit</span>
    <a href="{{ route('rumahsakit.create') }}" class="btn btn-light btn-sm">
      <i class="fas fa-plus"></i> Tambah
    </a>
  </div>

  <div class="card-body">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

    <form method="GET" class="row g-2 mb-3">
      <div class="col-md-4">
        <input name="q" value="{{ $q }}" class="form-control" placeholder="Cari nama/alamat/telepon…">
      </div>
      <div class="col-md-3">
        <button class="btn btn-secondary">Cari</button>
        <a href="{{ route('rumahsakit.index') }}" class="btn btn-outline-secondary">Reset</a>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-hover table-sm align-middle">
        <thead class="table-dark">
          <tr>
            <th style="width:60px">#</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Telepon</th>
            <th class="text-center" style="width:180px">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse($items as $i => $rs)
          <tr>
            <td>{{ $items->firstItem() + $i }}</td>
            <td class="fw-semibold">{{ $rs->nama }}</td>
            <td class="text-truncate" style="max-width: 480px">{{ $rs->alamat ?? '-' }}</td>
            <td>{{ $rs->telepon ?? '-' }}</td>
            <td class="text-center text-nowrap">
              <a href="{{ route('rumahsakit.edit',$rs->id) }}" class="btn btn-warning btn-sm">Edit</a>
              <a href="{{ route('rumahsakit.show',$rs->id) }}" class="btn btn-info btn-sm">Lihat</a>
              <form action="{{ route('rumahsakit.destroy',$rs->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Hapus RS ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ $items->links() }}
    </div>
  </div>
</div>
@endsection
