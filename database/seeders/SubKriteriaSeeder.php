<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubKriteria;
use App\Models\BobotKriteria;

class SubKriteriaSeeder extends Seeder
{
    public function run()
    {
        // Ambil ID kriteria berdasarkan kode
        $c1 = BobotKriteria::where('kode', 'C1')->first()?->id;
        $c2 = BobotKriteria::where('kode', 'C2')->first()?->id;
        $c3 = BobotKriteria::where('kode', 'C3')->first()?->id;
        $c4 = BobotKriteria::where('kode', 'C4')->first()?->id;

        if (!$c1 || !$c2 || !$c3 || !$c4) {
            return;
        }

        // Hapus data lama agar tidak duplikat saat seeding ulang
        SubKriteria::query()->delete();

        $subKriterias = [
            // C1 - Jenis Layanan
            ['bobot_kriteria_id' => $c1, 'nama_sub_kriteria' => 'Ganti Ban Depan', 'deskripsi' => 'Estimasi 10 menit', 'skor' => 1],
            ['bobot_kriteria_id' => $c1, 'nama_sub_kriteria' => 'Ganti Knalpot', 'deskripsi' => 'Estimasi 10 menit', 'skor' => 1],
            ['bobot_kriteria_id' => $c1, 'nama_sub_kriteria' => 'Ganti Ban Belakang', 'deskripsi' => 'Estimasi 15 menit', 'skor' => 2],
            ['bobot_kriteria_id' => $c1, 'nama_sub_kriteria' => 'Ganti Gear Set', 'deskripsi' => 'Estimasi 20 menit', 'skor' => 2],
            ['bobot_kriteria_id' => $c1, 'nama_sub_kriteria' => 'Servis ringan karburator', 'deskripsi' => 'Estimasi 30 menit', 'skor' => 3],
            ['bobot_kriteria_id' => $c1, 'nama_sub_kriteria' => 'Servis ringan injeksi', 'deskripsi' => 'Estimasi 45 menit', 'skor' => 3],
            ['bobot_kriteria_id' => $c1, 'nama_sub_kriteria' => 'Servis karburator plus', 'deskripsi' => 'Estimasi 45 menit', 'skor' => 4],
            ['bobot_kriteria_id' => $c1, 'nama_sub_kriteria' => 'Servis injeksi plus', 'deskripsi' => 'Estimasi 60 menit', 'skor' => 4],
            ['bobot_kriteria_id' => $c1, 'nama_sub_kriteria' => 'Bongkar Mesin', 'deskripsi' => 'Estimasi 120 menit', 'skor' => 5],
            ['bobot_kriteria_id' => $c1, 'nama_sub_kriteria' => 'Bongkar Mesin full', 'deskripsi' => 'Estimasi 240 menit', 'skor' => 5],

            // C2 - Estimasi Waktu (Cost)
            ['bobot_kriteria_id' => $c2, 'nama_sub_kriteria' => '≤ 20 menit', 'deskripsi' => 'sangat cepat', 'skor' => 1],
            ['bobot_kriteria_id' => $c2, 'nama_sub_kriteria' => '21–40 menit', 'deskripsi' => 'cepat', 'skor' => 2],
            ['bobot_kriteria_id' => $c2, 'nama_sub_kriteria' => '41–60 menit', 'deskripsi' => 'sedang', 'skor' => 3],
            ['bobot_kriteria_id' => $c2, 'nama_sub_kriteria' => '61–120 menit', 'deskripsi' => 'lama', 'skor' => 4],
            ['bobot_kriteria_id' => $c2, 'nama_sub_kriteria' => '> 120 menit', 'deskripsi' => 'sangat lama', 'skor' => 5],

            // C3 - Waktu Kedatangan
            ['bobot_kriteria_id' => $c3, 'nama_sub_kriteria' => '08:00 - 09:00', 'deskripsi' => 'Sangat Awal', 'skor' => 1],
            ['bobot_kriteria_id' => $c3, 'nama_sub_kriteria' => '09:01 - 11:00', 'deskripsi' => 'Awal', 'skor' => 2],
            ['bobot_kriteria_id' => $c3, 'nama_sub_kriteria' => '11:01 - 13:00', 'deskripsi' => 'Siang', 'skor' => 3],
            ['bobot_kriteria_id' => $c3, 'nama_sub_kriteria' => '13:01 - 15:00', 'deskripsi' => 'Sore', 'skor' => 4],
            ['bobot_kriteria_id' => $c3, 'nama_sub_kriteria' => '> 15:00', 'deskripsi' => 'Mendekati Tutup', 'skor' => 5],

            // C4 - Status Pesan (Benefit)
            ['bobot_kriteria_id' => $c4, 'nama_sub_kriteria' => 'walk-in', 'deskripsi' => 'tanpa reservasi', 'skor' => 1],
            ['bobot_kriteria_id' => $c4, 'nama_sub_kriteria' => 'booking', 'deskripsi' => 'slot waktu terjadwal', 'skor' => 5],
        ];

        foreach ($subKriterias as $sub) {
            SubKriteria::create($sub);
        }
    }
}
