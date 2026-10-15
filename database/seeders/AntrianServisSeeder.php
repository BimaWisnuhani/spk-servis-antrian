<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AntrianServis;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AntrianServisSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key checks sementara untuk membersihkan data lama secara aman
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        if (class_exists('App\Models\AntrianServisSubKriteria')) {
            \App\Models\AntrianServisSubKriteria::truncate();
        } else {
            DB::table('antrian_servis_sub_kriteria')->delete();
        }
        AntrianServis::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data 12 antrian dengan skenario jam sibuk (Peak Hours) di Ponorogo
        $dataAntrians = [
            [
                'antrian' => [
                    'kode_antrian'   => 'B001',
                    'nama_pelanggan' => 'Eko Prasetyo',
                    'no_polisi'      => 'AE 3412 YX',
                    'tipe_motor'     => 'Honda Beat FI 2020',
                    'no_hp'          => '081234567891',
                    'jenis_servis'   => 'Ganti Oli, Ganti Ban Belakang',
                    'estimasi_waktu' => 20,
                    'status_pesan'   => 'booking',
                    'tanggal_servis' => Carbon::today()->toDateString(),
                    'waktu_datang'   => '08:05:00',
                    'status'         => 'menunggu',
                ],
                'sub_kriterias' => [42, 28, 33, 39] // C1, C2, C3, C4
            ],
            [
                'antrian' => [
                    'kode_antrian'   => 'B002',
                    'nama_pelanggan' => 'Siti Nurhaliza',
                    'no_polisi'      => 'AE 5678 SK',
                    'tipe_motor'     => 'Yamaha NMax 155',
                    'no_hp'          => '082345678902',
                    'jenis_servis'   => 'Servis Injeksi Plus, Ganti Gear Set',
                    'estimasi_waktu' => 80,
                    'status_pesan'   => 'booking',
                    'tanggal_servis' => Carbon::today()->toDateString(),
                    'waktu_datang'   => '08:15:00', // Jam sibuk pagi
                    'status'         => 'menunggu',
                ],
                'sub_kriterias' => [25, 31, 33, 39]
            ],
            [
                'antrian' => [
                    'kode_antrian'   => 'W001',
                    'nama_pelanggan' => 'Agus Santoso',
                    'no_polisi'      => 'AE 4123 B',
                    'tipe_motor'     => 'Honda Vario 150',
                    'no_hp'          => '083456789033',
                    'jenis_servis'   => 'Servis Ringan Injeksi',
                    'estimasi_waktu' => 45,
                    'status_pesan'   => 'walk-in',
                    'tanggal_servis' => Carbon::today()->toDateString(),
                    'waktu_datang'   => '08:30:00',
                    'status'         => 'menunggu',
                ],
                'sub_kriterias' => [23, 30, 33, 38]
            ],
            [
                'antrian' => [
                    'kode_antrian'   => 'W002',
                    'nama_pelanggan' => 'Budi Hartono',
                    'no_polisi'      => 'AE 9911 CC',
                    'tipe_motor'     => 'Honda Supra X 125',
                    'no_hp'          => '081987654321',
                    'jenis_servis'   => 'Ganti Oli Mesin',
                    'estimasi_waktu' => 15,
                    'status_pesan'   => 'walk-in',
                    'tanggal_servis' => Carbon::today()->toDateString(),
                    'waktu_datang'   => '08:45:00',
                    'status'         => 'menunggu',
                ],
                'sub_kriterias' => [42, 28, 33, 38]
            ],
            [
                'antrian' => [
                    'kode_antrian'   => 'B003',
                    'nama_pelanggan' => 'Dewi Lestari',
                    'no_polisi'      => 'AE 7890 E',
                    'tipe_motor'     => 'Honda Scoopy Fi',
                    'no_hp'          => '085678901244',
                    'jenis_servis'   => 'Servis Karburator Plus, Ganti Knalpot',
                    'estimasi_waktu' => 55,
                    'status_pesan'   => 'booking',
                    'tanggal_servis' => Carbon::today()->toDateString(),
                    'waktu_datang'   => '09:00:00',
                    'status'         => 'menunggu',
                ],
                'sub_kriterias' => [24, 30, 34, 39]
            ],
            [
                'antrian' => [
                    'kode_antrian'   => 'W003',
                    'nama_pelanggan' => 'Joko Purnomo',
                    'no_polisi'      => 'AE 2143 TM',
                    'tipe_motor'     => 'Suzuki Satria FU 150',
                    'no_hp'          => '087890123455',
                    'jenis_servis'   => 'Bongkar Mesin Full',
                    'estimasi_waktu' => 240,
                    'status_pesan'   => 'walk-in',
                    'tanggal_servis' => Carbon::today()->toDateString(),
                    'waktu_datang'   => '09:15:00',
                    'status'         => 'menunggu',
                ],
                'sub_kriterias' => [27, 32, 34, 38]
            ],
            [
                'antrian' => [
                    'kode_antrian'   => 'B004',
                    'nama_pelanggan' => 'Bambang Tri',
                    'no_polisi'      => 'AE 6543 NC',
                    'tipe_motor'     => 'Yamaha Aerox 155',
                    'no_hp'          => '081345678999',
                    'jenis_servis'   => 'Ganti Ban Depan, Ganti Oli',
                    'estimasi_waktu' => 25,
                    'status_pesan'   => 'booking',
                    'tanggal_servis' => Carbon::today()->toDateString(),
                    'waktu_datang'   => '09:30:00', // Kasus bentrok jam sibuk
                    'status'         => 'menunggu',
                ],
                'sub_kriterias' => [18, 29, 34, 39]
            ],
            [
                'antrian' => [
                    'kode_antrian'   => 'W004',
                    'nama_pelanggan' => 'Rian Hidayat',
                    'no_polisi'      => 'AE 1234 AA',
                    'tipe_motor'     => 'Honda Revo Fit',
                    'no_hp'          => '082456789111',
                    'jenis_servis'   => 'Setel Klep, Ganti Lampu Depan',
                    'estimasi_waktu' => 18,
                    'status_pesan'   => 'walk-in',
                    'tanggal_servis' => Carbon::today()->toDateString(),
                    'waktu_datang'   => '10:00:00',
                    'status'         => 'menunggu',
                ],
                'sub_kriterias' => [22, 28, 34, 38]
            ],
            [
                'antrian' => [
                    'kode_antrian'   => 'B005',
                    'nama_pelanggan' => 'Putri Wulandari',
                    'no_polisi'      => 'AE 9876 YZ',
                    'tipe_motor'     => 'Honda Genio',
                    'no_hp'          => '083567892222',
                    'jenis_servis'   => 'Servis Ringan Injeksi, Ganti Oli',
                    'estimasi_waktu' => 50,
                    'status_pesan'   => 'booking',
                    'tanggal_servis' => Carbon::today()->toDateString(),
                    'waktu_datang'   => '10:15:00',
                    'status'         => 'menunggu',
                ],
                'sub_kriterias' => [23, 30, 34, 39]
            ],
            [
                'antrian' => [
                    'kode_antrian'   => 'W005',
                    'nama_pelanggan' => 'Doni Prasetyo',
                    'no_polisi'      => 'AE 4321 KL',
                    'tipe_motor'     => 'Yamaha Jupiter Z',
                    'no_hp'          => '084678933333',
                    'jenis_servis'   => 'Setel Kamprat, Ganti Gear Set',
                    'estimasi_waktu' => 23,
                    'status_pesan'   => 'walk-in',
                    'tanggal_servis' => Carbon::today()->toDateString(),
                    'waktu_datang'   => '10:30:00',
                    'status'         => 'menunggu',
                ],
                'sub_kriterias' => [21, 29, 34, 38]
            ],
            [
                'antrian' => [
                    'kode_antrian'   => 'B006',
                    'nama_pelanggan' => 'Anisa Rahma',
                    'no_polisi'      => 'AE 8899 SW',
                    'tipe_motor'     => 'Honda PCX 160',
                    'no_hp'          => '085789444444',
                    'jenis_servis'   => 'Servis Injeksi Plus',
                    'estimasi_waktu' => 60,
                    'status_pesan'   => 'booking',
                    'tanggal_servis' => Carbon::today()->toDateString(),
                    'waktu_datang'   => '11:00:00',
                    'status'         => 'menunggu',
                ],
                'sub_kriterias' => [25, 30, 35, 39]
            ],
            [
                'antrian' => [
                    'kode_antrian'   => 'B007',
                    'nama_pelanggan' => 'Yusuf Maulana',
                    'no_polisi'      => 'AE 5544 TT',
                    'tipe_motor'     => 'Yamaha Mio M3',
                    'no_hp'          => '081122334455',
                    'jenis_servis'   => 'Ganti Kampas Rem Depan',
                    'estimasi_waktu' => 20,
                    'status_pesan'   => 'booking',
                    'tanggal_servis' => Carbon::today()->toDateString(),
                    'waktu_datang'   => '11:15:00',
                    'status'         => 'menunggu',
                ],
                'sub_kriterias' => [20, 28, 35, 39]
            ],
        ];

        foreach ($dataAntrians as $item) {
            // 1. Buat data antrian utamanya
            $antrian = AntrianServis::create($item['antrian']);

            // 2. Masukkan relasi sub-kriteria ke tabel pivot
            if (method_exists($antrian, 'subKriterias')) {
                $antrian->subKriterias()->attach($item['sub_kriterias']);
            } elseif (method_exists($antrian, 'sub_kriterias')) {
                $antrian->sub_kriterias()->attach($item['sub_kriterias']);
            }
        }
    }
}
