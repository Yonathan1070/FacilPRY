<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use Illuminate\Support\Facades\DB;
use App\Models\Tablas\Proyectos;
use PDF;
use App\Models\Tablas\Empresas;
use Illuminate\Support\Carbon;
use stdClass;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\FacturasCobro;
use App\Models\Tablas\Notificaciones;
use Illuminate\Support\Facades\Mail;

/**
 * Inicio Controller, donde se visualizaran los proyectos y las facturas
 * para el cliente autenticado
 * 
 * @author: Yonathan Bohorquez
 * @email: ycbohorquez@ucundinamarca.edu.co
 * 
 * @author: Manuel Bohorquez
 * @email: jmbohorquez@ucundinamarca.edu.co
 * 
 * @version: dd/MM/yyyy 1.0
 */
class InicioController extends Controller
{
    /**
     * Muestra el listado de proyectos y de las facturas
     *
     * @return \Illuminate\View\View Vista de inicio
     */
    public function index()
    {
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $permisos = ['listarA'=>can2('listar-actividades')];

        $proyectos = Proyectos::obtenerProyectosCliente(session()->get('Usuario_Id'));
        $proyectosPagar = Proyectos::obtenerProyectosPagar();

        return view(
            'cliente.inicio',
            compact(
                'proyectos',
                'proyectosPagar',
                'datos',
                'notificaciones',
                'cantidad',
                'permisos'
            )
        );
    }

