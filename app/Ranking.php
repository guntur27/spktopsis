<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    protected $table = 'ranking';
    protected $primaryKey = 'id_ranking';
    public $incrementing = false;
    public $timestamps = false;

    public function hasil()
    {
        return $this->belongsTo('App\Hasil', 'id_hasil', 'id_hasil');
    }
}
