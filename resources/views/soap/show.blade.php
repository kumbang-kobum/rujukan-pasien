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
                <td>{!! nl2br(e($soap->objektif ?? '-')) !!}</td>
            </tr>
            <tr>
                <th>Assessment</th>
                <td>{!! nl2br(e($soap->assessment ?? '-')) !!}</td>
            </tr>
            <tr>
                <th>Plan</th>
                <td>{!! nl2br(e($soap->plan ?? '-')) !!}</td>
            </tr>
        </table>
    </div>
</div>

@endsection