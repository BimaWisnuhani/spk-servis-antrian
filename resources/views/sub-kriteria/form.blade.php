<form id="formSubKriteria" method="POST">
    @csrf
    {{-- Input method PUT akan ditambahkan otomatis via JS --}}

    <div class="modal-header text-white shadow-sm" id="modalHeaderSub">
        <h5 class="modal-title font-weight-bold" id="modalSubTitle"></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
    </div>

    <div class="modal-body p-4 text-dark">
        <div class="row">
            {{-- Pilih Kriteria --}}
            <div class="col-md-6 mb-3">
                <label class="font-weight-bold small text-uppercase">Parent Kriteria</label>
                <select name="bobot_kriteria_id" class="form-control shadow-sm" required>
                    <option value="">-- Pilih Kriteria --</option>
                    @foreach($kriterias as $k)
                        <option value="{{ $k->id }}">{{ $k->kode }} - {{ $k->nama_kriteria }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Skor --}}
            <div class="col-md-6 mb-3">
                <label class="font-weight-bold small text-uppercase">Skor / Nilai (1-5)</label>
                <input type="number" name="skor" class="form-control shadow-sm font-weight-bold text-primary" min="1" max="5" required>
            </div>
        </div>

        <div class="row">
            {{-- Nama Sub Kriteria --}}
            <div class="col-md-12 mb-3">
                <label class="font-weight-bold small text-uppercase">Nama Sub Kriteria</label>
                <input type="text" name="nama_sub_kriteria" class="form-control shadow-sm" placeholder="Contoh: Servis Ringan" required>
            </div>

            {{-- Deskripsi --}}
            <div class="col-md-12 mb-3">
                <label class="font-weight-bold small text-uppercase">Deskripsi Tambahan</label>
                <textarea name="deskripsi" class="form-control shadow-sm" rows="2" placeholder="Rincian sub kriteria..."></textarea>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light border-0">
        <button type="button" class="btn btn-secondary px-4 shadow-sm" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary px-4 shadow-sm" id="btnSubmitSub">Simpan Data</button>
    </div>
</form>
