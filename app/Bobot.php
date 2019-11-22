<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bobot extends Model
{
    protected $table = 'bobot';
    protected $primaryKey = 'id_bobot';
    public $incrementing = false;

    public function alternatif()
    {
        return $this->belongsTo('App\Alternatif', 'id_alternatif', 'id_aleternatif');
    }

    public function kriteria()
    {
        return $this->belongsTo('App\Kriteria', 'id_kriteria', 'id_kriteria');
    }
}
