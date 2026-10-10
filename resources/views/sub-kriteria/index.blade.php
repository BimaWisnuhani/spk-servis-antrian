@extends('layouts.sbadmin')

@section('title', 'Sub Kriteria')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-left-success shadow mb-4" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <!-- Card Top Header & Filter -->
    <div class="card shadow mb-4 border-bottom-primary">
        <div class="card-header py-3 bg-primary d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <h6 class="m-0 font-weight-bold text-white mb-2 mb-md-0">
                <i class="fas fa-list-alt mr-2"></i>Daftar Rincian Sub Kriteria
            </h6>

            <div class="d-flex flex-wrap align-items-center">
                {{-- Form Gabungan Search & Filter --}}
                <form action="{{ route('sub-kriteria.index') }}" method="GET" class="d-flex align-items-center mb-0">

                    {{-- 1. Input Search Group --}}
                    <div class="input-group input-group-sm shadow-sm mr-2" style="width: 200px;">
                        <input type="text" name="search" class="form-control border-0"
                               placeholder="Cari sub..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-light text-primary border-0" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    {{-- 2. Dropdown Filter --}}
                    <select name="kriteria_id" class="form-control form-control-sm border-0 shadow-sm px-3 mr-2"
                            onchange="this.form.submit()" style="min-width: 150px;">
                        <option value="">-- Semua Kriteria --</option>
                        @foreach($subKriterias as $item)
                            <p>Sub: {{ $item->nama_sub_kriteria }} | Kriteria: {{ $item->kriteria->nama_kriteria ?? 'Kriteria Tidak Ditemukan' }} | Jenis: {{ $item->kriteria->jenis ?? $item->kriteria->tipe ?? 'KOSONG' }}</p>
                        @endforeach
                    </select>
                </form>
                
                {{-- 3. Tombol Tambah --}}
                <button type="button" class="btn btn-light btn-sm font-weight-bold shadow-sm text-primary" id="btnTambahSub">
                    <i class="fas fa-plus fa-sm mr-1"></i> Tambah Sub
                </button>
            </div>
        </div>
    </div>

    <!-- TABEL 1: SUB KRITERIA BENEFIT -->
    <div class="card shadow mb-4 border-left-success">
        <div class="card-header py-3 bg-light d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-arrow-up mr-2"></i>Sub Kriteria - BENEFIT (Semakin Besar Semakin Baik)
            </h6>
            <span class="badge badge-success px-3 py-2">Benefit</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%">
                    <thead class="bg-light text-uppercase small font-weight-bold">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th width="20%">Kriteria</th>
                            <th>Sub Kriteria</th>
                            <th>Deskripsi</th>
                            <th class="text-center" width="10%">Skor</th>
                            <th class="text-center" width="50px">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $noBenefit = 1; @endphp
                        @forelse($subKriterias->filter(function($sub) {
                            $jenis = trim(strtolower($sub->kriteria->jenis ?? $sub->kriteria->tipe ?? $sub->kriteria->sifat ?? ''));
                            return $jenis === 'benefit';
                        }) as $sub)
                        <tr>
                            <td class="text-center">{{ $noBenefit++ }}</td>
                            <td>
                                <span class="badge badge-info">{{ $sub->kriteria->kode }}</span>
                                <span class="small font-weight-bold ml-1 text-dark">{{ $sub->kriteria->nama_kriteria }}</span>
                            </td>
                            <td class="font-weight-bold text-gray-800">{{ $sub->nama_sub_kriteria }}</td>
                            <td><small class="text-muted">{{ $sub->deskripsi ?: '-' }}</small></td>
                            <td class="text-center">
                                <span class="badge badge-success px-3 py-1">{{ $sub->skor }}</span>
                            </td>
                            <td class="text-center">
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow border-0 animated--fade-in">
                                        <a class="dropdown-item btn-edit-sub" href="javascript:void(0)"
                                           data-id="{{ $sub->id }}"
                                           data-parent="{{ $sub->bobot_kriteria_id }}"
                                           data-nama="{{ $sub->nama_sub_kriteria }}"
                                           data-skor="{{ $sub->skor }}"
                                           data-deskripsi="{{ $sub->deskripsi }}">
                                            <i class="fas fa-edit fa-sm fa-fw mr-2 text-warning"></i> Update
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <button type="button" class="dropdown-item text-danger font-weight-bold btn-delete-sub"
                                                data-nama="{{ $sub->nama_sub_kriteria }}"
                                                data-url="{{ route('sub-kriteria.destroy', $sub->id) }}">
                                            <i class="fas fa-trash fa-sm fa-fw mr-2"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Data sub kriteria Benefit belum tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TABEL 2: SUB KRITERIA COST -->
    <div class="card shadow mb-4 border-left-danger">
        <div class="card-header py-3 bg-light d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-danger">
                <i class="fas fa-arrow-down mr-2"></i>Sub Kriteria - COST (Semakin Kecil Semakin Baik)
            </h6>
            <span class="badge badge-danger px-3 py-2">Cost</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%">
                    <thead class="bg-light text-uppercase small font-weight-bold">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th width="20%">Kriteria</th>
                            <th>Sub Kriteria</th>
                            <th>Deskripsi</th>
                            <th class="text-center" width="10%">Skor</th>
                            <th class="text-center" width="50px">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $noCost = 1; @endphp
                        @forelse($subKriterias->filter(function($sub) {
                            $jenis = trim(strtolower($sub->kriteria->jenis ?? $sub->kriteria->tipe ?? $sub->kriteria->sifat ?? ''));
                            return $jenis === 'cost';
                        }) as $sub)
                        <tr>
                            <td class="text-center">{{ $noCost++ }}</td>
                            <td>
                                <span class="badge badge-info">{{ $sub->kriteria->kode }}</span>
                                <span class="small font-weight-bold ml-1 text-dark">{{ $sub->kriteria->nama_kriteria }}</span>
                            </td>
                            <td class="font-weight-bold text-gray-800">{{ $sub->nama_sub_kriteria }}</td>
                            <td><small class="text-muted">{{ $sub->deskripsi ?: '-' }}</small></td>
                            <td class="text-center">
                                <span class="badge badge-danger px-3 py-1">{{ $sub->skor }}</span>
                            </td>
                            <td class="text-center">
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow border-0 animated--fade-in">
                                        <a class="dropdown-item btn-edit-sub" href="javascript:void(0)"
                                           data-id="{{ $sub->id }}"
                                           data-parent="{{ $sub->bobot_kriteria_id }}"
                                           data-nama="{{ $sub->nama_sub_kriteria }}"
                                           data-skor="{{ $sub->skor }}"
                                           data-deskripsi="{{ $sub->deskripsi }}">
                                            <i class="fas fa-edit fa-sm fa-fw mr-2 text-warning"></i> Update
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <button type="button" class="dropdown-item text-danger font-weight-bold btn-delete-sub"
                                                data-nama="{{ $sub->nama_sub_kriteria }}"
                                                data-url="{{ route('sub-kriteria.destroy', $sub->id) }}">
                                            <i class="fas fa-trash fa-sm fa-fw mr-2"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Data sub kriteria Cost belum tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Modal Form --}}
