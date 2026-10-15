<?php

namespace App\Http\Controllers;

use App\Models\AntrianServis;
use App\Models\BobotKriteria;
use App\Models\SubKriteria;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan Dashboard utama monitoring antrian dan urutan prioritasnya.
     */
    public function index()
    {
        // 1. Kendaraan yang sedang dilayani / diproses
        $antrianAktif = AntrianServis::where('status', 'diproses')->first();

        // 2. Total kendaraan selesai hari ini
        $totalSelesai = AntrianServis::where('status', 'selesai')
            ->whereDate('updated_at', Carbon::today())
            ->count();

        // 3. Ambil daftar antrian yang sudah diurutkan berdasarkan prioritas WP
        $ranking = $this->calculateWpRanking();

        return view('dashboard', compact('antrianAktif', 'totalSelesai', 'ranking'));
    }

    /**
     * Menghitung urutan prioritas antrian 'menunggu' berbasis WP tanpa melempar nilai S/V ke view.
     */
    private function calculateWpRanking()
    {
        $kriterias = BobotKriteria::all();
        $antrians = AntrianServis::where('status', 'menunggu')->get();

        if ($kriterias->isEmpty() || $antrians->isEmpty()) {
            return [];
        }

        $totalBobot = $kriterias->sum('bobot');
        $hasilPerhitungan = [];

        foreach ($antrians as $antrian) {
            $vektorS = 1;

            foreach ($kriterias as $k) {
                $wj = $k->bobot / $totalBobot;
                if (strtolower($k->tipe) == 'cost') {
                    $wj *= -1;
                }

                $skor = $this->getSkorDatabase($antrian, $k);
                $vektorS *= pow($skor, $wj);
            }

            $hasilPerhitungan[] = [
                'antrian' => $antrian,
                's_value' => $vektorS,
            ];
        }

        $totalS = collect($hasilPerhitungan)->sum('s_value');

        // Hitung Nilai V untuk kebutuhan sorting internal
        $hasilSorted = collect($hasilPerhitungan)->map(function ($item) use ($totalS) {
            $item['v_value'] = $totalS > 0 ? $item['s_value'] / $totalS : 0;
            return $item;
        })->sort(function ($a, $b) {
            if (abs($a['v_value'] - $b['v_value']) < 0.00001) {
                $waktuA = $a['antrian']->awal_kedatangan ?? $a['antrian']->waktu_datang;
                $waktuB = $b['antrian']->awal_kedatangan ?? $b['antrian']->waktu_datang;
                return strtotime($waktuA) <=> strtotime($waktuB);
            }
            return $a['v_value'] < $b['v_value'] ? 1 : -1;
        })->values();

        // Kembalikan hanya Model AntrianServis-nya saja
        return $hasilSorted->pluck('antrian')->all();
    }

    /**
     * Helper privat pencocokan skor kriteria berbasis DB SubKriteria (C1-C5).
     */
    private function getSkorDatabase($antrian, $kriteria)
    {
        $subKriterias = SubKriteria::where('bobot_kriteria_id', $kriteria->id)->get();
        if ($subKriterias->isEmpty()) {
            return 1;
        }

        $kode = strtoupper($kriteria->kode);
        $fieldValue = null;

        if ($kode == 'C1') {
            $fieldValue = $antrian->jenis_servis ?? $antrian->c1_id ?? null;
        } elseif ($kode == 'C2') {
            $fieldValue = $antrian->estimasi_waktu ?? $antrian->c2_id ?? null;
        } elseif ($kode == 'C3') {
            $fieldValue = $antrian->awal_kedatangan ?? $antrian->waktu_datang ?? $antrian->c3_id ?? null;   
        } elseif ($kode == 'C5') {
            $fieldValue = $antrian->status_pesan ?? $antrian->c5_id ?? null;
        }

        if ($fieldValue === null || $fieldValue === '') {
            return 1;
        }

        if (is_numeric($fieldValue) && !in_array($kode, ['C2', 'C3'])) {
            $subDirect = $subKriterias->firstWhere('id', (int)$fieldValue);
            if ($subDirect) return $subDirect->skor;
        }

        $matched = $subKriterias->first(function ($sub) use ($fieldValue) {
            $nama = strtolower(trim($sub->sub_kriteria ?? $sub->nama_sub_kriteria));
            return $nama === strtolower(trim($fieldValue));
        });

        if ($matched) return $matched->skor;

        if ($kode == 'C2') {
            $menit = (int) filter_var($fieldValue, FILTER_SANITIZE_NUMBER_INT);

            foreach ($subKriterias as $sub) {
                $teks = str_replace(['–', '—'], '-', strtolower(trim($sub->sub_kriteria ?? $sub->nama_sub_kriteria)));

                if (str_contains($teks, '>')) {
                    $batas = (int) filter_var($teks, FILTER_SANITIZE_NUMBER_INT);
                    if ($menit > $batas) return $sub->skor;
                } elseif (str_contains($teks, '<=') || str_contains($teks, '≤')) {
                    $batas = (int) filter_var($teks, FILTER_SANITIZE_NUMBER_INT);
                    if ($menit <= $batas) return $sub->skor;
                } elseif (str_contains($teks, '-')) {
                    $parts = explode('-', $teks);
                    $min = (int) filter_var($parts[0], FILTER_SANITIZE_NUMBER_INT);
                    $max = (int) filter_var($parts[1], FILTER_SANITIZE_NUMBER_INT);
                    if ($menit >= $min && $menit <= $max) return $sub->skor;
                }
            }
        }

        if ($kode == 'C3') {
            $jamDatang = Carbon::parse($fieldValue)->format('H:i');

            foreach ($subKriterias as $sub) {
                $teks = str_replace(['–', '—'], '-', strtolower(trim($sub->sub_kriteria ?? $sub->nama_sub_kriteria)));

                preg_match_all('/\d{1,2}:\d{2}/', $teks, $matches);
                $times = $matches[0] ?? [];

                $times = array_map(function($t) {
                    return sprintf('%05s', $t);
                }, $times);

                if ((str_contains($teks, '<=') || str_contains($teks, '≤')) && isset($times[0])) {
                    if ($jamDatang <= $times[0]) return $sub->skor;
                } elseif (str_contains($teks, '>') && isset($times[0])) {
                    if ($jamDatang > $times[0]) return $sub->skor;
                } elseif (str_contains($teks, '-') && count($times) >= 2) {
                    if ($jamDatang >= $times[0] && $jamDatang <= $times[1]) {
                        return $sub->skor;
                    }
                }
            }
        }

        return 1;
    }
}
