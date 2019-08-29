<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\SIG005;
use App\models\SIG005L1;
use App\Models\Project;
use App\Models\ProjectHasPostulantes;
use App\Models\ProjectHasExpedientes;
use Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        //return $request;
        if ($request->input('nro_exp') || $request->input('project_id')) {

            $expediente = SIG005::where('NroExp',$request->input('nro_exp'))
            ->where('NroExpS','A')
            ->first();

            $project = Project::where('id',$request->input('project_id'))
            //->where('NroExpS','A')
            ->first();

            $exp = $request->input('nro_exp');
            $pro = $request->input('project_id');


            if (!$expediente) {
                //return redirect()->back()->with('error', 'Expediente no Existe!');
                return view('home',compact('exp','pro'))->with('successMsg', 'Expediente no Existe!');
            }

            if (!$project) {
                //return redirect()->back()->with('error', 'Proyecto no Existe!');
                return view('home',compact('exp','pro'))->with('successMsg', 'Proyecto no Existe!');
            }

            $postulantes = ProjectHasPostulantes::where('project_id',$project->id)->get();


            $pre = SIG005L1::where('NroExp',$request->input('nro_exp'))->first();
            $proexp = ProjectHasExpedientes::where('project_id',$project->id)->first();

            return view('home',compact('exp','pro','project','expediente','postulantes','pre','proexp'));

        }else{
            $exp = '';
            $pro = '';
            return view('home',compact('exp','pro'));
        }


    }


    public function store(Request $request)
    {

        //return $request;

        $project = Project::where('id',$request->input('project_idv'))->first();
        $postulantes = ProjectHasPostulantes::where('project_id',$project->id)->get();
        $date=new \DateTime();

        foreach ($postulantes as $key => $value) {
            $nombre = ($value->postulante_id?$value->getPostulante->first_name:"") .' '. ($value->postulante_id?$value->getPostulante->last_name:"");
            $reg = new SIG005L1();
            $reg->NroExp=$request->nro_expv;
            $reg->NroExpS='A';
            $reg->ExpDId=$key+1;
            $reg->ExpDPerCod=$value->postulante_id?$value->getPostulante->cedula:"";
            $reg->ExpDPerNom=$nombre;
            $reg->ExpDTel=$value->postulante_id?$value->getPostulante->mobile:"";
            $reg->ExpDNivel=ProjectHasPostulantes::getNivel($value->postulante_id);
            $reg->ExpDFec= date_format($date, 'Y-d-m H:i:s');
            $reg->ExpDUsuCod=strtoupper(substr(Auth::user()->username, 0, 10));
            $reg->ExpDImpr='N';
            $reg->ExpDNro='0';
            $reg->save();

        }

        $asig = new ProjectHasExpedientes();
        $asig->project_id=$request->project_idv;
        $asig->exp=$request->nro_expv;
        $asig->save();

        SIG005::where('NroExp', $request->input('nro_expv'))
        ->where('NroExpS','A')
        ->update(['ExpUltLin' => $postulantes->count()]);
        //$expediente = SIG005::where('NroExp',$request->input('nro_expv'))->where('NroExpS','A')->first();

        $exp = '';
        $pro = '';
        return view('home',compact('exp','pro'))->with('successMsgOK', 'Proyecto vinculado Correctamente!!');

    }
}
