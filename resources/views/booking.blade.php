@extends('layouts.public')

@section('title', 'Booking Servis Online')

@section('content')
<div class="row justify-content-center py-4">

    <!-- Kolom Kiri: Form Booking -->
    <div class="col-xl-6 col-lg-6 col-md-12 mb-4">
        <div class="card o-hidden border-0 shadow-lg h-100">
            <div class="card-body p-0">
                <div class="p-4 p-md-5">
                    <div class="text-center mb-4">
                        <h1 class="h4 text-gray-900 font-weight-bold">
                            <i class="fas fa-calendar-alt text-primary mr-2"></i>Form Booking Antrian
                        </h1>
                        <p class="text-muted small">Silakan isi formulir di bawah untuk mendaftar antrian servis Anda.</p>
                    </div>

                    <form action="{{ route('booking.store') }}" method="POST">
                        @csrf

                        <!-- Status Pesan Otomatis Booking -->
                        <input type="hidden" name="status_pesan" value="booking">

                        <!-- Nama Pelanggan -->
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-dark">NAMA PELANGGAN</label>
                            <input type="text" name="nama_pelanggan" class="form-control @error('nama_pelanggan') is-invalid @enderror"
                                   value="{{ old('nama_pelanggan') }}" required placeholder="Contoh: Budi Santoso">
                            @error('nama_pelanggan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- No Polisi & Tipe Motor -->
                        <div class="form-row mb-3">
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">NOMOR POLISI</label>
                                <input type="text" name="no_polisi" class="form-control text-uppercase font-weight-bold @error('no_polisi') is-invalid @enderror"
                                       value="{{ old('no_polisi') }}" required placeholder="Contoh: AE 1234 AB">
                                @error('no_polisi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">TIPE MOTOR</label>
                                <input type="text" name="tipe_motor" class="form-control @error('tipe_motor') is-invalid @enderror"
                                       value="{{ old('tipe_motor') }}" required placeholder="Contoh: Vario 125">
                                @error('tipe_motor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- No HP / WhatsApp -->
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-dark">NO. HP / WHATSAPP</label>
                            <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                                   value="{{ old('no_hp') }}" required placeholder="Contoh: 081234567890">
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jenis Layanan (Buka Modal) -->
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-dark mb-2 d-block">PILIH JENIS LAYANAN</label>
                            <button type="button" class="btn btn-outline-primary btn-block text-left d-flex justify-content-between align-items-center" data-toggle="modal" data-target="#modalLayanan">
                                <span><i class="fas fa-tools mr-2"></i> Klik Untuk Pilih Layanan</span>
                                <span class="badge badge-primary font-weight-bold" id="badgeTotalTerpilih">0 Layanan Dipilih</span>
                            </button>

                            <!-- Ringkasan Layanan yang Dipilih -->
                            <div id="containerRingkasanLayanan" class="mt-2 d-flex flex-wrap" style="gap: 5px;"></div>

                            @error('layanan_ids')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Modal Pilih Layanan -->
                        <div class="modal fade" id="modalLayanan" tabindex="-1" role="dialog" aria-labelledby="modalLayananLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title font-weight-bold" id="modalLayananLabel">
                                            <i class="fas fa-list-ul mr-2"></i>Pilih Layanan Servis
                                        </h5>
                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            @forelse ($listLayanan as $sub)
                                                @php
                                                    $namaLayanan = $sub->nama_sub_kriteria ?? $sub->nama_layanan ?? 'Layanan';
                                                    $deskripsiText =$sub->deskripsi ?? '';
                                                    preg_match('/\d+/', $deskripsiText, $matches);$durasiMenit = !empty($matches[0]) ? (int)$matches[0] : 15;
                                                @endphp
                                                <div class="col-md-6 mb-2">
                                                    <div class="custom-control custom-checkbox border rounded p-2 small h-100 d-flex align-items-center">
                                                        <input type="checkbox"
                                                               name="layanan_ids[]"
                                                               value="{{ $sub->id }}"
                                                               data-nama="{{ $namaLayanan }}"
                                                               data-menit="{{ $durasiMenit }}"
                                                               class="custom-control-input chk-layanan"
                                                               id="check-{{ $sub->id }}"
                                                               {{ in_array($sub->id, old('layanan_ids', [])) ? 'checked' : '' }}>
                                                        <label class="custom-control-label font-weight-normal text-dark w-100 pl-2 cursor-pointer mb-0" for="check-{{ $sub->id }}">
                                                            <strong>{{ $namaLayanan }}</strong>
                                                            <div class="text-muted small">Estimasi: {{ $durasiMenit }} Menit</div>
                                                        </label>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-12 text-muted small text-center py-3">Layanan tidak tersedia.</div>
                                            @endforelse
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-primary font-weight-bold" data-dismiss="modal">
                                            <i class="fas fa-check mr-1"></i> Selesai Memilih
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estimasi Waktu Terhitung -->
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-dark">ESTIMASI DURASI SERVIS (MENIT)</label>
                            <input type="number" id="estimasi_waktu" name="estimasi_waktu" class="form-control bg-light"
                                   value="{{ old('estimasi_waktu') }}" placeholder="0 Menit" readonly required>
                        </div>

                        <!-- Tanggal & Jam Datang -->
                        <div class="form-row mb-4">
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">TANGGAL SERVIS</label>
                                <input type="date" name="tanggal_servis" id="input_tanggal_servis" class="form-control @error('tanggal_servis') is-invalid @enderror"
                                       value="{{ old('tanggal_servis', $tanggalPilihan) }}"
                                       min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" required>
                                @error('tanggal_servis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">JAM KEDATANGAN</label>
                                <input type="time" name="waktu_datang" class="form-control @error('waktu_datang') is-invalid @enderror"
                                       value="{{ old('waktu_datang', '08:00') }}" required>
                                <small class="form-text text-muted" style="font-size: 11px;">
                                    <i class="fas fa-exclamation-circle text-warning mr-1"></i>Pilih jam di luar jam sibuk pada tabel kanan.
                                </small>
                                @error('waktu_datang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2 shadow-sm">
                            <i class="fas fa-paper-plane mr-2"></i> KIRIM BOOKING
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Jadwal Antrian Terisi -->
    <div class="col-xl-6 col-lg-6 col-md-12 mb-4">
        <div class="card o-hidden border-0 shadow-lg h-100">
            <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-clock mr-2"></i>Jadwal Antrian Terisi
                </h6>
                <form action="{{ route('booking.create') }}" method="GET" id="formFilterTanggal" class="form-inline m-0">
                    <input type="date" name="filter_tanggal" id="filter_tanggal" class="form-control form-control-sm"
                           value="{{ $tanggalPilihan }}" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" onchange="this.form.submit()">
                </form>
            </div>
            <div class="card-body p-3">
                <div class="alert alert-info small mb-3">
                    <i class="fas fa-info-circle mr-1"></i> Menampilkan antrian tanggal
                    <strong>{{ \Carbon\Carbon::parse($tanggalPilihan)->translatedFormat('d F Y') }}</strong>. Rentang jam tertera sedang terisi.
                </div>

                <div class="alert alert-warning small mb-3">
                    <i class="fas fa-lightbulb mr-1"></i> <strong>Jam Pilihan Anda Bentrok?</strong><br>
                    Anda dapat memilih jam lain yang tidak berada dalam rentang waktu terisi, atau datang langsung ke bengkel (<strong>Walk-in</strong>).
                </div>

                <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
                    <table class="table table-bordered table-striped table-hover text-center small mb-0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th width="8%">No</th>
                                <th>Kode</th>
                                <th>Nomor Polisi</th>
                                <th>Jam Datang</th>
                                <th>Estimasi Selesai</th>
                                <th>Status Antrian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jadwalAntrian as $index => $item)
                                @php
                                    $tglStr = is_a($item->tanggal_servis, \Carbon\Carbon::class)
                                        ? $item->tanggal_servis->format('Y-m-d')                                         : \Carbon\Carbon::parse($item->tanggal_servis)->format('Y-m-d');

                                    $jamMulai = \Carbon\Carbon::parse($tglStr . ' ' .$item->waktu_datang);
                                    $durasi = (int)($item->estimasi_waktu > 0 ? $item->estimasi_waktu : 30);$jamSelesai = (clone $jamMulai)->addMinutes($durasi);
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge badge-dark font-weight-bold px-2 py-1">
                                            {{ $item->kode_antrian ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-dark font-weight-bold">
                                        {{ $item->no_polisi ?? '-' }}
                                    </td>
                                    <td class="text-danger font-weight-bold">
                                        {{ $jamMulai->format('H:i') }} WIB
                                    </td>
                                    <td class="text-muted font-weight-bold">
                                        {{ $jamSelesai->format('H:i') }} WIB <small>({{$durasi }}m)</small>
                                    </td>
                                    <td>
                                        @if(($item->status ?? '') == 'pending')
                                            <span class="badge badge-warning text-uppercase px-2 py-1">Booking (Pending)</span>
                                        @elseif(strtolower($item->status_pesan ?? '') == 'booking')
                                            <span class="badge badge-info text-uppercase px-2 py-1">Booking (Terverifikasi)</span>
                                        @else
                                            <span class="badge badge-secondary text-uppercase px-2 py-1">Walk-in</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-muted py-4">Belum ada antrian pada tanggal ini. Seluruh jam masih tersedia!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.chk-layanan');
    const inputEstimasi = document.getElementById('estimasi_waktu');
    const inputTanggalServis = document.getElementById('input_tanggal_servis');
    const formFilterTanggal = document.getElementById('formFilterTanggal');
    const filterTanggalInput = document.getElementById('filter_tanggal');
    const badgeTotalTerpilih = document.getElementById('badgeTotalTerpilih');
    const containerRingkasan = document.getElementById('containerRingkasanLayanan');

    function hitungTotalEstimasi() {
        let totalMenit = 0;
        let countTerpilih = 0;
        containerRingkasan.innerHTML = '';

        checkboxes.forEach(chk => {
            if (chk.checked) {
                countTerpilih++;
                const menit = parseInt(chk.getAttribute('data-menit')) || 0;
                const nama = chk.getAttribute('data-nama');

                totalMenit += menit;

                const badge = document.createElement('span');
                badge.className = 'badge badge-light border text-dark font-weight-normal px-2 py-1';
                badge.innerHTML = `<i class="fas fa-check text-success mr-1"></i>${nama} (${menit}m)`;
                containerRingkasan.appendChild(badge);
            }
        });

        badgeTotalTerpilih.textContent = `${countTerpilih} Layanan Dipilih`;
        inputEstimasi.value = totalMenit > 0 ? totalMenit : '';
    }

    checkboxes.forEach(chk => {
        chk.addEventListener('change', hitungTotalEstimasi);
    });

    if(inputTanggalServis) {
        inputTanggalServis.addEventListener('change', function() {
            filterTanggalInput.value = this.value;
            formFilterTanggal.submit();
        });
    }

    hitungTotalEstimasi();
});
</script>
@endpush
