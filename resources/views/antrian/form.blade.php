<div class="card shadow {{ isset($antrian) ? 'border-left-warning' : 'border-left-primary' }}">
    <div class="card-header py-3 {{ isset($antrian) ? 'bg-warning' : 'bg-primary' }} d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-white">
            <i class="fas fa-edit mr-1"></i> {{ isset($antrian) ? 'EDIT DATA ANTRIAN' : 'INPUT ANTRIAN BARU' }}
        </h6>

        <div>
            @if(isset($antrian))
                <a href="{{ route('antrian.index') }}" class="btn btn-light btn-sm shadow-sm text-primary font-weight-bold mr-1">
                    <i class="fas fa-plus-circle mr-1"></i> Buat Baru
                </a>
            @endif
            <!-- Tombol Tutup Form -->
            <button class="btn btn-sm btn-light text-dark font-weight-bold" type="button" data-toggle="collapse" data-target="#collapseFormAntrian">
                <i class="fas fa-times"></i> Tutup
            </button>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ isset($antrian) ? route('antrian.update', $antrian->id) : route('antrian.store') }}" method="POST">
            @csrf
            @if(isset($antrian)) @method('PUT') @endif

            <input type="hidden" name="status_pesan" value="walk-in">

            <div class="row">
    <!-- Nama Pelanggan -->
    <div class="col-md-3 mb-3">
        <label class="small font-weight-bold text-dark">NAMA PELANGGAN</label>
        <input type="text" name="nama_pelanggan" class="form-control"
               value="{{ old('nama_pelanggan', $antrian->nama_pelanggan ?? '') }}" required placeholder="Nama Lengkap">
    </div>

    <!-- No Polisi -->
    <div class="col-md-3 mb-3">
        <label class="small font-weight-bold text-dark">NO. POLISI</label>
        <input type="text" name="no_polisi" class="form-control text-uppercase font-weight-bold"
               value="{{ old('no_polisi', $antrian->no_polisi ?? '') }}" required placeholder="Contoh: AE 1234 AB">
    </div>

    <!-- Tipe Motor -->
    <div class="col-md-3 mb-3">
        <label class="small font-weight-bold text-dark">TIPE MOTOR</label>
        <input type="text" name="tipe_motor" class="form-control"
               value="{{ old('tipe_motor', $antrian->tipe_motor ?? '') }}" required placeholder="Contoh: Vario 125">
    </div>

    <!-- No. HP / WhatsApp -->
    <div class="col-md-3 mb-3">
        <label class="small font-weight-bold text-dark">NO. HP / WA</label>
        <input type="text" name="no_hp" class="form-control"
               value="{{ old('no_hp', $antrian->no_hp ?? '') }}" required placeholder="08123456789">
    </div>
</div>

<div class="row">
    <!-- Estimasi Waktu -->
    <div class="col-md-4 mb-3">
        <label class="small font-weight-bold text-dark">ESTIMASI WAKTU (MENIT)</label>
        <input type="number" id="estimasi_waktu" name="estimasi_waktu" class="form-control"
               value="{{ old('estimasi_waktu', $antrian->estimasi_waktu ?? '') }}"
               placeholder="0 Menit" readonly required>
    </div>

    <!-- Tanggal Servis -->
    <div class="col-md-4 mb-3">
        <label class="small font-weight-bold text-dark">TGL SERVIS</label>
        <input type="date" name="tanggal_servis" class="form-control"
               value="{{ old('tanggal_servis', $antrian->tanggal_servis ?? date('Y-m-d')) }}" required>
    </div>

    <!-- Waktu Datang -->
    <div class="col-md-4 mb-3">
        <label class="small font-weight-bold text-dark">AWAL KEDATANGAN</label>
        <input type="time" name="waktu_datang" class="form-control"
               value="{{ old('waktu_datang', $antrian->waktu_datang ?? date('H:i')) }}" required>
    </div>
</div>

            <!-- Jenis Layanan (Dinamis dari Sub Kriteria C1) -->
            <!-- JENIS LAYANAN (Trigger Modal) -->
