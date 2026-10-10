@extends('layouts.sbadmin')

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-left-success" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- SECTION 1: KARTU RINGKASAN & KENDARAAN SEDANG DIPROSES -->
    <div class="row">
        <!-- Kartu Antrian Diproses (Sedang Servis) -->
        <div class="col-xl-6 col-md-12 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Sedang Dilayani / Diproses
                            </div>
                            @if(isset($antrianAktif) && $antrianAktif)
                                <div class="d-flex align-items-center mb-1">
                                    <span class="badge badge-dark font-weight-bold mr-2 px-2 py-1">
                                        {{ $antrianAktif->kode_antrian ?? '-' }}
                                    </span>
                                    <h4 class="h3 mb-0 font-weight-bold text-gray-800">
                                        {{ strtoupper($antrianAktif->no_polisi ?? $antrianAktif->nopol) }}
                                    </h4>
                                </div>

                                <div class="text-dark font-weight-bold small mb-2">
                                    <i class="fas fa-user text-gray-500 mr-1"></i> {{ $antrianAktif->nama_pelanggan ?? '-' }}
                                    <span class="text-muted">({{ $antrianAktif->tipe_motor ?? '-' }})</span>
                                </div>

                                <div class="mt-2 text-muted small">
                                    <span class="badge badge-info mr-1">{{ $antrianAktif->jenis_servis }}</span>
                                    <span class="badge badge-secondary mr-1">{{ $antrianAktif->estimasi_waktu }} Menit</span>
                                    @if($antrianAktif->no_hp)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $antrianAktif->no_hp) }}" target="_blank" class="badge badge-success">
                                            <i class="fab fa-whatsapp mr-1"></i>{{ $antrianAktif->no_hp }}
                                        </a>
                                    @endif
                                </div>
                            @else
                                <div class="h5 mb-0 font-weight-bold text-gray-500 py-2">
                                    Tidak ada kendaraan dilayani
                                </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-3x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Ringkasan Menunggu -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Menunggu Dalam Antrian
                            </div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">
                                {{ count($ranking ?? []) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Ringkasan Selesai -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Selesai Hari Ini
                            </div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">
                                {{ $totalSelesai ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: TABEL HASIL PERANKINGAN PRIORITAS -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-list-ol mr-2"></i>Daftar Urutan Prioritas Servis
            </h6>
            <span class="badge badge-light text-primary font-weight-bold">
                Total: {{ count($ranking ?? []) }} Kendaraan
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" width="100%">
                    <thead class="bg-light">
                        <tr class="small font-weight-bold text-dark text-uppercase">
                            <th class="text-center" width="8%">PRIORITAS</th>
                            <th width="10%">KODE</th>
                            <th width="22%">PELANGGAN & MOTOR</th>
                            <th width="20%">LAYANAN</th>
                            <th width="15%">KEDATANGAN</th>
                            <th class="text-center" width="13%">STATUS PESAN</th>
                            <th class="text-center" width="12%">KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ranking as $index => $antrian)
                        <tr>
                            <!-- Peringkat Prioritas -->
                            <td class="text-center align-middle font-weight-bold">
                                @if($index == 0)
                                    <span class="badge badge-warning p-2 text-dark font-weight-bold">
                                        <i class="fas fa-crown mr-1"></i>#1 Utama
                                    </span>
                                @else
                                    <span class="text-muted">#{{ $index + 1 }}</span>
                                @endif
                            </td>

                            <!-- Kode Antrian -->
                            <td class="align-middle">
                                <span class="badge badge-dark px-2 py-1 font-weight-bold">
                                    {{ $antrian->kode_antrian ?? '-' }}
                                </span>
                            </td>

                            <!-- Kendaraan & Pelanggan -->
                            <td class="align-middle">
                                <div class="font-weight-bold text-primary">
                                    {{ strtoupper($antrian->no_polisi ?? $antrian->nopol) }}
                                </div>
                                <div class="text-dark small font-weight-bold">
                                    {{ $antrian->nama_pelanggan ?? '-' }}
                                </div>
                                <div class="text-muted small">
                                    {{ $antrian->tipe_motor ?? '-' }}
                                    @if($antrian->no_hp)
                                        <span class="mx-1">•</span>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $antrian->no_hp) }}" target="_blank" class="text-success">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>

                            <!-- Layanan & Estimasi -->
                            <td class="align-middle">
                                <span class="badge badge-info mb-1">{{ $antrian->jenis_servis }}</span>
                                <div class="small text-muted">{{ $antrian->estimasi_waktu }} Menit</div>
                            </td>

                            <!-- Jam Kedatangan -->
                            <td class="align-middle">
                                <div class="small font-weight-bold">
                                    <i class="fas fa-clock mr-1 text-gray-400"></i>
                                    {{ \Carbon\Carbon::parse($antrian->awal_kedatangan ?? $antrian->waktu_datang)->format('H:i') }} WIB
                                </div>
                                @if(isset($antrian->tanggal_servis))
                                    <div class="small text-muted">
                                        {{ \Carbon\Carbon::parse($antrian->tanggal_servis)->format('d/m/Y') }}
                                    </div>
                                @endif
                            </td>

                            <!-- Status Pesan (Booking vs Walk-in) -->
                            <td class="text-center align-middle">
                                @if(strtolower($antrian->status_pesan ?? '') == 'booking')
                                    <span class="badge badge-success p-1">Booking</span>
                                @else
                                    <span class="badge badge-secondary p-1">Walk-in</span>
                                @endif
                            </td>

                            <!-- Status/Label Keterangan -->
                            <td class="text-center align-middle">
                                @if($index == 0)
                                    <span class="badge badge-danger p-1">Berikutnya</span>
                                @else
                                    <span class="badge badge-light border text-muted p-1">Menunggu</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted small">
                                <i class="fas fa-inbox fa-2x mb-2 text-gray-300 d-block"></i>
                                Tidak ada antrian yang sedang menunggu.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
