<div id="modalHeaderCustom" class="modal-header bg-primary text-white">
    <h5 class="modal-title font-weight-bold" id="modalTitle">Form Kriteria</h5>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<form id="formBobot" action="" method="POST">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label class="font-weight-bold">Kode Kriteria</label>
            <input type="text" name="kode" class="form-control" placeholder="Contoh: C1" required>
        </div>

        <div class="form-group">
            <label class="font-weight-bold">Nama Kriteria</label>
            <input type="text" name="nama_kriteria" class="form-control" placeholder="Contoh: Jenis Servis" required>
        </div>

        <div class="form-group">
            <label class="font-weight-bold">Tipe Kriteria</label>
            <select name="tipe" class="form-control" required>
                <option value="benefit">Benefit</option>
                <option value="cost">Cost</option>
            </select>
        </div>

        <div class="form-group">
            <label class="font-weight-bold">Bobot (Antara 0.01 - 1.00)</label>
            <input type="number" step="0.01" min="0.01" max="1" name="bobot" class="form-control" placeholder="0.20" required>
        </div>
    </div>

    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" id="btnSubmit" class="btn btn-primary px-4 shadow-sm">Simpan</button>
    </div>
</form>