<!-- JENIS LAYANAN -->
<div class="form-group mb-3">
    <label class="small font-weight-bold text-dark mb-1">JENIS LAYANAN</label>

    <button type="button" class="btn btn-outline-primary btn-block text-left d-flex justify-content-between align-items-center shadow-sm" data-toggle="modal" data-target="#modalPilihLayanan">
        <span><i class="fas fa-tools mr-2"></i> Pilih Jenis Layanan</span>
        <!-- ID 'badgeTotalTerpilih' Wajib Ada -->
        <span class="badge badge-primary font-weight-bold" id="badgeTotalTerpilih">0 Dipilih</span>
    </button>

    <!-- ID 'containerRingkasanLayanan' Wajib Ada -->
    <div id="containerRingkasanLayanan" class="mt-2 d-flex flex-wrap" style="gap: 5px;"></div>
</div>

<!-- MODAL PILIH LAYANAN -->
<div class="modal fade" id="modalPilihLayanan" tabindex="-1" role="dialog" aria-labelledby="modalPilihLayananLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="modalPilihLayananLabel">
                    <i class="fas fa-list-ul mr-2"></i> Pilih Jenis Layanan Servis
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @php
                        $selected_layanan = [];
                        if (isset($antrian) && method_exists($antrian, 'subKriterias')) {
                            $selected_layanan = $antrian->subKriterias->pluck('id')->toArray();
                        }
                    @endphp

                    @forelse ($listLayanan as $sub)
                        @php
                            $isChecked = in_array($sub->id, old('layanan_ids', $selected_layanan));
                        @endphp
                        <div class="col-md-6 mb-2">
                            <div class="custom-control custom-checkbox border rounded p-2 small h-100 d-flex align-items-center">
                                <input type="checkbox"
                                       name="layanan_ids[]"
                                       value="{{ $sub->id }}"
                                       data-nama="{{ $sub->nama_sub_kriteria }}"
                                       data-menit="{{ $sub->deskripsi }}"
                                       class="custom-control-input chk-layanan"
                                       id="check-{{ $sub->id }}"
                                       {{ $isChecked ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-normal text-dark w-100 pl-2 cursor-pointer mb-0" for="check-{{ $sub->id }}">
                                    <strong>{{ $sub->nama_sub_kriteria }}</strong>
                                    <div class="text-muted small">Estimasi: {{ $sub->deskripsi }} Menit</div>
                                </label>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-3">
                            <small class="text-danger">Belum ada data sub kriteria untuk jenis layanan (C1).</small>
                        </div>
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

            <!-- Action Button -->
            <div class="text-right border-top pt-3">
                @if(isset($antrian))
                    <a href="{{ route('antrian.index') }}" class="btn btn-secondary btn-sm mr-1 shadow-sm">Batal Edit</a>
                @endif
                <button type="submit" class="btn btn-{{ isset($antrian) ? 'warning' : 'primary' }} shadow font-weight-bold px-4">
                    <i class="fas fa-save mr-2"></i> {{ isset($antrian) ? 'PERBARUI' : 'SIMPAN ANTRIAN' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.chk-layanan');
    const inputEstimasi = document.getElementById('estimasi_waktu');
    const badgeTotalTerpilih = document.getElementById('badgeTotalTerpilih');
    const containerRingkasan = document.getElementById('containerRingkasanLayanan');

    function hitungLayanan() {
        let totalMenit = 0;
        let countTerpilih = 0;

        // Bersihkan ringkasan sebelum render ulang
        if (containerRingkasan) {
            containerRingkasan.innerHTML = '';
        }

        checkboxes.forEach(chk => {
            if (chk.checked) {
                countTerpilih++;

                // Mengambil nilai data-menit & data-nama
                const menit = parseInt(chk.getAttribute('data-menit')) || 0;
                const nama = chk.getAttribute('data-nama') || 'Layanan';

                totalMenit += menit;

                // Render badge ringkasan jika container-nya ada
                if (containerRingkasan) {
                    const badge = document.createElement('span');
                    badge.className = 'badge badge-light border text-dark font-weight-normal px-2 py-1';
                    badge.innerHTML = `<i class="fas fa-check text-success mr-1"></i>${nama} (${menit}m)`;
                    containerRingkasan.appendChild(badge);
                }
            }
        });

        // Update badge "X Dipilih"
        if (badgeTotalTerpilih) {
            badgeTotalTerpilih.textContent = `${countTerpilih} Dipilih`;
        }

        // Update input estimasi waktu
        if (inputEstimasi) {
            inputEstimasi.value = totalMenit > 0 ? totalMenit : '';
        }
    }

    // Pasang event listener pada setiap checkbox
    checkboxes.forEach(chk => {
        chk.addEventListener('change', hitungLayanan);
    });

    // Jalankan sekali di awal untuk membaca checkbox yang tercentang dari data awal/edit
    hitungLayanan();
});
</script>
