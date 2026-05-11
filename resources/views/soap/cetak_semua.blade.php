<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Catatan SOAP — {{ $soap->kunjungan->no_rawat }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            line-height: 1.35;
            color: #111;
        }

        /* Header pasien */
        .header {
            margin-bottom: 8px;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }
        .header h2 {
            font-size: 13px;
            text-align: center;
            margin-bottom: 4px;
        }
        .header-info {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        .header-info td { padding: 1px 6px; }
        .header-info td:first-child { font-weight: bold; width: 90px; }

        /* Tabel SOAP kolom menyamping */
        .soap-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .soap-table th,
        .soap-table td {
            border: 1px solid #aaa;
            padding: 4px 5px;
            vertical-align: top;
        }

        /* Kolom label kiri */
        .col-label {
            background-color: #e8e8e8;
            font-weight: bold;
            font-size: 8.5px;
            width: {{ max(10, round(100 / ($soapList->count() + 5))) + 2 }}%;
            text-align: center;
            vertical-align: middle;
        }

        /* Header kolom SOAP (tanggal + user) */
        .soap-header {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            font-size: 8.5px;
        }
        .soap-header .soap-num {
            font-size: 10px;
            font-weight: bold;
        }
        .soap-header .soap-date { font-size: 8px; }
        .soap-header .soap-user { font-size: 7.5px; color: #ffd; }

        /* Isi SOAP */
        .soap-content {
            font-size: 8.5px;
            white-space: pre-line;
            word-wrap: break-word;
        }
        .vital {
            font-size: 8px;
            color: #444;
        }

        .footer {
            margin-top: 8px;
            font-size: 8px;
            color: #666;
            text-align: right;
        }
    </style>
</head>
<body>

{{-- Header Pasien --}}
<div class="header">
    <h2>Catatan SOAP Pasien</h2>
    <table class="header-info">
        <tr>
            <td>No. Rawat</td>
            <td>: {{ $soap->kunjungan->no_rawat }}</td>
            <td width="20">&nbsp;</td>
            <td width="80" style="font-weight:bold">Pasien</td>
            <td>: {{ $soap->kunjungan->pasien->no_rkm_medis ?? '-' }} — {{ $soap->kunjungan->pasien->nama ?? '-' }}</td>
            <td width="20">&nbsp;</td>
            <td width="70" style="font-weight:bold">Dokter</td>
            <td>: {{ $soap->kunjungan->dokter->name ?? '-' }}</td>
            <td width="20">&nbsp;</td>
            <td style="text-align:right; color:#555">Dicetak: {{ now()->format('d/m/Y H:i') }}</td>
        </tr>
    </table>
</div>

{{-- Tabel Kolom SOAP --}}
@php
    $n = $soapList->count();
    // Lebar kolom SOAP dalam % dari sisa setelah kolom label
    // Landscape A4 ~267mm usable; kolom label ~15%
    $labelPct  = 12;
    $soapPct   = $n > 0 ? round((100 - $labelPct) / $n, 1) : (100 - $labelPct);
@endphp

<table class="soap-table">
    {{-- Baris header: label + tiap SOAP --}}
    <thead>
        <tr>
            <th class="col-label" style="width:{{ $labelPct }}%">Keterangan</th>
            @foreach($soapList as $s)
                <th class="soap-header" style="width:{{ $soapPct }}%">
                    <div class="soap-num">SOAP #{{ $loop->iteration }}</div>
                    <div class="soap-date">{{ $s->created_at->format('d/m/Y H:i') }}</div>
                    <div class="soap-user">{{ $s->user?->name ?? '-' }}</div>
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        {{-- Tanda Vital --}}
        <tr>
            <th class="col-label">TD / MAP</th>
            @foreach($soapList as $s)
                <td class="vital">
                    @if($s->td_sys || $s->td_dia)
                        {{ $s->td_sys ?? '?' }}/{{ $s->td_dia ?? '?' }} mmHg
                        @if($s->map) — MAP {{ $s->map }} @endif
                    @else
                        —
                    @endif
                </td>
            @endforeach
        </tr>
        {{-- Subjektif --}}
        <tr>
            <th class="col-label">Subjektif<br>(S)</th>
            @foreach($soapList as $s)
                <td class="soap-content">{{ $s->subjektif ?: '—' }}</td>
            @endforeach
        </tr>
        {{-- Objektif --}}
        <tr>
            <th class="col-label">Objektif<br>(O)</th>
            @foreach($soapList as $s)
                <td class="soap-content">{{ $s->objektif ?: '—' }}</td>
            @endforeach
        </tr>
        {{-- Assessment --}}
        <tr>
            <th class="col-label">Assessment<br>(A)</th>
            @foreach($soapList as $s)
                <td class="soap-content">{{ $s->assessment ?: '—' }}</td>
            @endforeach
        </tr>
        {{-- Plan --}}
        <tr>
            <th class="col-label">Plan<br>(P)</th>
            @foreach($soapList as $s)
                <td class="soap-content">{{ $s->plan ?: '—' }}</td>
            @endforeach
        </tr>
        {{-- Advice --}}
        <tr>
            <th class="col-label">Advice</th>
            @foreach($soapList as $s)
                <td class="soap-content">{{ $s->advice ?: '—' }}</td>
            @endforeach
        </tr>
    </tbody>
</table>

<div class="footer">
    Total {{ $soapList->count() }} catatan SOAP untuk kunjungan {{ $soap->kunjungan->no_rawat }}
</div>

</body>
</html>
