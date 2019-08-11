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

class InicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datosU = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $proyectos = DB::table('TBL_Actividades as a')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'a.ACT_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->join('TBL_Empresas as e', 'e.id', '=', 'u.USR_Empresa_Id')
            ->select('a.*', 'p.*', 'a.id as Id_Actividad')
            ->where('p.PRY_Cliente_Id', '=', session()->get('Usuario_Id'))
            ->where('a.ACT_Estado_Actividad', '=', 'Esperando Pago')
            ->where('a.ACT_Costo_Actividad', '<>', 0)
            ->groupBy('p.id')
            ->get();
            
        return view('cliente.inicio', compact('proyectos', 'datosU'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pagar($id)
    {
        $datosU = Usuarios::findOrFail(session()->get('Usuario_Id'));
        $proyecto = DB::table('TBL_Proyectos as p')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->where('p.id', '=', $id)
            ->first();
        $informacion = DB::table('TBL_Facturas_Cobro as fc')
            ->join('TBL_Actividades as a', 'a.id', '=', 'fc.FACT_Actividad_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'a.ACT_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('p.*', 'a.*', 'u.*', 'fc.*')
            ->where('a.ACT_Costo_Actividad', '<>', 0)
            ->where('a.ACT_Estado_Actividad', '=', 'Esperando Pago')
            ->where('p.id', '=', $id)
            ->get();
        $empresa = Empresas::findOrFail($proyecto->USR_Empresa_Id);
        $total = DB::table('TBL_Facturas_Cobro as fc')
            ->join('TBL_Actividades as a', 'a.id', '=', 'fc.FACT_Actividad_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'a.ACT_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->where('a.ACT_Estado_Actividad', '=', 'Esperando Pago')
            ->select('a.*', DB::raw('SUM(a.ACT_Costo_Actividad) as Costo'))
            ->groupBy('a.ACT_Proyecto_Id')
            ->where('p.id', '=', $id)
            ->first();
        foreach ($informacion as $info) {
            $factura = $info->id;
        }

        $datos = ['proyecto'=>$proyecto, 
            'informacion'=>$informacion, 
            'factura'=>$factura, 
            'fecha'=>Carbon::now()->toFormattedDateString(),
            'total'=>$total,
            'empresa'=>$empresa];
        return view('cliente.pagar', compact('datos', 'datosU'));
    }

    public function generarPdf($id)
    {
        $proyecto = Proyectos::findOrFail($id);
        $actividades = DB::table('TBL_Actividades as a')
            ->join('TBL_Usuarios as us', 'us.id', '=', 'a.ACT_Trabajador_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'a.ACT_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->join('TBL_Empresas as e', 'e.id', '=', 'u.USR_Empresa_Id')
            ->where('p.id', '=', $id)
            ->select('a.*', 'us.USR_Nombre as NombreT', 'us.USR_Apellido as ApellidoT', 'p.*', 'p.*', 'u.*', 'e.*')
            ->get();
        
        $pdf = PDF::loadView('includes.pdf.proyecto.actividades', compact('actividades'));

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
        $informacion = DB::table('TBL_Facturas_Cobro as fc')
            ->join('TBL_Actividades as a', 'a.id', '=', 'fc.FACT_Actividad_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'a.ACT_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('p.*', 'a.*', 'u.*', 'fc.*')
            ->where('a.ACT_Costo_Actividad', '<>', 0)
            ->where('a.ACT_Estado_Actividad', '=', 'Facturado')
            ->where('p.id', '=', $id)
            ->get();
        $empresa = Empresas::findOrFail($proyecto->USR_Empresa_Id);
        $total = DB::table('TBL_Facturas_Cobro as fc')
            ->join('TBL_Actividades as a', 'a.id', '=', 'fc.FACT_Actividad_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'a.ACT_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('a.*', DB::raw('SUM(a.ACT_Costo_Actividad) as Costo'))
            ->groupBy('a.ACT_Proyecto_Id')
            ->where('p.id', '=', $id)
            ->first();
        foreach ($informacion as $info) {
            $factura = $info->id;
        }

        $datos = ['proyecto'=>$proyecto, 
            'informacion'=>$informacion, 
            'factura'=>$factura, 
            'fecha'=>Carbon::now()->toFormattedDateString(),
            'total'=>$total,
            'empresa'=>$empresa];

        //return view('includes.pdf.factura.factura', compact('datos'));
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
        $informacion = DB::table('TBL_Facturas_Cobro as fc')
            ->join('TBL_Actividades as a', 'a.id', '=', 'fc.FACT_Actividad_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'a.ACT_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('p.*', 'a.*', 'u.*', 'fc.*')
            ->where('a.ACT_Costo_Actividad', '<>', 0)
            ->where('a.ACT_Costo_Actividad', '<>', 0)
            ->where('p.id', '=', $id)
            ->get();
        $empresa = Empresas::findOrFail($proyecto->USR_Empresa_Id);
        $total = DB::table('TBL_Facturas_Cobro as fc')
            ->join('TBL_Actividades as a', 'a.id', '=', 'fc.FACT_Actividad_Id')
            ->join('TBL_Proyectos as p', 'p.id', '=', 'a.ACT_Proyecto_Id')
            ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
            ->select('a.*', DB::raw('SUM(a.ACT_Costo_Actividad) as Costo'))
            ->groupBy('a.ACT_Proyecto_Id')
            ->where('p.id', '=', $id)
            ->first();
        foreach ($informacion as $info) {
            $factura = $info->id;
        }

        $datos = ['proyecto'=>$proyecto, 
            'informacion'=>$informacion, 
            'factura'=>$factura, 
            'fecha'=>Carbon::now()->toFormattedDateString(),
            'total'=>$total,
            'empresa'=>$empresa];

        $apiKey="4Vj8eK4rloUd272L48hsrarnUA";
        
        $infoPago = new stdClass();
        $infoPago->merchantId="508029";
        $infoPago->accountId="512321";
        $infoPago->description="Cobro Proyecto ".$datos['proyecto']->PRY_Nombre_Proyecto;
        $infoPago->referenceCode="Pago".$datos['proyecto']->id;
        $infoPago->amount=$datos['total']->Costo;
        $infoPago->tax="0";
        $infoPago->taxReturnBase="0";
        $infoPago->currency="COP";
        $infoPago->signature=md5($apiKey."~".$infoPago->merchantId."~".$infoPago->referenceCode."~".$datos['total']->Costo."~COP");
        $infoPago->test="1";
        $infoPago->buyerFullName=$datos['proyecto']->USR_Nombre." ".$datos['proyecto']->USR_Apellido;
        $infoPago->buyerEmail=$datos['proyecto']->USR_Correo;
        $infoPago->responseUrl="http://facilpry.test/cliente/respuesta-pago";
        $infoPago->confirmationUrl="http://facilpry.test/cliente/confirmacion-pago";

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
        $correo = $_REQUEST['buyerEmail'];
        $fechaPago = $_REQUEST['processingDate'];
        
        if ($transactionState == 4 ) {
            $estadoTx = "Transacción aprobada";
            $usuario = Usuarios::where('USR_Correo', '=', $correo)->first();
            $actividades = DB::table('TBL_Actividades as a')
                ->join('TBL_Proyectos as p', 'p.id', '=', 'a.ACT_Proyecto_Id')
                ->join('TBL_Usuarios as u', 'u.id', '=', 'p.PRY_Cliente_Id')
                ->where('a.ACT_Estado_Actividad', '=', 'Esperando Pago')
                ->where('u.id', '=', $usuario->id)
                ->select('a.id')
                ->get();
            foreach ($actividades as $actividad) {
                Actividades::findOrFail($actividad->id)->update([
                    'ACT_Estado_Actividad' => 'Pagado',
                    'ACT_Fecha_Pago' => $fechaPago
                ]);
            }
            return redirect()->route('inicio_cliente')->with('mensaje', 'Pago exitoso.');
        }
        
        /*else if ($transactionState == 6 ) {
            $estadoTx = "Transacción rechazada";
        }
        
        else if ($transactionState == 104 ) {
            $estadoTx = "Error";
        }
        
        else if ($transactionState == 7 ) {
            $estadoTx = "Transacción pendiente";
        }
        
        else {
            $estadoTx=$mensaje;
        }*/

        $informacion = [
            'ApiKey' => $ApiKey,
            'merchant_id' => $merchant_id,
            'referenceCode' => $referenceCode,
            'TX_VALUE' => $TX_VALUE,
            'New_value' => $New_value,
            'currency' => $currency,
            'transactionState' => $transactionState,
            'firma_cadena' => $firma_cadena,
            'firmacreada' => $firmacreada,
            'firma' => $firma,
            'reference_pol' => $reference_pol,
            'cus' => $cus,
            'extra1' => $extra1,
            'pseBank' => $pseBank,
            'lapPaymentMethod' => $lapPaymentMethod,
            'transactionId' => $transactionId,
            'correo' => $correo,
            'fechaPago' => $fechaPago
        ];
        return view('cliente.respuesta', compact('informacion'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function confirmacionPago()
    {
        
    }
}
