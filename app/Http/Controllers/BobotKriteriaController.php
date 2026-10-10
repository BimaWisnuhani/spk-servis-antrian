<?php

namespace App\Http\Controllers;

use App\Models\BobotKriteria;
use Illuminate\Http\Request;

class BobotKriteriaController extends Controller
{
    public function index()
    {
        // Ambil data urut berdasarkan kode kriteria
        $data = BobotKriteria::orderBy('kode', 'asc')->get();
        return view('bobot-kriteria.index', compact('data'));

        $query = BobotKriteria::query();
if ($request->filled('search')) {
    $query->where('nama_kriteria', 'like', '%' . $request->search . '%')
          ->orWhere('kode', 'like', '%' . $request->search . '%');
}
$data = $query->get(); // atau ->paginate()
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'kode' => 'required|unique:bobot_kriteria,kode',
            'nama_kriteria' => 'required',
            'tipe' => 'required|in:benefit,cost',
            'bobot' => 'required|numeric|between:0.01,1',
        ], [
            'kode.unique' => 'Kode kriteria sudah digunakan!',
            'bobot.between' => 'Bobot harus di antara 0.01 sampai 1.00',
        ]);

        // 2. Validasi Total Bobot (Maksimal 1.0)
        $totalSekarang = \App\Models\BobotKriteria::sum('bobot');
        if (($totalSekarang + $request->bobot) > 1.0001) {
            return redirect()->back()->withInput()->with('error', "Gagal! Total bobot melebihi 1.00. Sisa kuota bobot: " . (1 - $totalSekarang));
        }

        \App\Models\BobotKriteria::create($request->all());
        return redirect()->route('bobot-kriteria.index')->with('success', 'Kriteria berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|unique:bobot_kriteria,kode,' . $id,
            'nama_kriteria' => 'required',
            'tipe' => 'required|in:benefit,cost',
            'bobot' => 'required|numeric|between:0.01,1',
        ]);

        $totalLain = \App\Models\BobotKriteria::where('id', '!=', $id)->sum('bobot');
        if (($totalLain + $request->bobot) > 1.0001) {
            return redirect()->back()->withInput()->with('error', "Gagal! Total bobot melebihi 1.00.");
        }

        $item = \App\Models\BobotKriteria::findOrFail($id);
        $item->update($request->all());
        return redirect()->route('bobot-kriteria.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        BobotKriteria::destroy($id);
        return redirect()->route('bobot-kriteria.index')->with('success', 'Data berhasil dihapus!');
    }
}
