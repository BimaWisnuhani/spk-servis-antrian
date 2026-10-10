<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
    protected $table = 'sub_kriteria';

    protected $fillable = [
        'bobot_kriteria_id',
        'nama_sub_kriteria',
        'deskripsi',
        'skor'
    ];

    // Relasi: Sub Kriteria ini milik salah satu Kriteria
    public function kriteria()
    {
        return $this->belongsTo(BobotKriteria::class, 'bobot_kriteria_id');
    }

    public function antrianServis()
    {
        return $this->belongsToMany(AntrianServis::class, 'antrian_servis_sub_kriteria');
    }
}
