<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BobotKriteria extends Model
{
    protected $table = 'bobot_kriteria';

    protected $fillable = [
        'kode',
        'nama_kriteria',
        'tipe',
        'bobot'
    ];
    

    // Relasi: Satu Kriteria memiliki banyak Sub Kriteria
    public function subKriterias()
    {
        return $this->hasMany(SubKriteria::class, 'bobot_kriteria_id');
    }

}
