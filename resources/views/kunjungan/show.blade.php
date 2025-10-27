@extends('layouts.app')
@section('title','Detail Kunjungan')
@section('content')

<div class="card shadow-sm">
    <div class="card-header bg-info text-white">
        <i class="fas fa-info-circle"></i> Detail Kunjungan
        <a href="{{ route('kunjungan.index') }}" class="btn btn-light btn-sm float-end">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card-body">
        {{-- Info dasar --}}
        <table class="table table-bordered">
            <tr>
                <th>No. Rawat</th>
                <td>{{ $kunjungan->no_rawat }}</td>
            </tr>
            <tr>
                <th>Pasien</th>
                <td>{{ $kunjungan->pasien->no_rkm_medis ?? '-' }} - {{ $kunjungan->pasien->nama ?? '-' }}</td>
            </tr>
            <tr>
                <th>Dokter</th>
                <td>{{ $kunjungan->dokter->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Poli</th>
                <td>{{ $kunjungan->poli }}</td>
            </tr>
            <tr>
                <th>Tanggal & Jam Masuk</th>
                <td>{{ $kunjungan->tanggal_kunjungan }} {{ \Carbon\Carbon::parse($kunjungan->waktu_masuk)->format('H:i') }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if($kunjungan->status_pulang == 1)
                        <span class="badge bg-success">Pulang</span>
                    @else
                        <span class="badge bg-warning text-dark">Rawat</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Keluhan Utama</th>
                <td>{{ $kunjungan->keluhan_utama ?? '-' }}</td>
            </tr>
            <tr>
        <th>Penerima</th>
        <td>{{ $rujukan->penerima->name ?? '-' }}</td>
        </tr>
        </table>

        {{-- Tombol Aksi --}}
        <div class="mb-3">
            <a href="{{ route('kunjungan.edit',$kunjungan->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('kunjungan.destroy',$kunjungan->id) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Hapus kunjungan ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>
            </form>
            @if($kunjungan->status_pulang == 0)
                <form action="{{ route('kunjungan.pulangkan',$kunjungan->id) }}" method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <button class="btn btn-success btn-sm" onclick="return confirm('Tandai pasien sudah pulang?')">
                        <i class="fas fa-sign-out-alt"></i> Pulangkan
                    </button>
                </form>
            @endif
        </div>

        {{-- Riwayat SOAP --}}
        <h5 class="mt-4">Riwayat SOAP</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Petugas</th>
                    <th>Role</th>
                    <th>Subjektif</th>
                    <th>Objektif</th>
                    <th>Assessment</th>
                    <th>Plan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kunjungan->soap as $soap)
                    <tr>
                        <td>{{ $soap->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $soap->user->name ?? '-' }}</td>
                        <td>{{ ucfirst($soap->user->role ?? '-') }}</td>
                        <td>{!! nl2br(e($soap->subjektif ?? '-')) !!}</td>
                        <td>{!! nl2br(e($soap->objektif ?? '-')) !!}</td>
                        <td>{!! nl2br(e($soap->assessment ?? '-')) !!}</td>
                        <td>{!! nl2br(e($soap->plan ?? '-')) !!}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data SOAP.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{-- Berkas Medis --}}
<h5 class="mt-4">Berkas Medis</h5>
<a href="{{ route('berkas.create',['kunjungan_id'=>$kunjungan->id]) }}" class="btn btn-success btn-sm mb-2">
  + Upload Berkas
</a>

@if($kunjungan->berkasMedis->count() > 0)
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>No</th>
        <th>Jenis</th>
        <th>Nama File</th>
        <th>Uploader</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($kunjungan->berkasMedis as $i => $b)
        <tr>
          <td>{{ $i+1 }}</td>
          <td>{{ strtoupper($b->jenis) }}</td>
          <td><a href="{{ asset('storage/'.$b->path) }}" target="_blank">{{ $b->nama_file }}</a></td>
          <td>{{ $b->uploader->name ?? '-' }}</td>
          <td>
            <a href="{{ route('berkas.edit',$b->id) }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('berkas.destroy',$b->id) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Hapus berkas ini?')">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-sm">Hapus</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@else
  <p class="text-muted">Belum ada berkas medis yang diupload.</p>
@endif
    </div>
</div>

@endsection