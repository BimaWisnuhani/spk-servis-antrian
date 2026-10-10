<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\AntrianServis;
use App\Models\BobotKriteria;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AntrianServisController extends Controller
{
    /**
     * Menampilkan daftar antrian aktif, riwayat selesai, dan pending booking.
     */
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();

        // Recalculate SPK agar nilai_v ter-update sebelum diambil View
        $this->hitungSpk();

        // 1. Dapatkan antrian aktif
        $data = $this->getAntrianAktifQuery($request, $today)
            ->paginate($request->get('per_page', 10))
            ->appends($request->all());

        // 2. Query Terpisah untuk Riwayat Selesai Hari Ini
        $riwayatSelesaiHariIni = AntrianServis::where('status', 'selesai')
            ->whereDate('updated_at', $today)
            ->latest('updated_at')
            ->get();

        // 3. Query Terpisah untuk Booking Masuk (Pending Validasi)
        $pending = AntrianServis::where('status', 'pending')
            ->whereDate('tanggal_servis', '>=', $today)
            ->orderBy('tanggal_servis', 'asc')
            ->oldest('waktu_datang')
            ->get();

        // 4. Data untuk Form Edit & Option Checkbox Form
        $antrian = $request->filled('edit')
            ? AntrianServis::with('subKriterias')->find($request->edit)
            : null;

        $kriteriaC1 = BobotKriteria::where('kode', 'C1')->first();
        $listLayanan = $kriteriaC1
            ? SubKriteria::where('bobot_kriteria_id', $kriteriaC1->id)->get()
            : collect();

        return view('antrian.index', compact(
            'data',
            'riwayatSelesaiHariIni',
            'pending',
            'antrian',
            'listLayanan'
        ));
    }

    /**
     * Menyimpan data antrian baru (Walk-In / Booking dari Admin).
     */
    public function store(Request $request)
    {
        $validated = $this->validateAntrian($request);

        return DB::transaction(function () use ($validated, $request) {
            $kodeAntrian = $this->generateKodeAntrian(
                $validated['status_pesan'],
                $validated['tanggal_servis']
            );

            $namaLayanan = SubKriteria::whereIn('id', $validated['layanan_ids'])
                ->pluck('nama_sub_kriteria')
                ->toArray();

            $antrian = AntrianServis::create([
                'kode_antrian'   => $kodeAntrian,
                'nama_pelanggan' => $validated['nama_pelanggan'],
                'no_polisi'      => strtoupper(trim($validated['no_polisi'])),
                'tipe_motor'     => $validated['tipe_motor'],
                'no_hp'          => $validated['no_hp'],
                'jenis_servis'   => implode(', ', $namaLayanan),
                'estimasi_waktu' => $validated['estimasi_waktu'],
                'status_pesan'   => strtolower($validated['status_pesan']),
                'tanggal_servis' => $validated['tanggal_servis'],
                'waktu_datang'   => $validated['waktu_datang'],
                'status'         => 'menunggu',
            ]);

            $antrian->subKriterias()->attach($validated['layanan_ids']);

            $this->hitungSpk();

            return redirect()->route('antrian.index')
                ->with('success', "Antrian {$kodeAntrian} berhasil ditambahkan.");
        });
    }

    /**
     * Memperbarui data antrian.
     */
    public function update(Request $request, $id)
    {
        $antrian = AntrianServis::findOrFail($id);
        $validated = $this->validateAntrian($request);

        return DB::transaction(function () use ($antrian, $validated) {
            $namaLayanan = SubKriteria::whereIn('id', $validated['layanan_ids'])
                ->pluck('nama_sub_kriteria')
                ->toArray();

            $antrian->update([
                'nama_pelanggan' => $validated['nama_pelanggan'],
                'no_polisi'      => strtoupper(trim($validated['no_polisi'])),
                'tipe_motor'     => $validated['tipe_motor'],
                'no_hp'          => $validated['no_hp'],
                'jenis_servis'   => implode(', ', $namaLayanan),
                'estimasi_waktu' => $validated['estimasi_waktu'],
                'status_pesan'   => strtolower($validated['status_pesan']),
                'tanggal_servis' => $validated['tanggal_servis'],
                'waktu_datang'   => $validated['waktu_datang'],
            ]);

            $antrian->subKriterias()->sync($validated['layanan_ids']);

            $this->hitungSpk();

            return redirect()->route('antrian.index')
                ->with('success', "Data antrian {$antrian->no_polisi} berhasil diperbarui.");
        });
    }

    /**
     * Update status antrian.
     */
    public function updateStatus(Request $request, $id)
    {
        $antrian = AntrianServis::findOrFail($id);
        $antrian->update(['status' => strtolower($request->status)]);

        $this->hitungSpk();

        return redirect()->back()
            ->with('success', "Status kendaraan {$antrian->no_polisi} sekarang: " . ucfirst($antrian->status));
    }

    /**
     * Validasi/Konfirmasi Booking Online dari Pelanggan.
     */
    public function validasiBooking(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:menunggu,batal']);

        $antrian = AntrianServis::findOrFail($id);
        $antrian->update(['status' => $request->status]);

        $this->hitungSpk();

        $pesan = $request->status === 'menunggu'
            ? "Booking antrian {$antrian->no_polisi} berhasil disetujui."
            : "Booking antrian {$antrian->no_polisi} telah ditolak/dibatalkan.";

        return redirect()->back()->with('success', $pesan);
    }

    /**
     * Menghapus data antrian beserta relasinya.
     */
    public function destroy($id)
    {
        $antrian = AntrianServis::findOrFail($id);
        $noPolisi = $antrian->no_polisi;

        DB::transaction(function () use ($antrian) {
            $antrian->subKriterias()->detach();
            $antrian->delete();
            $this->hitungSpk();
        });

        return redirect()->route('antrian.index')
            ->with('success', "Antrian {$noPolisi} telah dihapus.");
    }

    // =========================================================================
    // PRIVATE HELPER METHODS
    // =========================================================================

    /**
     * Mengambil Query Builder untuk Antrian Aktif beserta pencarian & urutan SPK.
     */
    private function getAntrianAktifQuery(Request $request, string $today)
    {
        $query = AntrianServis::with('subKriterias')
            ->whereNotIn('status', ['selesai', 'batal', 'pending'])
            ->where(function ($q) use ($today) {
                $q->whereDate('tanggal_servis', $today)
                  ->orWhere('status', 'menginap');
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_polisi', 'like', "%{$search}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('kode_antrian', 'like', "%{$search}%")
                  ->orWhereHas('subKriterias', function ($subQuery) use ($search) {
                      $subQuery->where('nama_sub_kriteria', 'like', "%{$search}%");
                  });
            });
        }

        return $query->orderByRaw("FIELD(status, 'diproses', 'menunggu', 'menginap')")
            ->orderBy('nilai_v', 'desc')
            ->oldest('created_at');
    }

    /**
     * Validasi umum untuk Form Antrian.
     */
    private function validateAntrian(Request $request): array
    {
        return $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_polisi'      => 'required|string',
            'tipe_motor'     => 'required|string',
            'no_hp'          => 'required|string',
            'layanan_ids'    => 'required|array|min:1',
            'layanan_ids.*'  => 'exists:sub_kriteria,id',
            'estimasi_waktu' => 'required|integer',
            'status_pesan'   => 'required|string',
            'tanggal_servis' => 'required|date',
            'waktu_datang'   => 'required',
        ]);
    }

    /**
     * Generator Kode Antrian Unik (W001, B001, dst.).
     */
    private function generateKodeAntrian(string $statusPesan, string $targetDate): string
    {
        $prefix = (strtolower($statusPesan) === 'booking') ? 'B' : 'W';

        $existingCodes = AntrianServis::where(function ($q) use ($targetDate) {
                $q->whereDate('tanggal_servis', $targetDate)
                  ->orWhere('status', 'menginap');
            })
            ->where('kode_antrian', 'LIKE', $prefix . '%')
            ->pluck('kode_antrian');

        $maxNumber = 0;
        foreach ($existingCodes as $code) {
            $num = (int) substr($code, 1);
            if ($num > $maxNumber) {
                $maxNumber = $num;
            }
        }

        return $prefix . str_pad($maxNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Perhitungan SPK Weighted Product (WP) dan memperbarui nilai_v.
     */
    private function hitungSpk(): void
    {
        $today = Carbon::today()->toDateString();

        $antrianAktif = AntrianServis::with('subKriterias')
            ->whereNotIn('status', ['selesai', 'batal', 'pending'])
            ->where(function ($q) use ($today) {
                $q->whereDate('tanggal_servis', $today)
                  ->orWhere('status', 'menginap');
            })
            ->get();

        if ($antrianAktif->isEmpty()) {
            return;
        }

        $kriterias = BobotKriteria::all();
        $totalBobot = $kriterias->sum('bobot');

        if ($totalBobot == 0) {
            return;
        }

        // Normalisasi bobot W
        $normalizedW = [];
        foreach ($kriterias as $k) {
            $w = $k->bobot / $totalBobot;
            $normalizedW[$k->kode] = (strtolower($k->jenis) === 'cost') ? -$w : $w;
        }

        // Hitung Vector S
        $vectorS = [];
        $totalS = 0;

        foreach ($antrianAktif as $item) {
            $s = 1;
            foreach ($kriterias as $k) {
                $sub = $item->subKriterias->firstWhere('bobot_kriteria_id', $k->id);
                $nilai = $sub ? $sub->nilai : 1;
                $s *= pow($nilai, $normalizedW[$k->kode]);
            }
            $vectorS[$item->id] = $s;
            $totalS += $s;
        }

        // Hitung Vector V dan Update DB
        if ($totalS > 0) {
            foreach ($antrianAktif as $item) {
                $nilaiV = $vectorS[$item->id] / $totalS;
                $item->update(['nilai_v' => $nilaiV]);
            }
        }
    }
}
