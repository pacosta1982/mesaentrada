<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SIG0001 extends Model
{
    protected $table = 'SIG0001';

    protected $primaryKey = 'TexCod';

    protected $connection = 'sqlsrvsecond';

    /*public function distrito() {
        return $this->hasOne('App\Distrito','CiuId','CiuId');
    }

    public function departamento() {
        return $this->hasOne('App\Departamento','DptoId','DptoId');
    }*/
}
