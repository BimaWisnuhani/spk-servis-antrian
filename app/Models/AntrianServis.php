<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AntrianServis extends Model
{
    protected $table = 'antrian_servis';

    protected $fillable = [
        'kode_antrian',
        'nama_pelanggan',
        'no_polisi',
        'tipe_motor',
        'no_hp',
        'jenis_servis',
        'estimasi_waktu',
        'ketersediaan_mekanik',
        'antrian_pelanggan',
        'status_pesan',
        'tanggal_servis',
        'waktu_datang',
        'status',
        'nilai_v',
    ];

    public function subKriterias()
    {
        return $this->belongsToMany(SubKriteria::class, 'antrian_servis_sub_kriteria');
    }

    protected $casts = [
        'tanggal_servis' => 'date',
    ];
}
