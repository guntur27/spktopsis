<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $table = 'kriteria';
    protected $primaryKey = 'id_kriteria';
    public $incrementing = false;

    public function alternatif()
    {
        return $this->belongsToMany('App\Alternatif', 'bobot', 'id_kriteria', 'id_alternatif')->withPivot('bobot', 'id_bobot');
    }

    public function bobot()
    {
        return $this->hasMany('App\Bobot', 'id_kriteria', 'id_kriteria');
    }
}
