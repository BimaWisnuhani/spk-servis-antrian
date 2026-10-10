@extends('layouts.sbadmin')

@section('content')
<div class="container-fluid">

    <!-- Header Halaman & Tombol Toggle Form -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Manajemen Antrian Servis</h1>
            <p class="text-muted small mb-0">Kelola dan pantau antrian pengerjaan servis kendaraan secara real-time.</p>
        </div>

        <!-- Tombol Buka / Tutup Form -->
        <button class="btn btn-primary shadow-sm font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseFormAntrian" aria-expanded="{{ isset($antrian) ? 'true' : 'false' }}" aria-controls="collapseFormAntrian">
            <i class="fas fa-plus-circle fa-sm text-white-50 mr-1"></i> {{ isset($antrian) ? 'Tutup / Buka Form' : 'Tambah Antrian Baru' }}
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-left-success" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Panel Form Collapsible -->
    <div class="collapse {{ isset($antrian) || $errors->any() ? 'show' : '' }} mb-4" id="collapseFormAntrian">
        <div class="row">
            <div class="col-lg-12">
                @include('antrian.form')
            </div>
        </div>
    </div>

    @php
        // $pending = $data->where('status', 'pending');
        $totalMenunggu = $data->where('status', 'menunggu')->count();
        $totalDiproses = $data->where('status', 'diproses')->count();
        $totalMenginap = $data->where('status', 'menginap')->count();
        $riwayatList   = isset($riwayatSelesaiHariIni) ? $riwayatSelesaiHariIni : ($selesai ?? collect());
    @endphp

    <!-- CARDS RINGKASAN STATUS ANTRIAN (QUICK STATS) -->
    <div class="row mb-4">
        <!-- Card Menunggu -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menunggu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMenunggu }} Motor</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pause-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Diproses -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Sedang Diproses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDiproses }} Motor</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Menginap -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Menginap</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMenginap }} Motor</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bed fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Selesai Hari Ini -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Selesai Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $riwayatList->count() }} Motor</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL 0: VERIFIKASI BOOKING MASUK --}}
    @if($pending->count() > 0)
    <div class="card shadow mb-4 border-left-warning">
        <div class="card-header py-3 bg-warning text-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-bell mr-2"></i>Permintaan Booking Masuk ({{ $pending->count() }})
            </h6>
            <span class="badge badge-light text-dark font-weight-bold">Perlu Validasi</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" width="100%">
                    <thead class="bg-light">
                        <tr class="small font-weight-bold text-dark text-uppercase">
                            <th>KENDARAAN</th>
                            <th>LAYANAN</th>
                            <th>JADWAL</th>
                            <th class="text-center">AKSI VALIDASI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pending as $row)
                        <tr>
                            <td>
                                <div class="font-weight-bold text-primary">{{ strtoupper($row->no_polisi) }}</div>
                                <div class="small text-muted"><i class="fas fa-tag mr-1"></i>{{ ucfirst($row->status_pesan) }}</div>
                            </td>
                            <td>
                                <span class="badge badge-info mb-1">{{ $row->jenis_servis }}</span>
                                <div class="small text-muted">{{ $row->estimasi_waktu }} Menit</div>
                            </td>
                            <td>
                                <div class="small font-weight-bold"><i class="far fa-calendar-alt mr-1"></i>{{ \Carbon\Carbon::parse($row->tanggal_servis)->format('d/m/Y') }}</div>
                                <div class="small text-muted"><i class="fas fa-clock mr-1"></i>{{ $row->waktu_datang }} WIB</div>
                            </td>
                            <td class="text-center align-middle">
                                <div class="btn-group">
                                    <form action="{{ route('antrian.validasiBooking', $row->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="menunggu">
                                        <button type="submit" class="btn btn-success btn-sm mr-1 shadow-sm" title="Setujui" onclick="return confirm('Setujui booking ini?')">
                                            <i class="fas fa-check mr-1"></i> Setujui
                                        </button>
                                    </form>

                                    <form action="{{ route('antrian.validasiBooking', $row->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="batal">
                                        <button type="submit" class="btn btn-danger btn-sm shadow-sm" title="Tolak" onclick="return confirm('Tolak/Batalkan booking ini?')">
                                            <i class="fas fa-times mr-1"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- TABEL 1: DAFTAR ANTRIAN AKTIF --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <h6 class="m-0 font-weight-bold text-white mb-2 mb-md-0"><i class="fas fa-list-ol mr-2"></i>Daftar Antrian Aktif</h6>

            <!-- Form Filter Limit & Pencarian Gabungan -->
            <form action="{{ route('antrian.index') }}" method="GET" class="d-flex align-items-center">
                <!-- Select Per Page -->
                <select name="per_page" class="form-control form-control-sm border-0 shadow-sm mr-2" onchange="this.form.submit()">
                    <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5 Baris</option>
                    <option value="10" {{ request('per_page') == 10 || !request('per_page') ? 'selected' : '' }}>10 Baris</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris</option>
                </select>

                <!-- Input Pencarian -->
                <div class="input-group input-group-sm shadow-sm">
                    <input type="text" name="search" class="form-control border-0 small" placeholder="Cari no pol / nama..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-light btn-sm" type="submit">
                            <i class="fas fa-search fa-sm text-primary"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive" style="min-height: 200px;">
            <table class="table table-hover mb-0" width="100%">
                <thead class="bg-light">
                    <tr class="small font-weight-bold text-dark text-uppercase">
                        <th class="text-center" width="5%">NO</th>
                        <th width="12%">KODE</th>
                        <th width="28%">PELANGGAN & KENDARAAN</th>
                        <th width="25%">LAYANAN</th>
                        <th class="text-center" width="12%">STATUS</th>
                        <th class="text-center" width="18%">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $row)
                    <tr>
                        <!-- Nomor Urut -->
                        <td class="text-center small align-middle font-weight-bold">
                            {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                        </td>

                        <!-- Kode Antrian -->
                        <td class="align-middle">
                            <span class="badge badge-dark font-weight-bold px-2 py-1" style="font-size: 0.82rem;">
                                {{ $row->kode_antrian ?? '-' }}
                            </span>
                        </td>

                        <!-- Pelanggan, Kendaraan, & Tanggal -->
                        <td class="align-middle">
                            <div class="font-weight-bold text-dark">{{ $row->nama_pelanggan ?? 'Tanpa Nama' }}</div>
                            <div class="small font-weight-bold text-primary">
                                {{ strtoupper($row->no_polisi) }} <span class="text-muted font-weight-normal">({{ $row->tipe_motor ?? '-' }})</span>
                            </div>
                            <div class="small text-muted mt-1">
                                <i class="far fa-calendar-alt mr-1"></i>{{ \Carbon\Carbon::parse($row->tanggal_servis)->format('d/m/Y') }}
                                <span class="mx-1">•</span>
                                <i class="fas fa-clock mr-1"></i>{{ $row->waktu_datang }}
                                @if($row->no_hp)
                                    <span class="mx-1">•</span> <i class="fab fa-whatsapp text-success"></i> {{ $row->no_hp }}
                                @endif
                            </div>
                        </td>

                        <!-- Jenis Layanan -->
                        <td class="align-middle">
                            <span class="badge badge-info mb-1" style="white-space: normal; text-align: left; line-height: 1.3;">
                                {{ $row->jenis_servis }}
                            </span>
                            <div class="small text-capitalize text-muted">
                                <i class="fas fa-tag mr-1"></i>{{ $row->status_pesan }}
                            </div>
                        </td>

                        <!-- Status Antrian Badge Visual -->
                        <td class="text-center align-middle">
                            @if($row->status == 'menunggu')
                                <span class="badge badge-warning p-2 shadow-sm text-dark"><i class="fas fa-pause-circle mr-1"></i>Menunggu</span>
                            @elseif($row->status == 'diproses' || $row->status == 'proses')
                                <span class="badge badge-primary p-2 shadow-sm"><i class="fas fa-tools mr-1"></i>Diproses</span>
                            @elseif($row->status == 'menginap')
                                <span class="badge badge-secondary p-2 shadow-sm" style="background-color: #6f42c1; color: white;"><i class="fas fa-bed mr-1"></i>Menginap</span>
                            @else
                                <span class="badge badge-light p-2 shadow-sm">{{ ucfirst($row->status) }}</span>
                            @endif
                        </td>

                        <!-- Action Button dengan Dropdown Status Cepat -->
                        <td class="text-center align-middle">
                            <div class="btn-group">
                                <!-- Dropdown Ubah Status Cepat -->
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle shadow-sm mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-sync-alt mr-1"></i> Status
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                                        <h6 class="dropdown-header">Ubah Status Ke:</h6>

                                        <form action="{{ route('antrian.updateStatus', $row->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="menunggu">
                                            <button type="submit" class="dropdown-item text-warning {{ $row->status == 'menunggu' ? 'active text-white' : '' }}">
                                                <i class="fas fa-pause-circle mr-2"></i>Menunggu
                                            </button>
                                        </form>

                                        <form action="{{ route('antrian.updateStatus', $row->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="diproses">
                                            <button type="submit" class="dropdown-item text-primary {{ $row->status == 'diproses' ? 'active text-white' : '' }}">
                                                <i class="fas fa-tools mr-2"></i>Diproses
                                            </button>
                                        </form>

                                        <form action="{{ route('antrian.updateStatus', $row->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="menginap">
                                            <button type="submit" class="dropdown-item style-purple {{ $row->status == 'menginap' ? 'active text-white' : '' }}" style="color: #6f42c1;">
                                                <i class="fas fa-bed mr-2"></i>Menginap
                                            </button>
                                        </form>

                                        <div class="dropdown-divider"></div>

                                        <form action="{{ route('antrian.updateStatus', $row->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="selesai">
                                            <button type="submit" class="dropdown-item text-success">
                                                <i class="fas fa-check-circle mr-2"></i>Selesai Servis
                                            </button>
                                        </form>

                                        <form action="{{ route('antrian.updateStatus', $row->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="batal">
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Batalkan antrian ini?')">
                                                <i class="fas fa-times-circle mr-2"></i>Batalkan
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Tombol Edit -->
                                <a href="{{ route('antrian.index', ['edit' => $row->id]) }}" class="btn btn-warning btn-sm mr-1 shadow-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('antrian.destroy', $row->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm shadow-sm" onclick="return confirm('Hapus antrian ini secara permanen?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted small">
                            <i class="fas fa-inbox fa-2x mb-2 text-gray-300 d-block"></i>
                            Data antrian aktif tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($data->hasPages())
        <div class="card-footer py-3 d-flex flex-column flex-md-row justify-content-between align-items-center bg-white border-top">
            <div class="small text-muted mb-2 mb-md-0">
                Menampilkan {{ $data->firstItem() }} sampai {{ $data->lastItem() }} dari {{ $data->total() }} data
            </div>
            <div class="pagination-sm">
                {{ $data->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>

    {{-- TABEL 2: RIWAYAT SELESAI HARI INI --}}
    <div class="card shadow mb-4 border-left-success">
        <a href="#collapseSelesai" class="d-block card-header py-3 bg-light" data-toggle="collapse" role="button">
            <h6 class="m-0 font-weight-bold text-success text-center">
                <i class="fas fa-history mr-2"></i>Riwayat Selesai Hari Ini ({{ $riwayatList->count() }})
            </h6>
        </a>
        <div class="collapse" id="collapseSelesai">
            <div class="card-body p-0 small">
                <table class="table table-sm mb-0 table-striped">
                    <tbody>
                        @forelse ($riwayatList as $row)
                        <tr>
                            <td class="pl-4 py-2 font-weight-bold text-primary">{{ strtoupper($row->no_polisi) }}</td>
                            <td class="py-2 text-muted">{{ $row->jenis_servis }}</td>
                            <td class="text-center py-2"><span class="badge badge-success"><i class="fas fa-check mr-1"></i>Selesai</span></td>
                            <td class="text-right pr-4 py-2">
                                <a href="{{ route('antrian.index', ['edit' => $row->id]) }}" class="btn btn-link btn-sm text-warning p-0">
                                    <i class="fas fa-undo"></i> Koreksi
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-3 text-muted">Belum ada antrian yang diselesaikan hari ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
