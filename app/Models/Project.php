<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //
    //public $timestamps = false;

    //protected $dateFormat = 'd-m-Y H:i:s';
    protected $dateFormat = 'Y-m-d H:i:s.v';

    protected $dates = ['created_at','updated_at'];

    public $timestamps = false;

    //protected $connection = 'sqlsrv';

    protected $connection = 'pgsql1';

    /*public function getDateFormat()
    {
        return 'Y-d-m H:i:s.v';
    }*/

    protected $fillable = ['name', 'phone', 'sat_id','state_id','city_id','land_id','modalidad_id','localidad','leader_name','typology_id'];

    public function getSat() {
        return $this->hasOne('App\Models\Sat','NucCod','sat_id');
    }

    public function getLand() {
        return $this->hasOne('App\Models\Land','id','land_id');
    }

    public function getState() {
        return $this->hasOne('App\Models\Departamento','DptoId','state_id');
    }

    public function getCity() {
        return $this->hasOne('App\Models\Distrito','CiuId','city_id');
    }

    public function getModality() {
        return $this->hasOne('App\Models\Modality','id','modalidad_id');
    }
}
