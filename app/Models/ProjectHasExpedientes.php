<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use App\Models\PostulanteHasBeneficiary;
//use App\Models\Postulante;

class ProjectHasExpedientes extends Model
{
    //
    //protected $connection = 'sqlsrv';
    protected $connection = 'pgsql1';
    // public function getDateFormat()
    // {
    //     return 'Y-d-m H:i:s.v';
    // }

    public function getExpediente() {
        return $this->hasOne('App\Models\SIG005','NroExp','exp');
    }

    /*public function getMembers() {
        return $this->hasMany('App\Models\PostulanteHasBeneficiary', 'postulante_id', 'postulante_id');
    }*/

    /*public static function getNivel($id){

        $postulante = Postulante::find($id);
        $miembros = PostulanteHasBeneficiary::where('postulante_id',$id)->get();
        $total = Postulante::whereIn('id',$miembros->pluck('miembro_id'))->get();
        $ingreso = $total->sum('ingreso');//return $miembros->pluck('miembro_id');
        $grupo = $ingreso + $postulante->ingreso;

        if ($grupo < 2192839)   {return '4'; }
        if ($grupo < 4166394)   {return '3'; }
        if ($grupo < 7455653)   {return '2'; }
        if ($grupo < 10964195)  {return '1';}

    }

    public static function getIngreso($id){

        $postulante = Postulante::find($id);
        $miembros = PostulanteHasBeneficiary::where('postulante_id',$id)->get();
        $total = Postulante::whereIn('id',$miembros->pluck('miembro_id'))->get();
        $ingreso = $total->sum('ingreso');//return $miembros->pluck('miembro_id');
        $total = $ingreso + $postulante->ingreso;
        return $total;

    }*/


}
