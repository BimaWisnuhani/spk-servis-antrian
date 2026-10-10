@extends('layouts.public')

@section('title', 'Booking Berhasil')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-xl-5 col-lg-6 col-md-8">
        <div class="card o-hidden border-0 shadow-lg my-3">
            <div class="card-body p-4 text-center">
                <div class="text-success mb-3">
                    <i class="fas fa-check-circle fa-4x"></i>
                </div>
                <h4 class="font-weight-bold text-gray-900">Booking Berhasil!</h4>
                <p class="text-muted small">Nomor Antrian Anda adalah: <strong>{{ $antrian->kode_antrian }}</strong></p>
                <p class="text-muted small">Terima kasih, pendaftaran servis Anda telah berhasil tersimpan di sistem.</p>

                <div class="bg-light p-3 rounded text-left mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">No. Polisi:</span>
                        <strong class="text-primary">{{ $antrian->no_polisi }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Layanan:</span>
                        <span class="text-dark font-weight-bold small text-right" style="max-width: 60%;">{{ $antrian->jenis_servis }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Tanggal Servis:</span>
                        <span class="text-dark">{{ \Carbon\Carbon::parse($antrian->tanggal_servis)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Jam Kedatangan:</span>
                        <span class="text-dark">{{ $antrian->waktu_datang }} WIB</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Status Pesan:</span>
                        <span class="badge badge-info text-uppercase">{{ $antrian->status_pesan }}</span>
                    </div>
                </div>

                <a href="{{ route('booking.create') }}" class="btn btn-primary btn-block font-weight-bold shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Form Booking
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
