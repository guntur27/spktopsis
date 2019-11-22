<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
    protected $table = 'hasil';
    protected $primaryKey = 'id_hasil';
    public $incrementing = false;

    public function ranking()
    {
        return $this->hasMany('App\Ranking', 'id_hasil', 'id_hasil');
    }
}
