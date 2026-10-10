<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BobotKriteria;

class BobotKriteriaSeeder extends Seeder
{
    public function run()
    {
        $kriterias = [
            [
                'kode'          => 'C1',
                'nama_kriteria' => 'Jenis Layanan',
                'tipe'          => 'benefit',
                'bobot'         => 0.35,
            ],
            [
                'kode'          => 'C2',
                'nama_kriteria' => 'Estimasi Waktu',
                'tipe'          => 'cost',
                'bobot'         => 0.25,
            ],
            [
                'kode'          => 'C3',
                'nama_kriteria' => 'Waktu Kedatangan',
                'tipe'          => 'cost', // Makin awal datang (skor kecil/pagi), makin diprioritaskan
                'bobot'         => 0.20,
            ],
            [
                'kode'          => 'C4',
                'nama_kriteria' => 'Status Pesan',
                'tipe'          => 'benefit',
                'bobot'         => 0.20,
            ],
        ];

        foreach ($kriterias as $k) {
            BobotKriteria::updateOrCreate(['kode' => $k['kode']], $k);
        }
    }
}
