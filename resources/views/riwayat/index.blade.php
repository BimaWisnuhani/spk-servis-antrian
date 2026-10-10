@extends('layouts.sbadmin')

@section('title', 'Riwayat Servis')

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-left-success shadow mb-4" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <!-- Card Utama Selaras SB Admin Header -->
    <div class="card shadow mb-4 border-bottom-primary">
        <div class="card-header py-3 bg-primary d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <h6 class="m-0 font-weight-bold text-white mb-2 mb-md-0">
                <i class="fas fa-history mr-2"></i>Riwayat Transaksi & Servis Kendaraan
            </h6>

            <!-- Form Filter Tanggal & Search Group Selaras -->
            <form action="{{ route('riwayat.index') }}" method="GET" class="d-flex flex-wrap align-items-center mb-0">

                {{-- Input Tanggal --}}
                <input type="date" name="tanggal" class="form-control form-control-sm border-0 shadow-sm px-3 mr-2 mb-2 mb-md-0"
                       value="{{ request('tanggal') }}" onchange="this.form.submit()" style="min-width: 140px;">

                {{-- Input Search Group --}}
                <div class="input-group input-group-sm shadow-sm" style="width: 220px;">
                    <input type="text" name="search" class="form-control border-0"
                           placeholder="Cari Plat / Nama..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-light text-primary border-0" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                {{-- Tombol Reset Filter jika ada pencarian --}}
                @if(request('tanggal') || request('search'))
                    <a href="{{ route('riwayat.index') }}" class="btn btn-light btn-sm text-danger ml-2 font-weight-bold shadow-sm" title="Reset Filter">
                        <i class="fas fa-undo"></i>
                    </a>
                @endif
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%">
                    <thead class="bg-light text-uppercase small font-weight-bold">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="10%">Kode</th>
                            <th width="15%">Tanggal Servis</th>
                            <th width="12%">No. Polisi</th>
                            <th>Nama Pelanggan</th>
                            <th>Jenis Layanan</th>
                            <th class="text-center" width="12%">Status Pesan</th>
                            <th class="text-center" width="12%">Status Servis</th>
                            <th class="text-center" width="8%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayats as $index => $item)
                        <tr>
                            <td class="text-center">{{ $riwayats->firstItem() + $index }}</td>
                            <td>
                                <span class="badge badge-dark px-2 py-1 font-weight-bold">
                                    {{ $item->kode_antrian }}
                                </span>
                            </td>
                            <td>
                                <span class="text-dark">{{ \Carbon\Carbon::parse($item->tanggal_servis)->format('d/m/Y') }}</span>
                                <br><small class="text-muted"><i class="far fa-clock mr-1"></i>{{ $item->waktu_datang }} WIB</small>
                            </td>
                            <td class="font-weight-bold text-uppercase text-dark">
                                {{ $item->no_polisi }}
                            </td>
                            <td class="font-weight-bold text-gray-800">
                                {{ $item->nama_pelanggan }}
                            </td>
                            <td>
                                <span class="small font-weight-bold">{{ $item->jenis_servis }}</span>
                            </td>
                            <td class="text-center">
                                @if(strtolower($item->status_pesan) == 'booking')
                                    <span class="badge badge-info px-2 py-1">Booking</span>
                                @else
                                    <span class="badge badge-secondary px-2 py-1">Walk-in</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->status == 'selesai')
                                    <span class="badge badge-success px-3 py-1"><i class="fas fa-check-circle mr-1"></i>Selesai</span>
                                @else
                                    <span class="badge badge-danger px-3 py-1"><i class="fas fa-times-circle mr-1"></i>Batal</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow border-0 animated--fade-in">
                                        <a class="dropdown-item" href="{{ route('riwayat.show', $item->id) }}">
                                            <i class="fas fa-eye fa-sm fa-fw mr-2 text-info"></i> Detail
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <button type="button" class="dropdown-item text-danger font-weight-bold btn-delete-riwayat"
                                                data-nama="{{ $item->nama_pelanggan }} ({{ $item->no_polisi }})"
                                                data-url="{{ route('riwayat.destroy', $item->id) }}">
                                            <i class="fas fa-trash fa-sm fa-fw mr-2"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-2x mb-2 d-block text-gray-300"></i>
                                Belum ada data riwayat servis yang tercatat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bootstrap 4 SB Admin Style -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="small text-muted">
                    Menampilkan {{ $riwayats->firstItem() ?? 0 }} sampai {{ $riwayats->lastItem() ?? 0 }} dari {{$riwayats->total() }} data
                </div>
                <div>
                    {{ $riwayats->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Form Tersembunyi Hapus --}}
<form id="formDeleteRiwayat" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    const formDelete = $('#formDeleteRiwayat');

    // SweetAlert Hapus Riwayat
    $(document).on('click', '.btn-delete-riwayat', function() {
        const deleteUrl = $(this).data('url');
        const infoSub = $(this).data('nama');

        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Apakah Anda yakin ingin menghapus data riwayat "${infoSub}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74a3b',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                formDelete.attr('action', deleteUrl);
                formDelete.submit();
            }
        });
    });
});
</script>
@endpush
