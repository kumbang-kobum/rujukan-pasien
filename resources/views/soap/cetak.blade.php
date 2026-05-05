<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak SOAP</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table.meta {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        table.meta th {
            text-align: left;
            width: 150px;
            padding: 4px;
        }
        table.meta td {
            padding: 4px;
        }
        .section {
            margin-bottom: 15px;
        }
        .section h4 {
            margin: 0 0 5px 0;
            padding: 4px;
            background: #f0f0f0;
        }
        .section p {
            border: 1px solid #ccc;
            min-height: 50px;
            padding: 6px;
        }
        /* hormati karakter newline (\n) sebagai baris baru */
        .multiline {
            white-space: pre-line; line-height: 1.45;
        }
    </style>
</head>
<body>
    <h2>Catatan SOAP</h2>

    <table class="meta">
        <tr>
            <th>No. Rawat</th>
            <td>{{ $soap->kunjungan->no_rawat ?? '-' }}</td>
        </tr>
        <tr>
            <th>Pasien</th>
            <td>{{ $soap->kunjungan->pasien->no_rkm_medis ?? '-' }} - {{ $soap->kunjungan->pasien->nama ?? '-' }}</td>
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
            <th>Tanggal Input</th>
            <td>{{ $soap->created_at->format('d-m-Y H:i') }}</td>
        </tr>
    </table>

    <div class="section">
        <h4>Subjektif</h4>
        <p class="multiline">{{ $soap->subjektif ?? '-' }}</p>
    </div>
    <div class="section">
        <h4>Objektif</h4>
        <p class="multiline">{{ $soap->objektif ?? '-' }}</p>
    </div>
    <div class="section">
        <h4>Assessment</h4>
        <p class="multiline">{{ $soap->assessment ?? '-' }}</p>
    </div>
    <div class="section">
        <h4>Plan</h4>
        <p class="multiline">{{ $soap->plan ?? '-' }}</p>
    </div>
    <div class="section">
        <h4>Advice</h4>
        <p class="multiline">{{ $soap->advice ?? '-' }}</p>
    </div>
    
</body>
</html>