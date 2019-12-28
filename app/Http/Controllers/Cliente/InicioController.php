<?php

namespace App\Http\Controllers\Cliente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\Proyectos;
use PDF;
use App\Models\Tablas\Empresas;
use Illuminate\Support\Carbon;
use stdClass;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\Notificaciones;
use Illuminate\Support\Facades\Mail;

class InicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));

        $proyectos = $this->proyectos();
        $proyectosPagar = $this->proyectosPagarConsulta();

        return view('cliente.inicio', compact('proyectos', 'proyectosPagar', 'datos', 'notificaciones', 'cantidad'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pagar($id)
    {
        $notificaciones = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->orderByDesc('created_at')->get();
        $cantidad = Notificaciones::where('NTF_Para', '=', session()->get('Usuario_Id'))->where('NTF_Estado', '=', 0)->count();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $proyecto = DB::table('TBL_Proyectos as p')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->where('p.id', '=', $id)
            ->first();
        $informacion = $this->informacionFacturacion($id);
        $id_empresa = DB::table('TBL_Usuarios as uu')
            ->join('TBL_Usuarios as ud', 'uu.id', '=', 'ud.USR_Supervisor_Id')
            ->where('ud.id', '=', session()->get('Usuario_Id'))
            ->select('uu.USR_Empresa_Id')
            ->first();
        $empresa = Empresas::findOrFail($id_empresa->USR_Empresa_Id);
        $total = $this->totalCosto($id);
        foreach ($informacion as $info) {
            $factura = $info->id;
        }
        $datosU = ['proyecto'=>$proyecto, 
            'informacion'=>$informacion, 
            'factura'=>$factura, 
            'fecha'=>Carbon::now()->toFormattedDateString(),
            'total'=>$total,
            'empresa'=>$empresa];
        return view('cliente.pagar', compact('datos', 'datosU', 'notificaciones', 'cantidad'));
    }

    public function generarPdf($id)
    {
        $proyecto = Proyectos::findOrFail($id);
        $actividades = DB::table('TBL_Actividades as a')
            ->join('TBL_Usuarios as us', 'us.id', '=', 'a.ACT_Trabajador_Id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->join('TBL_Empresas as e', 'e.id', '=', 'u.USR_Empresa_Id')
            ->join('TBL_Estados as es', 'es.id', '=', 'a.ACT_Estado_Id')
            ->join('TBL_Usuarios_Roles as ur', 'ur.USR_RLS_Usuario_Id', '=', 'u.id')
            ->join('TBL_Roles as ro', 'ro.id', '=', 'ur.USR_RLS_Rol_Id')
            ->select('a.*', 'es.*', 'us.USR_Nombres_Usuario as NombreT', 'us.USR_Apellidos_Usuario as ApellidoT', 'p.*', 'p.*', 'u.*', 'e.*')
            ->where('p.id', '=', $id)
            ->where('r.id', '<>', 5)
            ->get();
        $id_empresa = DB::table('TBL_Usuarios as uu')
            ->join('TBL_Usuarios as ud', 'uu.id', '=', 'ud.USR_Supervisor_Id')
            ->where('ud.id', '=', session()->get('Usuario_Id'))
            ->select('uu.USR_Empresa_Id')
            ->first();
        $empresa = Empresas::findOrFail($id_empresa->USR_Empresa_Id);
        $pdf = PDF::loadView('includes.pdf.proyecto.actividades', compact('actividades', 'empresa'));

        $fileName = 'Actividades'.$proyecto->PRY_Nombre_Proyecto;
        return $pdf->download($fileName);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generarFactura($id)
    {
        $proyecto = DB::table('TBL_Proyectos as p')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->where('p.id', '=', $id)
            ->first();
        $informacion = $this->informacionFacturacion($id);
        $id_empresa = DB::table('TBL_Usuarios as uu')
            ->join('TBL_Usuarios as ud', 'uu.id', '=', 'ud.USR_Supervisor_Id')
            ->where('ud.id', '=', session()->get('Usuario_Id'))
            ->select('uu.USR_Empresa_Id')
            ->first();
        $empresa = Empresas::findOrFail($id_empresa->USR_Empresa_Id);
        $total = $this->totalCosto($id);
        foreach ($informacion as $info) {
            $factura = $info->id;
        }
        $datos = ['proyecto'=>$proyecto, 
            'informacion'=>$informacion, 
            'factura'=>$factura, 
            'fecha'=>Carbon::now()->toFormattedDateString(),
            'total'=>$total,
            'empresa'=>$empresa];
        $pdf = PDF::loadView('includes.pdf.factura.factura', compact('datos'));
        $fileName = 'FacturaINK-'.$factura;
        return $pdf->download($fileName);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function informacionPago($id)
    {
        $proyecto = DB::table('TBL_Proyectos as p')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->where('p.id', '=', $id)
            ->first();
        $informacion = $this->informacionFacturacion($id);
        $id_empresa = DB::table('TBL_Usuarios as uu')
            ->join('TBL_Usuarios as ud', 'uu.id', '=', 'ud.USR_Supervisor_Id')
            ->where('ud.id', '=', session()->get('Usuario_Id'))
            ->select('uu.USR_Empresa_Id')
            ->first();
        $empresa = Empresas::findOrFail($id_empresa->USR_Empresa_Id);
        $total = $this->totalCosto($id);
        foreach ($informacion as $info) {
            $factura = $info->id;
        }
        $datos = ['proyecto'=>$proyecto, 
            'informacion'=>$informacion, 
            'factura'=>$factura, 
            'fecha'=>Carbon::now()->toFormattedDateString(),
            'total'=>$total,
            'empresa'=>$empresa];

        $infoPago = $this->informacionPayu($datos);

        return json_encode($infoPago);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function respuestaPago()
    {
        $correo = $_REQUEST['buyerEmail'];
        $fechaPago = Carbon::now();
        $estadoTx = $this->datosRespuesta();

        if ($estadoTx == "Transacción aprobada") {
            $usuario = Usuarios::where('USR_Correo_Usuario', '=', $correo)->first();
            $actividades = $this->consultaActividades($usuario->id);
            foreach ($actividades as $actividad) {
                $this->actualizarEstado($actividad->id, 10, $fechaPago);
            }
            return redirect()->route('inicio_cliente')->with('mensaje', 'Pago exitoso.');
        }
        else if ($estadoTx == "Transacción rechazada") {
            return redirect()->route('inicio_cliente')->withErrors('Transacción Rechazada.');
        }
        else if ($estadoTx == "Error") {
            return redirect()->route('inicio_cliente')->withErrors('Error al pagar.');
        }
        else if ($estadoTx == "Transacción pendiente" ) {
            $usuario = Usuarios::where('USR_Correo_Usuario', '=', $correo)->first();
            $actividades = $this->consultaActividades($usuario->id);
            foreach ($actividades as $actividad) {
                $this->actualizarEstado($actividad->id, 13, $fechaPago);
            }
            return redirect()->route('inicio_cliente')->with('mensaje', 'Pago Pendiente.');
        }
        else {
            return redirect()->route('inicio_cliente')->withErrors('Otro.');
        }
    }

    public function confirmacionPago(){
        $ApiKey = "NDzo4w71RkoV65mpP4Fj3lI82v";
		$merchant_id =  $_REQUEST['merchant_id'];
		$state_pol = $_REQUEST['state_pol'];
		$response_code_pol=$_REQUEST['response_code_pol	'];
		$reference_sale=$_REQUEST['reference_sale'];
		$reference_pol=$_REQUEST['reference_pol'];
		$sign=$_REQUEST['sign'];
		$extra1=$_REQUEST['extra1'];
		$payment_method=$_REQUEST['payment_method'];
		$payment_method_type=$_REQUEST['payment_method_type	'];
		$installments_number=$_REQUEST['installments_number'];	
		$TX_VALUE = $_REQUEST['value'];
		$New_value = number_format($TX_VALUE, 1, '.', '');
		$transaction_date=$_REQUEST['transaction_date'];
		$currency=$_REQUEST['currency'];
		$email_buyer=$_REQUEST['email_buyer'];
		$cus=$_REQUEST['cus'];
		$pse_bank=$_REQUEST['pse_bank'];
		$test=$_REQUEST['test'];
		$description=$_REQUEST['description'];

        $phone=$_REQUEST['phone'];
        
        if($state_pol==4){
            Mail::send('general.correo.respuesta', [
                'estado' => '',
                'nombre' => 'Ink Brutal',
                'descripcion' => $description,
                'email' => $email_buyer,
                'telefono' => $phone,
                'referencia' => $reference_sale,
                'valor' => $New_value
            ], function($message){
                $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
                $message->to('soporte@inkdigital.co', 'Bienvenido(a) a InkBrutalPRY, Software de Gestión de Proyectos')
                    ->subject('Pago Actividad');
            });
        }
        else{
            Mail::send('general.correo.respuesta', [
                'estado' => 'PERO NO HA SIDO EXITOSA',
                'nombre' => 'Ink Brutal',
                'descripcion' => $description,
                'email' => $email_buyer,
                'telefono' => $phone,
                'referencia' => $reference_sale,
                'valor' => $New_value
            ], function($message){
                $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
                $message->to('soporte@inkdigital.co', 'Bienvenido(a) a InkBrutalPRY, Software de Gestión de Proyectos')
                    ->subject('Pago Actividad');
            });
        }
    }

    public function cambiarEstadoNotificacion($id)
    {
        $notificacion = Notificaciones::findOrFail($id);
        $notificacion->update([
            'NTF_Estado' => 1
        ]);
        $notif = new stdClass();
        if($notificacion->NTF_Route != null && $notificacion->NTF_Parametro != null)
            $notif->ruta = route($notificacion->NTF_Route, [$notificacion->NTF_Parametro => $notificacion->NTF_Valor_Parametro]);
        else if($notificacion->NTF_Route != null)
            $notif->ruta = route($notificacion->NTF_Route);
        return json_encode($notif);
    }

    public function proyectosPagarConsulta()
    {
        $proyectos = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->join('TBL_Empresas as e', 'e.id', '=', 'u.USR_Empresa_Id')
            ->join('TBL_Estados as es', 'es.id', '=', 'a.ACT_Estado_Id')
            ->select('a.*', 'p.*', 'a.id as Id_Actividad')
            ->where('p.PRY_Cliente_Id', '=', session()->get('Usuario_Id'))
            ->where('es.id', '=', 9)
            ->where('a.ACT_Costo_Real_Actividad', '<>', 0)
            ->groupBy('p.id')
            ->get();
        
        return $proyectos;
    }

    public function proyectos(){
        $proyectos = DB::table('TBL_Proyectos as p')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('p.*')
            ->where('p.PRY_Cliente_Id', '=', session()->get('Usuario_Id'))
            ->get();
        return $proyectos;
    }

    public function informacionFacturacion($id){
        $informacion = DB::table('TBL_Facturas_Cobro as fc')
            ->join('TBL_Actividades as a', 'a.id', '=', 'fc.FACT_Actividad_Id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('p.*', 'r.*', 'a.*', 'u.*', 'fc.*')
            ->where('a.ACT_Costo_Real_Actividad', '<>', 0)
            ->where('a.ACT_Estado_Id', '=', 9)
            ->where('p.id', '=', $id)
            ->get();

        return $informacion;
    }

    public function totalCosto($id){
        $total = DB::table('TBL_Facturas_Cobro as fc')
            ->join('TBL_Actividades as a', 'a.id', '=', 'fc.FACT_Actividad_Id')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->where('a.ACT_Estado_Id', '=', 9)
            ->select('a.*', DB::raw('SUM(a.ACT_Costo_Real_Actividad) as Costo'))
            ->groupBy('r.REQ_Proyecto_Id')
            ->where('p.id', '=', $id)
            ->first();

        return $total;
    }

    public function informacionPayu($datos){
        $apiKey="NDzo4w71RkoV65mpP4Fj3lI82v";
        $infoPago = new stdClass();
        $infoPago->merchantId="708186";
        $infoPago->accountId="711450";
        $infoPago->description="Cobro Proyecto ".$datos['proyecto']->PRY_Nombre_Proyecto;
        $infoPago->referenceCode="Pago".$datos['factura'];
        $infoPago->amount=$datos['total']->Costo;
        $infoPago->tax="0";
        $infoPago->taxReturnBase="0";
        $infoPago->currency="COP";
        $infoPago->signature=md5($apiKey."~".$infoPago->merchantId."~".$infoPago->referenceCode."~".$datos['total']->Costo."~COP");
        $infoPago->test="0";
        $infoPago->buyerFullName=$datos['proyecto']->USR_Nombres_Usuario." ".$datos['proyecto']->USR_Apellidos_Usuario;
        $infoPago->buyerEmail=$datos['proyecto']->USR_Correo_Usuario;
        $infoPago->responseUrl=route("respuesta_pago_cliente");
        $infoPago->confirmationUrl=route("confirmacion_pago_cliente");

        return $infoPago;
    }

    public function datosRespuesta(){
        $ApiKey = "4Vj8eK4rloUd272L48hsrarnUA";
        $merchant_id = $_REQUEST['merchantId'];
        $referenceCode = $_REQUEST['referenceCode'];
        $TX_VALUE = $_REQUEST['TX_VALUE'];
        $New_value = number_format($TX_VALUE, 1, '.', '');
        $currency = $_REQUEST['currency'];
        $transactionState = $_REQUEST['transactionState'];
        $firma_cadena = "$ApiKey~$merchant_id~$referenceCode~$New_value~$currency~$transactionState";
        $firmacreada = md5($firma_cadena);
        $firma = $_REQUEST['signature'];
        $reference_pol = $_REQUEST['reference_pol'];
        $cus = $_REQUEST['cus'];
        $extra1 = $_REQUEST['description'];
        $pseBank = $_REQUEST['pseBank'];
        $lapPaymentMethod = $_REQUEST['lapPaymentMethod'];
        $transactionId = $_REQUEST['transactionId'];

        if ($transactionState == 4 ) {
            $estadoTx = "Transacción aprobada";
        }
        else if ($transactionState == 6 ) {
            $estadoTx = "Transacción rechazada";
        }
        else if ($transactionState == 104 ) {
            $estadoTx = "Error";
        }
        else if ($transactionState == 7 ) {
            $estadoTx = "Transacción pendiente";
        }
        else {
            $estadoTx="Otro";
        }
        return $estadoTx;
    }

    public function consultaActividades($id){
        $actividades = DB::table('TBL_Actividades as a')
            ->join('TBL_Requerimientos as r', 'r.id', '=', 'a.ACT_Requerimiento_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'r.REQ_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->where('a.ACT_Estado_Id', '=', 9)
            ->where('u.id', '=', $id)
            ->select('a.id')
            ->get();

        return $actividades;
    }

    public function actualizarEstado($id, $estado, $fecha){
        Actividades::findOrFail($id)->update([
            'ACT_Estado_Id' => $estado,
            'ACT_Fecha_Pago' => $fecha
        ]);
    }
}
