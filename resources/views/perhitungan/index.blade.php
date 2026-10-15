@extends('layouts.sbadmin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Hasil Perankingan Prioritas Antrian (WP)</h1>

    {{-- Tabel Utama Hasil Ranking --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-success d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-white">Daftar Prioritas Berdasarkan Metode Weighted Product</h6>
            <a href="{{ route('perhitungan.pdf') }}" class="btn btn-sm btn-light text-success font-weight-bold">
                <i class="fa fa-file-pdf mr-1"></i> Export PDF
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark text-center">
                        <tr>
                            <th width="50">Ranking</th>
                            <th>Kode Antrian</th>
                            <th>No Polisi</th>
                            <th>Layanan (C1)</th>
                            <th>Vektor S</th>
                            <th>Vektor V (Skor Akhir)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ranking as $key => $r)
                        <tr>
                            <td class="text-center font-weight-bold">
                                @if($key == 0)
                                    <span class="badge badge-warning" style="font-size: 1rem;">1</span>
                                @else
                                    {{ $key + 1 }}
                                @endif
                            </td>
                            <td class="text-center font-weight-bold text-success">
                                {{ $r['antrian']->kode_antrian ?? '-' }}
                            </td>
                            <td class="font-weight-bold text-uppercase">
                                {{ $r['antrian']->no_polisi ?? $r['antrian']->nopol ?? '-' }}
                            </td>
                            <td>{{ $r['antrian']->jenis_servis ?? '-' }}</td>
                            <td class="text-center">{{ number_format($r['s_value'], 4) }}</td>
                            <td class="text-center font-weight-bold text-primary">{{ number_format($r['v_value'], 4) }}</td>
                            <td class="text-center">
                                @if($key == 0)
                                    <span class="badge badge-danger px-3 py-2">Prioritas Utama</span>
                                @else
                                    <span class="badge badge-secondary px-3 py-2">Menunggu</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data untuk dihitung.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Detail Perhitungan (Langkah-langkah WP) --}}
    @if(count($ranking) > 0)
    <div class="accordion shadow mb-4" id="accordionPerhitungan">
        <div class="card">
            <div class="card-header bg-info" id="headingOne">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left text-white font-weight-bold d-flex align-items-center" type="button" data-toggle="collapse" data-target="#collapseOne">
                        <i class="fas fa-calculator mr-2"></i>
                        Lihat Langkah-Langkah Perhitungan Manual (Logika Sistem)
                        <i class="fas fa-chevron-down ml-auto"></i>
                    </button>
                </h2>
            </div>

            <div id="collapseOne" class="collapse" data-parent="#accordionPerhitungan">
                <div class="card-body bg-white">

                    {{-- Tahap 1 --}}
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-primary border-bottom pb-2">Tahap 1: Perbaikan Bobot (W)</h6>
                        <p class="small text-muted">Bobot diperbaiki dengan rumus $w_j = \frac{W_j}{\sum W}$. Untuk kriteria <strong>Cost</strong>, bobot dikalikan -1.</p>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered text-center small">
                                <thead class="bg-light font-weight-bold">
                                    <tr>
                                        @foreach($kriterias as $k)
                                            <th>{{ $k->nama_kriteria ?? $k->kriteria }} [{{ $k->kode }}] ({{ ucfirst($k->tipe) }})</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @foreach($kriterias as $k)
                                        @php
                                            $bobotTernormalisasi = $totalBobot > 0 ? ($k->bobot / $totalBobot) : 0;
                                            if (strtolower($k->tipe) == 'cost') {
                                                $bobotTernormalisasi *= -1;
                                            }
                                        @endphp
                                        <td>
                                            {{ $k->bobot }} / {{ $totalBobot }} <br>
                                            <strong class="text-primary">{{ number_format($bobotTernormalisasi, 4) }}</strong>
                                        </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tahap 2 --}}
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-primary border-bottom pb-2">Tahap 2: Menentukan Vektor S</h6>
                        <p class="small text-muted">Mencari nilai preferensi dengan rumus $S_i = \prod_{j=1}^n x_{ij}^{w_j}$ (Skor dipangkatkan bobot ternormalisasi).</p>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered text-center small">
                                <thead class="bg-light font-weight-bold">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Alternatif (No Pol)</th>
                                        @foreach($kriterias as $k)
                                            @php
                                                $wjHeader = $totalBobot > 0 ? ($k->bobot / $totalBobot) : 0;
                                                if (strtolower($k->tipe) == 'cost') $wjHeader *= -1;
                                            @endphp
                                            <th>{{ $k->kode }} (Skor<sup>{{ number_format($wjHeader, 2) }}</sup>)</th>
                                        @endforeach
                                        <th class="bg-warning text-dark">Vektor S</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ranking as $r)
                                    <tr>
                                        <td class="font-weight-bold text-success text-center">
                                            {{ $r['antrian']->kode_antrian ?? '-' }}
                                        </td>
                                        <td class="font-weight-bold text-left">
                                            {{ $r['antrian']->no_polisi ?? $r['antrian']->nopol ?? '-' }}
                                        </td>
                                        @foreach($kriterias as $k)
                                        <td>
                                            @php
                                                $skor = $r['detail'][$k->kode]['skor'] ?? 1;
                                                $wj = $r['detail'][$k->kode]['pangkat'] ?? 0;
                                                $hasilPangkat = $r['detail'][$k->kode]['hasil'] ?? pow($skor, $wj);
                                            @endphp
                                            {{ $skor }} <sup>{{ number_format($wj, 2) }}</sup><br>
                                            <span class="text-muted">={{ number_format($hasilPangkat, 3) }}</span>
                                        </td>
                                        @endforeach
                                        <td class="font-weight-bold bg-light text-primary">{{ number_format($r['s_value'], 4) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="bg-light font-weight-bold">
                                        <td colspan="{{ count($kriterias) + 2 }}" class="text-right">Total Vektor S (&sum;S):</td>
                                        <td class="text-primary">{{ number_format($totalS, 4) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tahap 3 --}}
                    <div>
                        <h6 class="font-weight-bold text-primary border-bottom pb-2">Tahap 3: Menentukan Vektor V (Normalisasi)</h6>
                        <p class="small text-muted">Normalisasi Vektor S untuk mendapatkan skor akhir (Ranking) dengan rumus $V_i = \frac{S_i}{\sum S_i}$.</p>
                        <div class="bg-light p-3 rounded">
                            <ul class="list-unstyled mb-0 small">
                                @foreach($ranking as $r)
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success mr-2"></i>
                                    V (<span class="text-success font-weight-bold">{{ $r['antrian']->kode_antrian ?? '-' }}</span> - {{ $r['antrian']->no_polisi ?? $r['antrian']->nopol ?? '-' }}) = {{ number_format($r['s_value'], 4) }} / {{ number_format($totalS, 4) }} =
                                    <strong class="text-primary font-weight-bold">{{ number_format($r['v_value'], 4) }}</strong>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
