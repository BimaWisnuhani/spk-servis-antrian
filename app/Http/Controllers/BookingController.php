<?php

namespace App\Http\Controllers;

use App\Models\AntrianServis;
use App\Models\BobotKriteria;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Tampilkan halaman form booking publik.
     */


public function create(Request $request)
{
    // Cari Kriteria C1 (Jenis Layanan)
    $kriteriaC1 = BobotKriteria::where('kode', 'C1')->first();

    // Ambil sub kriteria milik C1
    $listLayanan = SubKriteria::where('bobot_kriteria_id', $kriteriaC1?->id)->get();

    // Atau jika relasi langsung tanpa filter C1:
    // $listLayanan = SubKriteria::all();

    $tanggalPilihan = $request->get('filter_tanggal', date('Y-m-d', strtotime('+1 day')));

    // Ambil jadwal antrian
    $jadwalAntrian = AntrianServis::whereDate('tanggal_servis', $tanggalPilihan)->get();

    return view('booking', compact('listLayanan', 'jadwalAntrian', 'tanggalPilihan'));
}

    /**
     * Proses simpan booking dari pelanggan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_polisi'      => 'required|string|max:15',
            'tipe_motor'     => 'required|string|max:100',
            'no_hp'          => 'required|string|max:20',
            'layanan_ids'    => 'required|array|min:1',
            'layanan_ids.*'  => 'exists:sub_kriteria,id',
            'estimasi_waktu' => 'required|integer|min:1',
            'tanggal_servis' => 'required|date|after:today',
            'waktu_datang'   => [
                'required',
                // Rule Kustom: Cek bentrok rentang waktu servis
                function ($attribute, $value, $fail) use ($request) {
                    if (!$request->tanggal_servis || !$request->estimasi_waktu || !$value) {
                        return;
                    }

                    // 1. Waktu Servis Pengajuan Baru
                    $baruMulai = Carbon::parse($request->tanggal_servis . ' ' . $value);
                    $baruSelesai = (clone $baruMulai)->addMinutes((int) $request->estimasi_waktu);

                    // 2. Ambil semua antrian aktif pada tanggal tersebut
                    $antrianEksisting = AntrianServis::whereDate('tanggal_servis', $request->tanggal_servis)
                        ->where('status', '!=', 'dibatalkan')
                        ->get();

                    // 3. Cek overlap/bentrok dengan antrian eksisting
                    foreach ($antrianEksisting as $antrian) {
                        $tglStr = is_a($antrian->tanggal_servis, \Carbon\Carbon::class)
                            ? $antrian->tanggal_servis->format('Y-m-d')
                            : \Carbon\Carbon::parse($antrian->tanggal_servis)->format('Y-m-d');

                        $eksistingMulai = Carbon::parse($tglStr . ' ' . $antrian->waktu_datang);

                        // Gunakan estimasi_waktu eksisting (default 30 menit jika kosong)
                        $durasiEksisting = $antrian->estimasi_waktu > 0 ? $antrian->estimasi_waktu : 30;
                        $eksistingSelesai = (clone $eksistingMulai)->addMinutes($durasiEksisting);

                        // Rumus Overlap: (Mulai_A < Selesai_B) DAN (Selesai_A > Mulai_B)
                        if ($baruMulai < $eksistingSelesai && $baruSelesai > $eksistingMulai) {
                            $fail(
                                'Waktu kedatangan bentrok dengan antrian lain! ' .
                                'Jam ' . $eksistingMulai->format('H:i') . ' - ' . $eksistingSelesai->format('H:i') . ' WIB ' .
                                'masih dalam proses servis (' . $durasiEksisting . ' menit). Silakan pilih jam setelah pukul ' . $eksistingSelesai->format('H:i') . ' WIB.'
                            );
                            break;
                        }
                    }
                },
            ],
        ], [
            'nama_pelanggan.required' => 'Nama lengkap wajib diisi.',
            'no_polisi.required'      => 'Nomor polisi kendaraan wajib diisi.',
            'tipe_motor.required'     => 'Tipe motor wajib diisi.',
            'no_hp.required'          => 'Nomor HP / WhatsApp wajib diisi.',
            'layanan_ids.required'    => 'Pilih minimal satu jenis layanan servis.',
            'estimasi_waktu.min'      => 'Estimasi waktu servis minimal 1 menit.',
            'tanggal_servis.after'    => 'Booking untuk hari ini telah ditutup. Silakan pilih tanggal servis mulai besok (H+1).',
            'waktu_datang.required'   => 'Jam kedatangan wajib dipilih.',
        ]);

        // Generasi Kode Antrian Otomatis Booking (Contoh: B001, B002) untuk tanggal servis yang dipesan
        $countBookingHariIni = AntrianServis::whereDate('tanggal_servis', $request->tanggal_servis)
            ->where('status_pesan', 'booking')
            ->count() + 1;
        $kodeAntrian = 'B' . str_pad($countBookingHariIni, 3, '0', STR_PAD_LEFT);

        // Ambil nama-nama layanan dari ID yang dicentang
        $namaLayanan = SubKriteria::whereIn('id', $request->layanan_ids)->pluck('nama_sub_kriteria')->toArray();
        $teksLayanan = implode(', ', $namaLayanan);

        // Simpan data antrian dengan status 'pending'
        $antrian = AntrianServis::create([
            'kode_antrian'   => $kodeAntrian,
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_polisi'      => strtoupper(trim($request->no_polisi)),
            'tipe_motor'     => $request->tipe_motor,
            'no_hp'          => $request->no_hp,
            'jenis_servis'   => $teksLayanan,
            'estimasi_waktu' => $request->estimasi_waktu,
            'status_pesan'   => 'booking', // Otomatis Booking
            'tanggal_servis' => $request->tanggal_servis,
            'waktu_datang'   => $request->waktu_datang,
            'status'         => 'pending', // Status awal booking
        ]);

        // Simpan ke pivot jika relasi subKriterias digunakan
        if (method_exists($antrian, 'subKriterias')) {
            $antrian->subKriterias()->attach($request->layanan_ids);
        }

        return redirect()->route('booking.sukses', $antrian->id);
    }

    /**
     * Tampilan konfirmasi setelah booking berhasil.
     */
    public function sukses($id)
    {
        $antrian = AntrianServis::findOrFail($id);
        return view('booking_sukses', compact('antrian'));
    }
}