    /**
     * Muestra el formulario para pagar las actividades pendientes
     *
     * @param  $id  Identificador del proyecto
     * @return \Illuminate\View\View Vista para realizar el pago
     */
    public function pagar($id)
    {
        $notificaciones = Notificaciones::obtenerNotificaciones();
        $cantidad = Notificaciones::obtenerCantidadNotificaciones();
        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));
        
        $proyecto = Proyectos::obtenerProyecto($id);
        $informacion = FacturasCobro::obtenerDetalleFactura($id);
        $id_empresa = Empresas::obtenerIdEmpresa();
        $empresa = Empresas::findOrFail($id_empresa->USR_Empresa_Id);
        $total = FacturasCobro::obtenerTotalFactura($id);
        
        foreach ($informacion as $info) {
            $factura = $info->id;
        }
        
        $datosU = [
            'proyecto'=>$proyecto, 
            'informacion'=>$informacion, 
            'factura'=>$factura, 
            'fecha'=>Carbon::now()->toFormattedDateString(),
            'total'=>$total,
            'empresa'=>$empresa
        ];

        return view(
            'cliente.pagar',
            compact(
                'datos',
                'datosU',
                'notificaciones',
                'cantidad'
            )
        );
    }

    /**
     * Genera el PDF de las actividades del proyecto
     *
     * @param  $id  Identificador del proyecto
     * @return PDF->download()
     */
    public function generarPdf($id)
    {
        $proyecto = Proyectos::findOrFail($id);
        $actividades = Actividades::obtenerActividadesPDF($id);
        if (count($actividades) == 0) {
            return redirect()
                ->back()
                ->withErrors(
                    'No es posible generar el archivo sin que el proyecto tenga actividades'
                );
        }
        $id_empresa = Empresas::obtenerIdEmpresa();
        $empresa = Empresas::findOrFail($id_empresa->USR_Empresa_Id);
        $pdf = PDF::loadView(
            'includes.pdf.proyecto.actividades',
            compact('actividades', 'empresa')
        );

        $fileName = 'Actividades'.$proyecto->PRY_Nombre_Proyecto;
        
        return $pdf->download($fileName.'.pdf');
    }

    /**
     * Generar factura actividades
     *
     * @param  $id  Identificador del proyecto
     * @return PDF->download()
     */
    public function generarFactura($id)
    {
        $proyecto = Proyectos::obtenerProyecto($id);
        $informacion = FacturasCobro::obtenerDetalleFactura($id);
        $idEmpresa = DB::table('TBL_Proyectos as p')
            ->join('TBL_Empresas as eu', 'eu.id', '=', 'p.PRY_Empresa_Id')
            ->join('TBL_Empresas as ed', 'ed.id', '=', 'eu.EMP_Empresa_Id')
            ->select('ed.id')
            ->first()->id;
        $empresa = Empresas::findOrFail($idEmpresa);
        $total = FacturasCobro::obtenerTotalFactura($id);
        
        foreach ($informacion as $info) {
            $factura = $info->id;
        }
        
        $datos = [
            'proyecto'=>$proyecto, 
            'informacion'=>$informacion, 
            'factura'=>$factura, 
            'fecha'=>Carbon::now()->toFormattedDateString(),
            'total'=>$total,
            'empresa'=>$empresa
        ];
        
        $pdf = PDF::loadView('includes.pdf.factura.factura', compact('datos'));
        $fileName = 'FacturaINK-'.$proyecto->PRY_Nombre_Proyecto.'-'.$factura;
        
        return $pdf->download($fileName.'.pdf');
    }

    /**
     * Muestra la factura y el botón para realizar el pago
     *
     * @param  $id
     * @return json_encode()
     */
    public function informacionPago($id)
    {
        $proyecto = Proyectos::obtenerProyecto($id);
        $informacion = FacturasCobro::obtenerDetalleFactura($id);
        $idEmpresa = DB::table('TBL_Proyectos as p')
            ->join('TBL_Empresas as eu', 'eu.id', '=', 'p.PRY_Empresa_Id')
            ->join('TBL_Empresas as ed', 'ed.id', '=', 'eu.EMP_Empresa_Id')
            ->select('ed.id')
            ->first()->id;
        $empresa = Empresas::findOrFail($idEmpresa);
        $total = FacturasCobro::obtenerTotalFactura($id);
        
        foreach ($informacion as $info) {
            $factura = $info->id;
        }
        $datos = [
            'proyecto'=>$proyecto, 
            'informacion'=>$informacion, 
            'factura'=>$factura, 
            'fecha'=>Carbon::now()->toFormattedDateString(),
            'total'=>$total,
            'empresa'=>$empresa
        ];

        $infoPago = $this->informacionPayu($datos);

        return json_encode($infoPago);
    }

    /**
     * Metodo que obtiene la respuesta del pago realizado en la pasarela de pagos
     *
     * 
     * @return redirect()->route()
     */
    public function respuestaPago()
    {
        $correo = $_REQUEST['buyerEmail'];
        $fechaPago = Carbon::now();
        $estadoTx = $this->datosRespuesta();

        if ($estadoTx == "Transacción aprobada") {
            $usuario = Usuarios::where('USR_Correo_Usuario', '=', $correo)->first();
            $actividades = Actividades::obtenerActividadesPendientesPago($usuario->id);
            foreach ($actividades as $actividad) {
                Actividades::actualizarEstadoPago($actividad->id, 10, $fechaPago);
            }
            return redirect()
                ->route('inicio_cliente')
                ->with('mensaje', 'Pago exitoso.');
        }
        else if ($estadoTx == "Transacción rechazada") {
            return redirect()
                ->route('inicio_cliente')
                ->withErrors('Transacción Rechazada.');
        }
        else if ($estadoTx == "Error") {
            return redirect()
                ->route('inicio_cliente')
                ->withErrors('Error al pagar.');
        }
        else if ($estadoTx == "Transacción pendiente" ) {
            $usuario = Usuarios::where('USR_Correo_Usuario', '=', $correo)->first();
            $actividades = Actividades::obtenerActividadesPendientesPago($usuario->id);
            foreach ($actividades as $actividad) {
                Actividades::actualizarEstadoPago($actividad->id, 14, $fechaPago);
            }
            return redirect()
                ->route('inicio_cliente')
                ->with('mensaje', 'Pago Pendiente.');
        }
        else {
            return redirect()
                ->route('inicio_cliente')
                ->withErrors('Otro.');
        }
    }

    /**
     * Metodo que obtiene la respuesta de confirmación del pago realizado 
     * en la pasarela de pagos.
     *
     * 
     * @return redirect()->route()
     */
    public function confirmacionPago(){
        $ApiKey = "NDzo4w71RkoV65mpP4Fj3lI82v";
		$merchant_id =  $_REQUEST['merchant_id'];
		$state_pol = $_REQUEST['state_pol'];
		$response_code_pol=$_REQUEST['response_code_pol'];
		$reference_sale=$_REQUEST['reference_sale'];
		$reference_pol=$_REQUEST['reference_pol'];
		$sign=$_REQUEST['sign'];
		$extra1=$_REQUEST['extra1'];
		$payment_method=$_REQUEST['payment_method'];
		$payment_method_type=$_REQUEST['payment_method_type'];
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
        
        $usuario = Usuarios::where('USR_Correo_Usuario', '=', $email_buyer)->first();
        $actividades = Actividades::obtenerTransaccionPendiente($usuario->id);
        if($state_pol==4){
            foreach ($actividades as $actividad) {
                Actividades::actualizarEstadoPago($actividad->id, 10, $transaction_date);
            }
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
                $message->to(
                    'soporte@inkdigital.co',
                    'InkBrutalPRY, Software de Gestión de Proyectos'
                )->subject('Pago Actividad');
            });
            return redirect()
                ->route('inicio_cliente')
                ->with('mensaje', 'Pago exitoso.');
        }
        else{
            foreach ($actividades as $actividad) {
                Actividades::actualizarEstadoPago($actividad->id, 9, $transaction_date);
            }
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
                $message->to(
                    'soporte@inkdigital.co',
                    'InkBrutalPRY, Software de Gestión de Proyectos'
                )->subject('Pago Actividad');
            });

            return redirect()
                ->route('inicio_cliente')
                ->withErrors('Transacción Rechazada o Expirada.');
        }
    }

    /**
     * Cambia el estado de la notificación y retorna la ruta a la que debe redireccionar
     *
     * @param: $id Identificador de la notificación
     * @return json_encode Datos de la ruta
     * 
     */
    public function cambiarEstadoNotificacion($id)
    {
        $notificacion = Notificaciones::cambiarEstadoNotificacion($id);
        $notif = new stdClass();
        if($notificacion->NTF_Route != null && $notificacion->NTF_Parametro != null) {
            $notif->ruta = route(
                $notificacion->NTF_Route,
                [$notificacion->NTF_Parametro => $notificacion->NTF_Valor_Parametro]
            );
        } else if($notificacion->NTF_Route != null) {
            $notif->ruta = route($notificacion->NTF_Route);
        }

        return json_encode($notif);
    }

    /**
     * Cambia el estado de todas las notificaciónest retorna mesaje de éxito
     *
     * @param: $id Identificador del usuario autenticado
     * @return response()->json() Mensaje de exito
     * 
     */
    public function cambiarEstadoTodasNotificaciones($id)
    {
        Notificaciones::cambiarEstadoTodas($id);
        
        return response()
            ->json(['mensaje' => 'ok']);
    }

    #Metodo que obtiene los datos de la api de PayU
    public function informacionPayu($datos){
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now());

        $apiKey="NDzo4w71RkoV65mpP4Fj3lI82v";
        $infoPago = new stdClass();
        $infoPago->merchantId="708186";
        $infoPago->accountId="711450";
        $infoPago->description="Cobro Proyecto ".$datos['proyecto']->PRY_Nombre_Proyecto;
        $infoPago->referenceCode="Pago".$datos['factura'].'-'.$fecha->format("U");
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

    #Metodo que obtiene los datos de la página de respuesta de PayU
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
}
