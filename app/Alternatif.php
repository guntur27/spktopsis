<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    protected $table = 'alternatif';
    protected $primaryKey = 'id_alternatif';
    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo('App\User', 'username', 'username');
    }

    public function kriteria()
    {
        return $this->belongsToMany('App\Kriteria', 'bobot', 'id_alternatif', 'id_kriteria')->withPivot('bobot', 'id_bobot');
    }

    public function bobot()
    {
        return $this->hasMany('App\Bobot', 'id_alternatif', 'id_alternatif');
    }
}
