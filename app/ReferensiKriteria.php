<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferensiKriteria extends Model
{
    protected $table = 'referensi_kriteria';
    protected $primaryKey = 'id';

    public function kriteria()
    {
        return $this->belongsTo('App\Kriteria', 'id_kriteria', 'id_kriteria');
    }
}
