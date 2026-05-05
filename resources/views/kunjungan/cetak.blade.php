<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kunjungan</title>
    <style>
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; }
        .title { font-size: 16px; font-weight: bold; margin-bottom: 8px; }
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #999; padding:6px; }
        th { background:#efefef; }
        .small { color:#666; font-size: 11px; }
    </style>
</head>
<body>
    <div class="title">Laporan Kunjungan</div>
    <div class="small">
        Dicetak: {{ now()->format('d/m/Y H:i') }}<br>
        Periode:
        @if(($params['start_date'] ?? null) && ($params['end_date'] ?? null))
            {{ \Carbon\Carbon::parse($params['start_date'])->format('d/m/Y') }}
            s.d.
            {{ \Carbon\Carbon::parse($params['end_date'])->format('d/m/Y') }}
        @else
            {{ now()->format('d/m/Y') }} (Hari ini)
        @endif
        <br>
        @if(!empty($params['pasien'])) Pasien berisi: "{{ $params['pasien'] }}"<br>@endif
        @if(!empty($params['dokter_id'])) Dokter: {{ optional($rows->firstWhere('dokter_id',$params['dokter_id']))->dokter->name ?? '-' }}<br>@endif
        @if(!empty($params['status'])) Status: {{ ucfirst($params['status']) }}<br>@endif
    </div>

    <br>
    <table>
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th>No. Rawat</th>
                <th>No RM / Pasien</th>
                <th>Dokter</th>
                <th>Rawat Jalan / Rawat Inap</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        @forelse($rows as $i => $k)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $k->no_rawat }}</td>
                <td>{{ $k->pasien->no_rkm_medis ?? '-' }} - {{ $k->pasien->nama ?? '-' }}</td>
                <td>{{ $k->dokter->name ?? '-' }}</td>
                <td>{{ $k->rajalranap }}</td>
                <td>{{ $k->tanggal_kunjungan?->format('Y-m-d') }} {{ optional($k->waktu_masuk)->format('H:i') }}</td>
                <td>{{ $k->status_pulang ? 'Pulang' : 'Rawat' }}</td>
            </tr>
        @empty
            <tr><td colspan="7" style="text-align:center">Tidak ada data.</td></tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>