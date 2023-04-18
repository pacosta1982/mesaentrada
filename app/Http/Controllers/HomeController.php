<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SIG005;
use App\Models\SIG005L1;
use App\Models\Project;
use App\Models\ProjectHasPostulantes;
use App\Models\ProjectHasExpedientes;
use Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        //return $expediente = SIG005::all();
        //return $expediente = SIG005::where('NroExp', 546123) ->first();
        //return $project = Project::where('id', 1875)->first();
        if($request==''){
            return $expediente = SIG005::all();
        }
        //return $request;
        if ($request->input('nro_exp') || $request->input('project_id')) {

            //return $request;
            $expediente = SIG005::where('NroExp',$request->input('nro_exp'))
            ->where('NroExpS','A')
            ->where('NroExpFch','>=','2019-09-02')
            ->first();

            $project = Project::where('id',$request->input('project_id'))
            //->where('NroExpS','A')
            ->first();

            $exp = $request->input('nro_exp');
            $pro = $request->input('project_id');


            if (!$expediente) {
                //return redirect()->back()->with('error', 'Expediente no Existe!');
                return view('home',compact('exp','pro'))->with('successMsg', 'Expediente no Existe o no es Valido!');
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

        //return $date=new \DateTime();
        $date = Carbon::now()->format('Ymd H:i:s');
        //return $ExpDFec= '20230417 09:36:00';


        foreach ($postulantes as $key => $value) {
            // $nombre = ($value->postulante_id?$value->getPostulante->first_name:"") .' '. ($value->postulante_id?$value->getPostulante->last_name:"");
            // echo $nombre;
            $nombre = ($value->postulante_id?$value->getPostulante->first_name:"") .' '. ($value->postulante_id?$value->getPostulante->last_name:"");
            $reg = new SIG005L1();
            $reg->NroExp=$request->nro_expv;
            $reg->NroExpS='A';
            $reg->ExpDId=$key+1;
            $reg->ExpDPerCod=$value->postulante_id?$value->getPostulante->cedula:"";
            //$reg->ExpDPerNom=$nombre;
            //$reg->ExpDPerNom=utf8_decode($nombre);
            $reg->ExpDPerNom=$nombre;
            $reg->ExpDTel=$value->postulante_id?$value->getPostulante->mobile:"";
            $reg->ExpDNivel=ProjectHasPostulantes::getNivel($value->postulante_id);
            $reg->ExpDFec= $date;
            //$reg->ExpDFec= '20230417 09:36:00';
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
