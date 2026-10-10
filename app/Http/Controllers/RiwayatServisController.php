<?php

namespace App\Http\Controllers;

use App\Models\AntrianServis;
use Illuminate\Http\Request;

class RiwayatServisController extends Controller
{
    public function index(Request $request)
    {
        $query = AntrianServis::whereIn('status', ['selesai', 'batal']);

        // Filter Pencarian (No Polisi / Nama Pelanggan / Kode Antrian)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_polisi', 'like', "%{$search}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('kode_antrian', 'like', "%{$search}%");
            });
        }

        // Filter Tanggal Servis
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_servis', $request->tanggal);
        }

        // Data diurutkan dari yang paling baru
        $riwayats = $query->latest('updated_at')->paginate(10);

        return view('riwayat.index', compact('riwayats'));
    }

    public function show($id)
    {
        $riwayat = AntrianServis::findOrFail($id);
        return view('riwayat.show', compact('riwayat'));
    }

    public function destroy($id)
    {
        $riwayat = AntrianServis::findOrFail($id);
        $riwayat->delete();

        return redirect()->route('riwayat.index')->with('success', 'Data riwayat servis berhasil dihapus.');
    }
}
