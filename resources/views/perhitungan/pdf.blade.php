<!DOCTYPE html>
<html>
<head>
    <title>Laporan Prioritas Antrian</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>DAFTAR PRIORITAS SERVIS MOTOR</h2>
        <p>Metode: Weighted Product (WP)</p>
        <hr>
    </div>

    <table>
        <thead>
            <tr>
                <th>Ranking</th>
                <th>No Polisi</th>
                <th>Vektor V (Skor Akhir)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hasilRanking as $key => $row)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $row['no_polisi'] }}</td>
                <td>{{ number_format($row['nilai_v'], 4) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
