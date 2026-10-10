<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubKriteria;
use App\Models\BobotKriteria;

class SubKriteriaController extends Controller
{
    public function index(Request $request)
    {
        $kriterias = BobotKriteria::all();

        // Fitur Filter berdasarkan kriteria
        $subKriterias = SubKriteria::with('kriteria')
            ->when($request->kriteria_id, function ($query) use ($request) {
                return $query->where('bobot_kriteria_id', $request->kriteria_id);
            })->get();

        // Data untuk mode Edit
        $editData = $request->edit ? SubKriteria::find($request->edit) : null;

        return view('sub-kriteria.index', compact('subKriterias', 'kriterias', 'editData'));
    }

    // FUNGSI INI YANG KURANG (Penyebab Error)
    public function store(Request $request)
    {
        $request->validate([
            'bobot_kriteria_id' => 'required',
            'nama_sub_kriteria' => 'required',
            'deskripsi' => 'required',
            'skor' => 'required|numeric|min:1|max:5', // Sesuai skor di gambar
        ]);

        SubKriteria::create($request->all());

        return redirect()->route('sub-kriteria.index')->with('success', 'Sub Kriteria berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bobot_kriteria_id' => 'required',
            'nama_sub_kriteria' => 'required',
            'deskripsi' => 'required',
            'skor' => 'required|numeric',
        ]);

        $sub = SubKriteria::findOrFail($id);
        $sub->update($request->all());

        return redirect()->route('sub-kriteria.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        SubKriteria::destroy($id);
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}
