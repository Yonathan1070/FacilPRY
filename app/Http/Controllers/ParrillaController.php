<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\UsuariosRoles;
use App\Models\Tablas\Notificaciones;
use App\Models\Tablas\Parrilla;
use App\Models\Tablas\Publicacion;
use Illuminate\Support\Facades\DB;

class ParrillaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        can('listar-parrilla');
        $permisos = ['crear'=> can2('crear-parrilla'),'editar'=>can2('editar-parrilla'), 'eliminar'=>can2('eliminar-parrilla'),'publicacion'=>can2('listar-publicacion')];
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $rol = UsuariosRoles::findOrFail(session()->get('Usuario_Id'));
        if($rol->USR_RLS_Rol_Id==3){
          $parrillas= DB::table('TBL_Proyectos')
          ->join('TBL_Parrilla', 'TBL_Proyectos.id', '=', 'TBL_Parrilla.PRL_Proyecto_Id')
          ->join('TBL_Usuarios','TBL_Usuarios.id','=','TBL_Proyectos.PRY_Cliente_Id')
          ->select('TBL_Proyectos.PRY_Nombre_Proyecto', 'TBL_Parrilla.*')
          ->where('TBL_Proyectos.PRY_Cliente_Id',session()->get('Usuario_Id'))
          ->latest()
          ->get();
        }else{
          $parrillas= DB::table('TBL_Proyectos')
          ->join('TBL_Parrilla', 'TBL_Proyectos.id', '=', 'TBL_Parrilla.PRL_Proyecto_Id')
          ->select('TBL_Proyectos.PRY_Nombre_Proyecto', 'TBL_Parrilla.*')
          ->latest()
          ->get();
        }

        return view('parrilla.inicio', compact('datos', 'notificaciones', 'cantidad','permisos','parrillas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        can('crear-parrilla');
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $proyectos = DB::table('TBL_Proyectos')->select('id','PRY_Nombre_Proyecto')->get();
        return view('parrilla.create', compact('datos', 'notificaciones', 'cantidad','proyectos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Parrilla::create($request->all());
        return redirect()->back()->with('mensaje', 'Parrilla agregada con exito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $fecha = DB::table('TBL_Parrilla')->select('PRL_Anio','PRL_Mes')->where('id','=',$id)->first();
        $mes_num=$this->spanish_month($fecha->PRL_Mes);
        $month = $fecha->PRL_Anio.'-'.$mes_num;
        $data = $this->calendar_month($month,$id);
        // obtener mes en espanol
        $mespanish = $fecha->PRL_Mes;
        $mes = $data['month'];
        $array = array(
            'mes' => $mes,
            'mespanish' => $mespanish
        );
        return view('parrilla.parrilla', compact('datos', 'notificaciones', 'cantidad','array','data'));
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      can('editar-parrilla');
      $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
      $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
      $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
      $parrilla = Parrilla::findOrFail($id);
      return view('parrilla.edit', compact('parrilla', 'datos', 'notificaciones', 'cantidad'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      Parrilla::findOrFail($id)->update([
        'PRL_Mes' => $request['PRL_Mes'],
        'PRL_Anio' => $request['PRL_Anio']
    ]);

      return redirect()->route('parrilla')->with('mensaje', 'Parrilla actualizada con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        if(!can('eliminar-parrilla')){
            return response()->json(['mensaje' => 'np']);
        }else{
            if($request->ajax()){
                    try{
                        Parrilla::destroy($id);
                        return response()->json(['mensaje' => 'ok']);
                    }catch(QueryException $e){
                        return response()->json(['mensaje' => 'ng']);
                    }
                
            }
        }
    }

    public static function spanish_month($month)
    {

        $mes = $month;
        if ($month=="ENERO") {
          $mes = "01";
        }
        elseif ($month=="FEBRERO")  {
          $mes = "02";
        }
        elseif ($month=="MARZO")  {
          $mes = "03";
        }
        elseif ($month=="ABRIL") {
          $mes = "04";
        }
        elseif ($month=="MAYO") {
          $mes = "05";
        }
        elseif ($month=="JUNIO") {
          $mes = "06";
        }
        elseif ($month=="JULIO") {
          $mes = "07";
        }
        elseif ($month=="AGOSTO") {
          $mes = "08";
        }
        elseif ($month=="SEPTIEMBRE") {
          $mes = "09";
        }
        elseif ($month=="OCTUBRE") {
          $mes = "10";
        }
        elseif ($month=="NOVIEMBRE") {
          $mes = "11";
        }
        elseif ($month=="DICIEMBRE") {
          $mes = "12";
        }
        else {
          $mes = $month;
        }
        return $mes;
    }

    public static function calendar_month($month,$id){
              //$mes = date("Y-m");
      $mes = $month;
      //sacar el ultimo de dia del mes
      $daylast =  date("Y-m-d", strtotime("last day of ".$mes));
      //sacar el dia de dia del mes
      $fecha      =  date("Y-m-d", strtotime("first day of ".$mes));
      $daysmonth  =  date("d", strtotime($fecha));
      $montmonth  =  date("m", strtotime($fecha));
      $yearmonth  =  date("Y", strtotime($fecha));
      // sacar el lunes de la primera semana
      $nuevaFecha = mktime(0,0,0,$montmonth,$daysmonth,$yearmonth);
      $diaDeLaSemana = date("w", $nuevaFecha);
      $nuevaFecha = $nuevaFecha - ($diaDeLaSemana*24*3600); //Restar los segundos totales de los dias transcurridos de la semana
      $dateini = date ("Y-m-d",$nuevaFecha);
      //$dateini = date("Y-m-d",strtotime($dateini."+ 1 day"));
      // numero de primer semana del mes
      $semana1 = date("W",strtotime($fecha));
      // numero de ultima semana del mes
      $semana2 = date("W",strtotime($daylast));
      // semana todal del mes
      // en caso si es diciembre
      if (date("m", strtotime($mes))==12) {
          $semana = 5;
      }
      else {
        $semana = ($semana2-$semana1)+1;
      }
      // semana todal del mes
      $datafecha = $dateini;
      $calendario = array();
      $iweek = 0;
      while ($iweek < $semana):
          $iweek++;
          //echo "Semana $iweek <br>";
          //
          $weekdata = [];
          for ($iday=0; $iday < 7 ; $iday++){
            // code...
            $datafecha = date("Y-m-d",strtotime($datafecha."+ 1 day"));
            $datanew['mes'] = date("M", strtotime($datafecha));
            $datanew['dia'] = date("d", strtotime($datafecha));
            $datanew['fecha'] = $datafecha;
            //AGREGAR CONSULTAS EVENTO
            $datanew['evento'] = DB::table('TBL_Publicacion')
                                ->select('TBL_Publicacion.*')
                                ->where("PBL_Fecha",$datafecha)
                                ->where('PBL_Parrilla_Id',$id)
                                ->get();

            array_push($weekdata,$datanew);
          }
          $dataweek['semana'] = $iweek;
          $dataweek['datos'] = $weekdata;
          //$datafecha['horario'] = $datahorario;
          array_push($calendario,$dataweek);
      endwhile;
      $nextmonth = date("Y-M",strtotime($mes."+ 1 month"));
      $lastmonth = date("Y-M",strtotime($mes."- 1 month"));
      $month = date("M",strtotime($mes));
      $yearmonth = date("Y",strtotime($mes));
      //$month = date("M",strtotime("2019-03"));
      $data = array(
        'next' => $nextmonth,
        'month'=> $month,
        'year' => $yearmonth,
        'last' => $lastmonth,
        'calendar' => $calendario,
      );
      return $data;
    
    }
}