<div class="modal fade" id="modalSubKriteria" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg shadow-lg" role="document">
        <div class="modal-content border-0">
            @include('sub-kriteria.form')
        </div>
    </div>
</div>

{{-- Form Tersembunyi Khusus Eksekusi Hapus --}}
<form id="formDeleteSub" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
{{-- Load CDN SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    const modal = $('#modalSubKriteria');
    const form = $('#formSubKriteria');
    const formDelete = $('#formDeleteSub');
    const baseUrl = "{{ route('sub-kriteria.index') }}";

    let isEdit = false;

    // 1. Tombol Tambah
    $('#btnTambahSub').on('click', function() {
        isEdit = false;
        form[0].reset();
        form.attr('action', baseUrl);
        form.find('input[name="_method"]').remove();

        $('#modalHeaderSub').attr('class', 'modal-header bg-primary text-white shadow-sm');
        $('#modalSubTitle').html('<i class="fas fa-plus-circle mr-2"></i> Tambah Sub Kriteria');
        $('#btnSubmitSub').attr('class', 'btn btn-primary px-4 shadow-sm').text('Simpan Data');

        modal.modal('show');
    });

    // 2. Tombol Edit
    $(document).on('click', '.btn-edit-sub', function() {
        isEdit = true;
        const d = $(this).data();

        form.attr('action', baseUrl + '/' + d.id);
        if (form.find('input[name="_method"]').length === 0) {
            form.append('<input type="hidden" name="_method" value="PUT">');
        }

        // Isi form
        form.find('select[name="bobot_kriteria_id"]').val(d.parent);
        form.find('input[name="nama_sub_kriteria"]').val(d.nama);
        form.find('input[name="skor"]').val(d.skor);
        form.find('textarea[name="deskripsi"]').val(d.deskripsi);

        $('#modalHeaderSub').attr('class', 'modal-header bg-warning text-white shadow-sm');
        $('#modalSubTitle').html('<i class="fas fa-edit mr-2"></i> Update Sub Kriteria');
        $('#btnSubmitSub').attr('class', 'btn btn-warning px-4 shadow-sm text-dark').text('Simpan Perubahan');

        modal.modal('show');
    });

    // 3. Alert Konfirmasi Tambah / Update
    form.on('submit', function(e) {
        e.preventDefault();

        const pesanModal = isEdit
            ? 'Apakah Anda yakin ingin menyimpan perubahan pada sub kriteria ini?'
            : 'Apakah Anda yakin ingin menambahkan sub kriteria baru ini?';
        const confirmBtnColor = isEdit ? '#f6c23e' : '#4e73df';

        Swal.fire({
            title: 'Konfirmasi Data',
            text: pesanModal,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: confirmBtnColor,
            cancelButtonColor: '#858796',
            confirmButtonText: 'Ya, Proses!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

    // 4. Alert Konfirmasi Hapus
    $(document).on('click', '.btn-delete-sub', function() {
        const deleteUrl = $(this).data('url');
        const namaSub = $(this).data('nama');

        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Apakah Anda yakin ingin menghapus sub kriteria "${namaSub}"?`,
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
