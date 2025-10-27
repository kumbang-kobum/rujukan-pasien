<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kunjungan Pasien</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h3 style="text-align:center">Laporan Kunjungan Pasien</h3>
    <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Rawat</th>
                <th>Pasien</th>
                <th>Dokter</th>
                <th>Poli</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Keluhan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kunjungan as $i => $k)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $k->no_rawat }}</td>
                <td>{{ $k->pasien->no_rkm_medis ?? '' }} - {{ $k->pasien->nama ?? '' }}</td>
                <td>{{ $k->dokter->name ?? '-' }}</td>
                <td>{{ $k->poli }}</td>
                <td>{{ $k->tanggal_kunjungan }} {{ \Carbon\Carbon::parse($k->waktu_masuk)->format('H:i') }}</td>
                <td>{{ $k->status_pulang ? 'Pulang' : 'Rawat' }}</td>
                <td>{{ $k->keluhan_utama ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>