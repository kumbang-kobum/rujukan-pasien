@extends('layouts.app')
@section('title','Detail SOAP')
@section('content')

<div class="card shadow-sm">
    <div class="card-header bg-info text-white">
        <i class="fas fa-notes-medical"></i> Detail SOAP
        <a href="{{ route('soap.index') }}" class="btn btn-light btn-sm float-end">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('soap.cetak',$soap->id) }}" class="btn btn-primary" target="_blank">Cetak PDF</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th width="200">No. Rawat</th>
                <td>{{ $soap->kunjungan->no_rawat ?? '-' }}</td>
            </tr>
            <tr>
                <th>Pasien</th>
                <td>
                    {{ $soap->kunjungan->pasien->no_rkm_medis ?? '-' }} -
                    {{ $soap->kunjungan->pasien->nama ?? '-' }}
                </td>
            </tr>
            <tr>
                <th>Dokter</th>
                <td>{{ $soap->kunjungan->dokter->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>User Input</th>
                <td>{{ $soap->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal SOAP</th>
                <td>{{ $soap->created_at->format('d-m-Y H:i') }}</td>
            </tr>
            <tr>
                <th>Subjektif</th>
                <td>{!! nl2br(e($soap->subjektif ?? '-')) !!}</td>
            </tr>
            <tr>
              <th>Objektif</th>
              <td>
                {!! nl2br(e($soap->objektif ?? '-')) !!}
                
                @if($soap->berkas->count())
                  <div class="mt-3">
                    <strong>Lampiran (Objektif):</strong>
                    <div class="row g-3 mt-1">
                      @foreach($soap->berkas as $b)
                        @php
                          $url = route('berkas.file', $b);
                          $ext = strtolower(pathinfo($b->path, PATHINFO_EXTENSION));
                          $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','bmp']);
                        @endphp
            
                        <div class="col-6 col-md-3">
                          @if($isImage)
                            <a href="{{ $url }}" target="_blank" rel="noopener">
                              <img src="{{ $url }}" class="img-fluid rounded border" alt="{{ $b->nama_file }}">
                            </a>
                            <div class="small text-muted mt-1">
                              [{{ $b->kategori ?? 'LAIN' }}] {{ $b->nama_file }}
                            </div>
                          @else
                            <a href="{{ $url }}" target="_blank" rel="noopener" class="d-block p-3 border rounded text-center">
                              <i class="far fa-file fa-2x d-block mb-2"></i>
                              {{ $b->nama_file }}
                            </a>
                          @endif
                        </div>
                      @endforeach
                    </div>
                  </div>
                @endif
            
                {{-- Lampiran USG/Lab untuk SOAP ini --}}
                @if($soap->berkas->count())
                  <div class="mt-2">
                    <strong>Lampiran (Objektif):</strong>
                    <ul class="mb-2">
                      @foreach($soap->berkas as $b)
                        <li>
                          [{{ $b->kategori ?? 'LAIN' }}]
                          <a href="{{ route('berkas.file', $b) }}" target="_blank">{{ $b->nama_file }}</a>
                          <small class="text-muted">— {{ $b->uploader->name ?? 'User' }}</small>
                        </li>
                      @endforeach
                    </ul>
                  </div>
                @endif
              </td>
            </tr>
            <tr>
                <th>TD / MAP</th>
                <td>
                    @php($sys=$soap->td_sys) @php($dia=$soap->td_dia) @php($m=$soap->map)
                    {{ $sys && $dia ? "TD: {$sys}/{$dia} mmHg" : '-' }}
                    {{ $m ? " — MAP: {$m} mmHg" : '' }}
                </td>
            </tr>
            <tr>
                <th>Assessment</th>
                <td>{!! nl2br(e($soap->assessment ?? '-')) !!}</td>
            </tr>
            <tr>
                <th>Plan</th>
                <td>{!! nl2br(e($soap->plan ?? '-')) !!}</td>
            </tr>
            <tr>
                <th>Advice</th>
                <td>{!! nl2br(e($soap->advice ?? '-')) !!}</td>
            </tr>
        </table>
    </div>
</div>
@if($berkasKunjungan->isNotEmpty())
  <hr class="my-4">
  <h5>Berkas Medis (kunjungan ini)</h5>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>No</th>
        <th>Kategori</th>
        <th>Nama File</th>
        <th>Uploader</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($berkasKunjungan as $i => $b)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ strtoupper($b->kategori) }}</td>
          <td>
            <a href="{{ route('berkas.file', $b) }}" target="_blank">
              {{ $b->nama_file }}
            </a>
          </td>
          <td>{{ $b->uploader->name ?? '-' }}</td>
          <td class="text-nowrap">
            <a href="{{ route('berkas.edit', ['berka' => $b->id, 'redirect' => route('soap.show', $soap->id)]) }}"
               class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('berkas.destroy', ['berka' => $b->id]) }}?redirect={{ urlencode(route('soap.show',$soap->id)) }}"
                  method="POST" class="d-inline">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-sm">Hapus</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@else
  <p class="text-muted">Belum ada berkas pada kunjungan ini.</p>
@endif

@endsection
