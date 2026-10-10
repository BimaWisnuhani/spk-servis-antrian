<?php

namespace App\Http\Controllers;

use App\Models\AntrianServis;
use App\Models\BobotKriteria;
use App\Models\SubKriteria;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PerhitunganController extends Controller
{
    public function index()
    {
        $kriterias = BobotKriteria::all();
        if ($kriterias->isEmpty()) {
            return "Data Kriteria belum diisi. Silakan isi kriteria terlebih dahulu.";
        }

        $totalBobot = $kriterias->sum('bobot');
        $antrians = AntrianServis::whereIn('status', ['menunggu'])->get();

        if ($antrians->isEmpty()) {
            return view('perhitungan.index', [
                'ranking' => [],
                'totalS' => 0,
                'totalBobot' => $totalBobot,
                'kriterias' => $kriterias
            ]);
        }

        $hasilPerhitungan = [];

        foreach ($antrians as $antrian) {
            $vektorS = 1;
            $detailSkor = [];

            foreach ($kriterias as $k) {
                $wj = $k->bobot / $totalBobot;
                if (strtolower($k->tipe) == 'cost') {
                    $wj *= -1;
                }

                $skor = $this->getSkorDatabase($antrian, $k);
                $pangkat = pow($skor, $wj);
                $vektorS *= $pangkat;

                $detailSkor[$k->kode] = [
                    'skor' => $skor,
                    'pangkat' => $wj,
                    'hasil' => $pangkat
                ];
            }

            $hasilPerhitungan[] = [
                'antrian' => $antrian,
                's_value' => $vektorS,
                'detail' => $detailSkor
            ];
        }

        $totalS = collect($hasilPerhitungan)->sum('s_value');

        $hasilWithV = collect($hasilPerhitungan)->map(function ($item) use ($totalS) {
            $item['v_value'] = $totalS > 0 ? $item['s_value'] / $totalS : 0;
            return $item;
        });

        $ranking = $hasilWithV->sort(function ($a, $b) {
            if (abs($a['v_value'] - $b['v_value']) < 0.00001) {
                $waktuA = $a['antrian']->awal_kedatangan ?? $a['antrian']->waktu_datang;
                $waktuB = $b['antrian']->awal_kedatangan ?? $b['antrian']->waktu_datang;
                return strtotime($waktuA) <=> strtotime($waktuB);
            }
            return $a['v_value'] < $b['v_value'] ? 1 : -1;
        })->values()->all();

        return view('perhitungan.index', compact('ranking', 'totalS', 'totalBobot', 'kriterias'));
    }

    /**
     * Mengambil dan mencocokkan skor dari database SubKriteria secara dinamis
     */
    private function getSkorDatabase($antrian, $kriteria)
    {
        $subKriterias = SubKriteria::where('bobot_kriteria_id', $kriteria->id)->get();
        if ($subKriterias->isEmpty()) {
            return 1;
        }

        $kode = strtoupper($kriteria->kode);

        // 1. Tentukan sumber nilai dari model AntrianServis
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

        // 2. CEK METODE 1: Jika field menyimpan ID SubKriteria langsung (selain C2 dan C3)
        if (is_numeric($fieldValue) && !in_array($kode, ['C2', 'C3'])) {
            $subDirect = $subKriterias->firstWhere('id', (int)$fieldValue);
            if ($subDirect) {
                return $subDirect->skor;
            }
        }

        // 3. CEK METODE 2: Pencocokan Teks Kategori (C1 & C5)
        $matched = $subKriterias->first(function ($sub) use ($fieldValue) {
            $nama = strtolower(trim($sub->sub_kriteria ?? $sub->nama_sub_kriteria));
            return $nama === strtolower(trim($fieldValue));
        });

        if ($matched) {
            return $matched->skor;
        }

        // 4. CEK METODE 3: PARSING KRITERIA C2 (Estimasi Waktu - Menit)
        if ($kode == 'C2') {
            $menit = (int) filter_var($fieldValue, FILTER_SANITIZE_NUMBER_INT);

            foreach ($subKriterias as $sub) {
                // Normalisasi karakter dash (–, — ke -)
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

        // 5. CEK METODE 4: PARSING KRITERIA C3 (Awal Kedatangan - Jam:Menit)
        if ($kode == 'C3') {
            // Ambil format jam dan menit (HH:mm)
            $jamDatang = Carbon::parse($fieldValue)->format('H:i');

            foreach ($subKriterias as $sub) {
                // Normalisasi karakter dash
                $teks = str_replace(['–', '—'], '-', strtolower(trim($sub->sub_kriteria ?? $sub->nama_sub_kriteria)));

                // Tangkap pattern HH:mm
                preg_match_all('/\d{1,2}:\d{2}/', $teks, $matches);
                $times = $matches[0] ?? [];

                // Format string waktu agar konsisten 5 karakter (misal "8:30" -> "08:30")
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

    public function exportPdf()
    {
        $antrians = AntrianServis::whereIn('status', ['menunggu'])->get();
        $kriterias = BobotKriteria::all();
        $totalBobot = $kriterias->sum('bobot');

        $hasil = [];

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

            $hasil[] = [
                'antrian' => $antrian,
                'no_polisi' => $antrian->no_polisi ?? $antrian->nopol,
                'vektor_s' => $vektorS,
                'waktu_datang' => $antrian->awal_kedatangan ?? $antrian->waktu_datang
            ];
        }

        $totalS = collect($hasil)->sum('vektor_s');

        $hasilRanking = collect($hasil)->map(function ($h) use ($totalS) {
            $h['nilai_v'] = $totalS > 0 ? $h['vektor_s'] / $totalS : 0;
            return $h;
        })->sort(function ($a, $b) {
            if (abs($a['nilai_v'] - $b['nilai_v']) < 0.00001) {
                return strtotime($a['waktu_datang']) <=> strtotime($b['waktu_datang']);
            }
            return $a['nilai_v'] < $b['nilai_v'] ? 1 : -1;
        })->values()->all();

        $pdf = Pdf::loadView('perhitungan.pdf', compact('hasilRanking'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Laporan-Prioritas-Antrian-' . date('Y-m-d') . '.pdf');
    }
}
