<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AntrianServisController;
use App\Http\Controllers\BobotKriteriaController;
use App\Http\Controllers\SubKriteriaController;
use App\Http\Controllers\PerhitunganController;
use App\Http\Controllers\RiwayatServisController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/sukses/{id}', [BookingController::class, 'sukses'])->name('booking.sukses');

Route::get('/riwayat-servis', [RiwayatServisController::class, 'index'])->name('riwayat.index');
Route::get('/riwayat-servis/{id}', [RiwayatServisController::class, 'show'])->name('riwayat.show');
Route::delete('/riwayat-servis/{id}', [RiwayatServisController::class, 'destroy'])->name('riwayat.destroy');

Route::get('/', function () {
    return redirect()->route('login'); // Langsung arahkan ke login jika buka domain utama
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- GRUP UNTUK PENGUNJUNG (BELUM LOGIN) ---
Route::middleware('guest')->group(function () {
    // Menampilkan halaman login (Metode GET)
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::get('/login', [AuthController::class, 'index']);

    // Memproses data login (Metode POST)
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});
// Route untuk yang sudah login
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::patch('/antrian/{id}/status', [AntrianServisController::class, 'updateStatus'])->name('antrian.updateStatus');
    Route::get('/antrian', [AntrianServisController::class, 'index'])->name('antrian.index');
    Route::resource('antrian', AntrianServisController::class)->except(['index']);
    Route::patch('/antrian/{id}/status', [AntrianServisController::class, 'updateStatus'])->name('antrian.updateStatus');
    Route::patch('/antrian/{id}/validasi', [AntrianServisController::class, 'validasiBooking'])->name('antrian.validasiBooking');

    Route::resource('bobot-kriteria', BobotKriteriaController::class);
    Route::put('/bobot-kriteria/{id}', [BobotKriteriaController::class, 'update'])->name('bobot-kriteria.update');
    Route::resource('sub-kriteria', SubKriteriaController::class);

    // Tambahkan kriteria & subkriteria di sini
    // Route::resource('kriteria', ...);

    Route::get('/perhitungan', [PerhitunganController::class, 'index'])->name('perhitungan.index');
    Route::get('/perhitungan/pdf', [PerhitunganController::class, 'exportPdf'])->name('perhitungan.pdf');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
