<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AntrianServis;

class AntrianServisSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus 'antrian_pelanggan' dan 'ketersediaan_mekanik' dari array seeder
        $data = [
            [
                'kode_antrian'   => 'W001',
                'nama_pelanggan' => 'Budi Santoso',
                'no_polisi'      => 'AE 2415 SK',
                'tipe_motor'     => 'Vario 125',
                'no_hp'          => '081234567890',
                'jenis_servis'   => 'Servis Sedang',
                'estimasi_waktu' => 60,
                'status_pesan'   => 'walk-in',
                'tanggal_servis' => '2026-01-08',
                'waktu_datang'   => '08:00:00',
                'status'         => 'menunggu',
            ],
            // Tambahkan data dummy lainnya jika ada...
        ];

        foreach ($data as $item) {
            AntrianServis::create($item);
        }
    }
}
