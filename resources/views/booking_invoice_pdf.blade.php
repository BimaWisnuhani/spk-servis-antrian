<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Booking - {{ $antrian->kode_antrian }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2e7d32; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #2e7d32; font-size: 18px; }
        .code-box { background: #f1f8e9; border: 1px dashed #4caf50; padding: 12px; text-align: center; margin-bottom: 20px; border-radius: 6px; }
        .code-box h1 { margin: 0; color: #2e7d32; font-size: 28px; letter-spacing: 2px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table td { padding: 6px 4px; vertical-align: top; border-bottom: 1px solid #f0f0f0; }
        .label { width: 38%; font-weight: bold; color: #555; }
        .footer { text-align: center; font-size: 10px; color: #777; border-top: 1px solid #ddd; padding-top: 10px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>UTAMA MOTOR PONOROGO</h2>
        <p style="margin: 2px 0; font-size: 10px;">Jln Raya Ponorogo-Trenggalek, Jintap, Wonoketro, Kec. Jetis, Kabupaten Ponorogo, Jawa Timur</p>
    </div>

    <div class="code-box">
        <span style="font-size: 10px; text-transform: uppercase; color: #555; font-weight: bold;">Nomor Antrian Anda</span>
        <h1>{{ $antrian->kode_antrian }}</h1>
    </div>

    <table>
        <tr>
            <td class="label">Nama Pelanggan</td>
            <td>: {{ $antrian->nama_pelanggan }}</td>
        </tr>
        <tr>
            <td class="label">No. Polisi</td>
            <td>: {{ $antrian->no_polisi }}</td>
        </tr>
        <tr>
            <td class="label">Tipe Motor</td>
            <td>: {{ $antrian->tipe_motor }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Layanan</td>
            <td>: {{ $antrian->jenis_servis }}</td>
        </tr>
        <tr>
            <td class="label">Estimasi Durasi</td>
            <td>: ± {{ $antrian->estimasi_waktu }} Menit</td>
        </tr>
        <tr>
            <td class="label">Tanggal Servis</td>
            <td>: {{ \Carbon\Carbon::parse($antrian->tanggal_servis)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Jam Kedatangan</td>
            <td>: {{ $antrian->waktu_datang }} WIB</td>
        </tr>
        <tr>
            <td class="label">Status Pesan</td>
            <td>: <span style="text-transform: uppercase; font-weight: bold; color: #0288d1;">{{ $antrian->status_pesan }}</span></td>
        </tr>
    </table>

    <div class="footer">
        <p>Harap tunjukkan bukti PDF ini kepada kasir/mekanik bengkel saat tiba di lokasi.<br>Simpan bukti ini di ponsel Anda.</p>
    </div>
</body>
</html>
