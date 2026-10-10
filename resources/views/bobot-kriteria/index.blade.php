@extends('layouts.sbadmin')

@section('title', 'Bobot Kriteria')

@section('content')
<div class="container-fluid">
    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-left-success shadow" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    {{-- Notifikasi Error --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-left-danger shadow" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card shadow mb-4 border-bottom-primary">
        <div class="card-header py-3 bg-primary d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <h6 class="m-0 font-weight-bold text-white mr-3">Daftar Kriteria & Bobot</h6>
                <span class="badge badge-light px-3 py-2 text-primary shadow-sm d-none d-lg-inline">
                    Total Bobot: <strong>{{ number_format($data->sum('bobot'), 2) }}</strong>
                </span>
            </div>

            <div class="d-flex align-items-center">
                {{-- Search Form --}}
                <form action="{{ route('bobot-kriteria.index') }}" method="GET" class="form-inline mr-2">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control border-0 shadow-sm"
                               placeholder="Cari kriteria..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-light text-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <button type="button" class="btn btn-light btn-sm font-weight-bold shadow-sm text-primary" id="btnTambah">
                    <i class="fas fa-plus fa-sm mr-1"></i> Tambah Kriteria
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%">
                    <thead class="bg-light text-uppercase small font-weight-bold">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th class="text-center">Kode</th>
                            <th>Nama Kriteria</th>
                            <th class="text-center">Tipe</th>
                            <th class="text-center">Bobot</th>
                            <th class="text-center" width="10%">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center"><span class="badge badge-info px-3">{{ $row->kode }}</span></td>
                            <td class="font-weight-bold text-gray-800">{{ $row->nama_kriteria }}</td>
                            <td class="text-center">
                                <span class="badge badge-{{ strtolower($row->tipe) == 'benefit' ? 'success' : 'danger' }}">
                                    {{ strtoupper($row->tipe) }}
                                </span>
                            </td>
                            <td class="text-center font-weight-bold text-primary">{{ number_format($row->bobot, 2) }}</td>
                            <td class="text-center">
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow border-0 animated--fade-in">
                                        <a class="dropdown-item btn-edit" href="javascript:void(0)"
                                           data-id="{{ $row->id }}"
                                           data-kode="{{ $row->kode }}"
                                           data-nama="{{ $row->nama_kriteria }}"
                                           data-tipe="{{ $row->tipe }}"
                                           data-bobot="{{ $row->bobot }}"
                                           data-url="{{ route('bobot-kriteria.update', $row->id) }}">
                                            <i class="fas fa-edit fa-sm fa-fw mr-2 text-warning"></i> Update
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <button type="button" class="dropdown-item text-danger font-weight-bold btn-delete"
                                                data-nama="{{ $row->nama_kriteria }}"
                                                data-url="{{ route('bobot-kriteria.destroy', $row->id) }}">
                                            <i class="fas fa-trash fa-sm fa-fw mr-2"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Data kriteria belum ada.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Form (Tambah & Edit) --}}
<div class="modal fade" id="modalBobot" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg shadow-lg" role="document">
        <div class="modal-content border-0">
            @include('bobot-kriteria.form')
        </div>
    </div>
</div>

{{-- Form Tersembunyi untuk Eksekusi Hapus --}}
<form id="formDelete" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
{{-- Load CDN SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    const modal = $('#modalBobot');
    const form = $('#formBobot');
    const formDelete = $('#formDelete');
    const storeUrl = "{{ route('bobot-kriteria.store') }}";

    let isEdit = false; // Penanda mode (Tambah / Edit)

    // 1. Trigger Buka Modal Tambah
    $('#btnTambah').on('click', function() {
        isEdit = false;
        form[0].reset();
        form.attr('action', storeUrl);
        form.find('input[name="_method"]').remove();

        $('#modalHeaderCustom').attr('class', 'modal-header bg-primary text-white');
        $('#modalTitle').html('<i class="fas fa-plus-circle mr-2"></i> Tambah Kriteria');
        $('#btnSubmit').attr('class', 'btn btn-primary px-4 shadow-sm').text('Simpan');

        modal.modal('show');
    });

    // 2. Trigger Buka Modal Edit
    $(document).on('click', '.btn-edit', function() {
        isEdit = true;
        const d = $(this).data();

        form.attr('action', d.url);
        if (form.find('input[name="_method"]').length === 0) {
            form.append('<input type="hidden" name="_method" value="PUT">');
        }

        form.find('input[name="kode"]').val(d.kode);
        form.find('input[name="nama_kriteria"]').val(d.nama);
        form.find('select[name="tipe"]').val(d.tipe);
        form.find('input[name="bobot"]').val(d.bobot);

        $('#modalHeaderCustom').attr('class', 'modal-header bg-warning text-white');
        $('#modalTitle').html('<i class="fas fa-edit mr-2"></i> Update Kriteria');
        $('#btnSubmit').attr('class', 'btn btn-warning px-4 shadow-sm text-white').text('Update');

        modal.modal('show');
    });

    // 3. Konfirmasi Alert untuk SIMPAN & UPDATE
    form.on('submit', function(e) {
        e.preventDefault(); // Tahan pengiriman form bawaan

        const pesanModal = isEdit
            ? 'Apakah Anda yakin ingin memperbarui data kriteria ini?'
            : 'Apakah Anda yakin ingin menambahkan data kriteria baru ini?';
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
                this.submit(); // Eksekusi submit form jika user pilih "Ya"
            }
        });
    });

    // 4. Konfirmasi Alert untuk HAPUS
    $(document).on('click', '.btn-delete', function() {
        const deleteUrl = $(this).data('url');
        const namaKriteria = $(this).data('nama');

        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Apakah Anda yakin ingin menghapus kriteria "${namaKriteria}"?`,
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
