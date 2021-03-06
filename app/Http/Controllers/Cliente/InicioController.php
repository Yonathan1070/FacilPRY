<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Tablas\Usuarios;
use App\Models\Tablas\Proyectos;
use PDF;
use App\Models\Tablas\Empresas;
use Illuminate\Support\Carbon;
use stdClass;
use App\Models\Tablas\Actividades;
use App\Models\Tablas\FacturaAdicional;
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
        $permisos = ['listarA'=>can2('listar-actividades')];

        $idUsuario = session()->get('Usuario_Id');
        
        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $datos = Usuarios::findOrFail(
            $idUsuario
        );

        $proyectos = Proyectos::obtenerProyectosCliente(
            $idUsuario
        );
        
        $proyectosPagar = Proyectos::obtenerProyectosPagar(
            $idUsuario
        );

        $factAdicional = Proyectos::obtenerProyectosConFacturasAdicionalesById(
            $idUsuario
        );

        return view(
            'cliente.inicio',
            compact(
                'proyectos',
                'factAdicional',
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
        $idUsuario = session()->get('Usuario_Id');

        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);
        
        $proyecto = Proyectos::obtenerProyecto($id);
        $empresaProyecto = Empresas::findOrFail($proyecto->PRY_Empresa_Id);
        $informacion = FacturasCobro::obtenerDetalleFactura($id);
        $idEmpresa = Empresas::obtenerEmpresa()->id;
        $empresa = Empresas::findOrFail($idEmpresa);
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
            'empresa'=>$empresa,
            'empresaProyecto'=>$empresaProyecto
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
     * Muestra el formulario para pagar las actividades pendientes
     *
     * @param  $id  Identificador del proyecto
     * @return \Illuminate\View\View Vista para realizar el pago
     */
    public function pagarAdicional($id)
    {
        $idUsuario = session()->get('Usuario_Id');

        $notificaciones = Notificaciones::obtenerNotificaciones(
            $idUsuario
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            $idUsuario
        );

        $datos = Usuarios::findOrFail($idUsuario);
        
        $proyecto = Proyectos::obtenerProyecto($id);
        $empresaProyecto = Empresas::findOrFail($proyecto->PRY_Empresa_Id);
        $informacion = FacturaAdicional::obtenerDetalleFacturaAdicional($id);
        $idEmpresa = Empresas::obtenerEmpresa()->id;
        $empresa = Empresas::findOrFail($idEmpresa);
        $total = FacturaAdicional::obtenerTotalFacturaAdicional($id);
        
        foreach ($informacion as $info) {
            $factura = $info->id;
        }
        
        $datosU = [
            'proyecto'=>$proyecto, 
            'informacion'=>$informacion, 
            'factura'=>$factura, 
            'fecha'=>Carbon::now()->toFormattedDateString(),
            'total'=>$total,
            'empresa'=>$empresa,
            'empresaProyecto'=>$empresaProyecto
        ];

        return view(
            'cliente.pagaradicional',
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
        $idUsuario = session()->get('Usuario_Id');

        $proyecto = Proyectos::findOrFail($id);
        $actividades = Actividades::obtenerActividadesPDF($id);
        
        if (count($actividades) == 0) {
            return redirect()
                ->back()
                ->withErrors(
                    'No es posible generar el archivo sin que el proyecto tenga actividades'
                );
        }

        $id_empresa = Empresas::obtenerIdEmpresa($idUsuario);
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
        $empresaProyecto = Empresas::findOrFail($proyecto->PRY_Empresa_Id);
        $informacion = FacturasCobro::obtenerDetalleFactura($id);
        $idEmpresa = Empresas::obtenerEmpresa()->id;
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
            'empresa'=>$empresa,
            'empresaProyecto'=>$empresaProyecto
        ];
        
        $pdf = PDF::loadView(
            'includes.pdf.factura.factura',
            compact('datos')
        );

        $fileName = 'CuentaCobroINK-'.$proyecto->PRY_Nombre_Proyecto.'-'.$factura;
        
        return $pdf->download($fileName.'.pdf');
    }

    /**
     * Generar factura actividades
     *
     * @param  $id  Identificador del proyecto
     * @return PDF->download()
     */
    public function generarFacturaAdicional($id)
    {
        $proyecto = Proyectos::obtenerProyecto($id);
        $empresaProyecto = Empresas::findOrFail($proyecto->PRY_Empresa_Id);
        $informacion = FacturaAdicional::obtenerDetalleFacturaAdicional($id);
        $idEmpresa = Empresas::obtenerEmpresa()->id;
        $empresa = Empresas::findOrFail($idEmpresa);
        $total = FacturaAdicional::obtenerTotalFacturaAdicional($id);
        
        foreach ($informacion as $info) {
            $factura = $info->id;
        }
        
        $datos = [
            'proyecto'=>$proyecto, 
            'informacion'=>$informacion, 
            'factura'=>$factura, 
            'fecha'=>Carbon::now()->toFormattedDateString(),
            'total'=>$total,
            'empresa'=>$empresa,
            'empresaProyecto'=>$empresaProyecto
        ];
        
        $pdf = PDF::loadView(
            'includes.pdf.factura.facturaadicional',
            compact('datos')
        );

        $fileName = 'CuentaCobroINK-'.$proyecto->PRY_Nombre_Proyecto.'-'.$factura;
        
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
        $idEmpresa = Empresas::obtenerEmpresa()->id;
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
     * Muestra la factura y el botón para realizar el pago adicional
     *
     * @param  $id
     * @return json_encode()
     */
    public function informacionPagoAdicional($id)
    {
        $proyecto = Proyectos::obtenerProyecto($id);
        $informacion = FacturaAdicional::obtenerDetalleFacturaAdicional($id);
        $idEmpresa = Empresas::obtenerEmpresa()->id;
        $empresa = Empresas::findOrFail($idEmpresa);
        $total = FacturaAdicional::obtenerTotalFacturaAdicional($id);
        
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

        $infoPago = $this->informacionPayuAdicional($datos);

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
        if($_REQUEST) {
            session([
                'buyerEmail' => $_REQUEST['buyerEmail'],
                'merchantId' => $_REQUEST['merchantId'],
                'referenceCode' => $_REQUEST['referenceCode'],
                'TX_VALUE' => $_REQUEST['TX_VALUE'],
                'currency' => $_REQUEST['currency'],
                'transactionState' => $_REQUEST['transactionState'],
                'signature' => $_REQUEST['signature'],
                'reference_pol' => $_REQUEST['reference_pol'],
                'cus' => $_REQUEST['cus'],
                'description' => $_REQUEST['description'],
                'pseBank' => $_REQUEST['pseBank'],
                'lapPaymentMethod' => $_REQUEST['lapPaymentMethod'],
                'transactionId' => $_REQUEST['transactionId']
            ]);

            return redirect()->route('respuesta_pago_cliente');
        } else if (session('buyerEmail') == null){
            return redirect()->route('inicio_cliente')->withErrors('Error en la transacción');
        } else {
            $correo = session('buyerEmail');
            $fechaPago = Carbon::now();
            $estadoTx = $this->datosRespuesta();

            $usuario = Usuarios::where('USR_Correo_Usuario', '=', $correo)->first();

            session([
                'buyerEmail' => null,
                'merchantId' => null,
                'referenceCode' => null,
                'TX_VALUE' => null,
                'currency' => null,
                'transactionState' => null,
                'signature' => null,
                'reference_pol' => null,
                'cus' => null,
                'description' => null,
                'pseBank' => null,
                'lapPaymentMethod' => null,
                'transactionId' => null
            ]);

            if ($estadoTx == "Transacción aprobada") {
                $actividades = Actividades::obtenerActividadesPendientesPago($usuario->id);
                
                foreach ($actividades as $actividad) {
                    Actividades::actualizarEstadoPago($actividad->id, 10, $fechaPago);
                }

                return redirect()
                    ->route('inicio_cliente')
                    ->with('mensaje', 'Pago exitoso.');
            } else if ($estadoTx == "Transacción rechazada") {
                return redirect()
                    ->route('inicio_cliente')
                    ->withErrors('Transacción Rechazada.');
            } else if ($estadoTx == "Error") {
                return redirect()
                    ->route('inicio_cliente')
                    ->withErrors('Error al pagar.');
            } else if ($estadoTx == "Transacción pendiente" ) {
                $actividades = Actividades::obtenerActividadesPendientesPago($usuario->id);
                
                foreach ($actividades as $actividad) {
                    Actividades::actualizarEstadoPago($actividad->id, 14, $fechaPago);
                }

                return redirect()
                    ->route('inicio_cliente')
                    ->with('mensaje', 'Pago Pendiente.');
            } else {
                return redirect()
                    ->route('inicio_cliente')
                    ->withErrors('Otro.');
            }
        }
    }

    /**
     * Metodo que obtiene la respuesta de confirmación del pago realizado 
     * en la pasarela de pagos.
     *
     * 
     * @return redirect()->route()
     */
    public function confirmacionPago()
    {
        $ApiKey = "NDzo4w71RkoV65mpP4Fj3lI82v";
        if($_REQUEST) {
            session([
                'merchant_id' => $_REQUEST['merchant_id'],
                'state_pol' => $_REQUEST['state_pol'],
                'response_code_pol' => $_REQUEST['response_code_pol'],
                'reference_sale' => $_REQUEST['reference_sale'],
                'reference_pol' => $_REQUEST['reference_pol'],
                'sign' => $_REQUEST['sign'],
                'extra1' => $_REQUEST['extra1'],
                'payment_method' => $_REQUEST['payment_method'],
                'payment_method_type' => $_REQUEST['payment_method_type'],
                'installments_number' => $_REQUEST['installments_number'],
                'TX_VALUE' => $_REQUEST['value'],
                'New_value' => number_format($_REQUEST['value'], 1, '.', ''),
                'transaction_date' => $_REQUEST['transaction_date'],
                'currency' => $_REQUEST['currency'],
                'email_buyer' => $_REQUEST['email_buyer'],
                'cus' => $_REQUEST['cus'],
                'pse_bank' => $_REQUEST['pse_bank'],
                'test' => $_REQUEST['test'],
                'description' => $_REQUEST['description'],
                'phone' => $_REQUEST['phone']
            ]);

            return redirect()->route('confirmacion_pago_cliente');
        } else if (session('email_buyer') == null){
            return redirect()->route('inicio_cliente')->withErrors('Error en la transacción');
        } else {
            $usuario = Usuarios::where('USR_Correo_Usuario', '=', session('email_buyer'))->first();
            $actividades = Actividades::obtenerTransaccionPendiente($usuario->id);
            
            if(session('state_pol')==4) {
                foreach ($actividades as $actividad) {
                    Actividades::actualizarEstadoPago($actividad->id, 10, session('transaction_date'));
                }

                Mail::send('general.correo.respuesta', [
                    'estado' => '',
                    'nombre' => 'Ink Brutal',
                    'descripcion' => session('description'),
                    'email' => session('email_buyer'),
                    'telefono' => session('phone'),
                    'referencia' => session('reference_sale'),
                    'valor' => session('New_value')
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
            } else{
                foreach ($actividades as $actividad) {
                    Actividades::actualizarEstadoPago($actividad->id, 9, session('transaction_date'));
                }

                Mail::send('general.correo.respuesta', [
                    'estado' => 'PERO NO HA SIDO EXITOSA',
                    'nombre' => 'Ink Brutal',
                    'descripcion' => session('description'),
                    'email' => session('email_buyer'),
                    'telefono' => session('phone'),
                    'referencia' => session('reference_sale'),
                    'valor' => session('New_value')
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
    }

    /**
     * Metodo que obtiene la respuesta del pago realizado en la pasarela de pagos
     *
     * 
     * @return redirect()->route()
     */
    public function respuestaPagoAdicional()
    {
        if($_REQUEST) {
            session([
                'buyerEmail' => $_REQUEST['buyerEmail'],
                'merchantId' => $_REQUEST['merchantId'],
                'referenceCode' => $_REQUEST['referenceCode'],
                'TX_VALUE' => $_REQUEST['TX_VALUE'],
                'currency' => $_REQUEST['currency'],
                'transactionState' => $_REQUEST['transactionState'],
                'signature' => $_REQUEST['signature'],
                'reference_pol' => $_REQUEST['reference_pol'],
                'cus' => $_REQUEST['cus'],
                'description' => $_REQUEST['description'],
                'pseBank' => $_REQUEST['pseBank'],
                'lapPaymentMethod' => $_REQUEST['lapPaymentMethod'],
                'transactionId' => $_REQUEST['transactionId']
            ]);

            return redirect()->route('respuesta_pago_cliente_adicional');
        } else if (session('buyerEmail') == null){
            return redirect()->route('inicio_cliente')->withErrors('Error en la transacción');
        } else {
            $correo = session('buyerEmail');
            $fechaPago = Carbon::now();
            $estadoTx = $this->datosRespuesta();

            $usuario = Usuarios::where('USR_Correo_Usuario', '=', $correo)->first();

            $actividades = FacturaAdicional::obtenerFacturaAdicional($usuario->id);

            session([
                'buyerEmail' => null,
                'merchantId' => null,
                'referenceCode' => null,
                'TX_VALUE' => null,
                'currency' => null,
                'transactionState' => null,
                'signature' => null,
                'reference_pol' => null,
                'cus' => null,
                'description' => null,
                'pseBank' => null,
                'lapPaymentMethod' => null,
                'transactionId' => null
            ]);

            if ($estadoTx == "Transacción aprobada") {
                
                foreach ($actividades as $actividad) {
                    FacturaAdicional::actualizarFactura($actividad->id, 10);
                }

                return redirect()
                    ->route('inicio_cliente')
                    ->with('mensaje', 'Pago exitoso.');
            } else if ($estadoTx == "Transacción rechazada") {
                return redirect()
                    ->route('inicio_cliente')
                    ->withErrors('Transacción Rechazada.');
            } else if ($estadoTx == "Error") {
                return redirect()
                    ->route('inicio_cliente')
                    ->withErrors('Error al pagar.');
            } else if ($estadoTx == "Transacción pendiente" ) {
                
                foreach ($actividades as $actividad) {
                    FacturaAdicional::actualizarFactura($actividad->id, 14);
                }

                return redirect()
                    ->route('inicio_cliente')
                    ->with('mensaje', 'Pago Pendiente.');
            } else {
                return redirect()
                    ->route('inicio_cliente')
                    ->withErrors('Otro.');
            }
        }
    }

    /**
     * Metodo que obtiene la respuesta de confirmación del pago realizado 
     * en la pasarela de pagos.
     *
     * 
     * @return redirect()->route()
     */
    public function confirmacionPagoAdicional()
    {
        $ApiKey = "NDzo4w71RkoV65mpP4Fj3lI82v";
        if($_REQUEST) {
            session([
                'merchant_id' => $_REQUEST['merchant_id'],
                'state_pol' => $_REQUEST['state_pol'],
                'response_code_pol' => $_REQUEST['response_code_pol'],
                'reference_sale' => $_REQUEST['reference_sale'],
                'reference_pol' => $_REQUEST['reference_pol'],
                'sign' => $_REQUEST['sign'],
                'extra1' => $_REQUEST['extra1'],
                'payment_method' => $_REQUEST['payment_method'],
                'payment_method_type' => $_REQUEST['payment_method_type'],
                'installments_number' => $_REQUEST['installments_number'],
                'TX_VALUE' => $_REQUEST['value'],
                'New_value' => number_format($_REQUEST['value'], 1, '.', ''),
                'transaction_date' => $_REQUEST['transaction_date'],
                'currency' => $_REQUEST['currency'],
                'email_buyer' => $_REQUEST['email_buyer'],
                'cus' => $_REQUEST['cus'],
                'pse_bank' => $_REQUEST['pse_bank'],
                'test' => $_REQUEST['test'],
                'description' => $_REQUEST['description'],
                'phone' => $_REQUEST['phone']
            ]);

            return redirect()->route('confirmacion_pago_cliente_adicional');
        } else if (session('email_buyer') == null){
            return redirect()->route('inicio_cliente')->withErrors('Error en la transacción');
        } else {
            $usuario = Usuarios::where('USR_Correo_Usuario', '=', session('email_buyer'))->first();
            $actividades = FacturaAdicional::obtenerFacturaAdicionalPendiente($usuario->id);
            
            if(session('state_pol')==4) {
                foreach ($actividades as $actividad) {
                    FacturaAdicional::actualizarFactura($actividad->id, 10);
                }

                Mail::send('general.correo.respuesta', [
                    'estado' => '',
                    'nombre' => 'Ink Brutal',
                    'descripcion' => session('description'),
                    'email' => session('email_buyer'),
                    'telefono' => session('phone'),
                    'referencia' => session('reference_sale'),
                    'valor' => session('New_value')
                ], function($message){
                    $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
                    $message->to(
                        'soporte@inkdigital.co',
                        'InkBrutalPRY, Software de Gestión de Proyectos'
                    )->subject('Pago Actividad');
                });

                session([
                    'merchant_id' => null,
                    'state_pol' => null,
                    'response_code_pol' => null,
                    'reference_sale' => null,
                    'reference_pol' => null,
                    'sign' => null,
                    'extra1' => null,
                    'payment_method' => null,
                    'payment_method_type' => null,
                    'installments_number' => null,
                    'TX_VALUE' => null,
                    'New_value' => null,
                    'transaction_date' => null,
                    'currency' => null,
                    'email_buyer' => null,
                    'cus' => null,
                    'pse_bank' => null,
                    'test' => null,
                    'description' => null,
                    'phone' => null
                ]);

                return redirect()
                    ->route('inicio_cliente')
                    ->with('mensaje', 'Pago exitoso.');
            } else{
                foreach ($actividades as $actividad) {
                    FacturaAdicional::actualizarFactura($actividad->id, 9);
                }

                Mail::send('general.correo.respuesta', [
                    'estado' => 'PERO NO HA SIDO EXITOSA',
                    'nombre' => 'Ink Brutal',
                    'descripcion' => session('description'),
                    'email' => session('email_buyer'),
                    'telefono' => session('phone'),
                    'referencia' => session('reference_sale'),
                    'valor' => session('New_value')
                ], function($message){
                    $message->from('yonathan.inkdigital@gmail.com', 'InkBrutalPry');
                    $message->to(
                        'soporte@inkdigital.co',
                        'InkBrutalPRY, Software de Gestión de Proyectos'
                    )->subject('Pago Actividad');
                });

                session([
                    'merchant_id' => null,
                    'state_pol' => null,
                    'response_code_pol' => null,
                    'reference_sale' => null,
                    'reference_pol' => null,
                    'sign' => null,
                    'extra1' => null,
                    'payment_method' => null,
                    'payment_method_type' => null,
                    'installments_number' => null,
                    'TX_VALUE' => null,
                    'New_value' => null,
                    'transaction_date' => null,
                    'currency' => null,
                    'email_buyer' => null,
                    'cus' => null,
                    'pse_bank' => null,
                    'test' => null,
                    'description' => null,
                    'phone' => null
                ]);

                return redirect()
                    ->route('inicio_cliente')
                    ->withErrors('Transacción Rechazada o Expirada.');
            }
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

    /**
     * Cambia la visibilidad de la notificacion
     *
     * @param: $id Identificador del usuario autenticado
     * @return response()->json() Mensaje de exito
     * 
     */
    public function limpiarNotificacion($id)
    {
        Notificaciones::limpiar($id);
        
        return response()
            ->json(['mensaje' => 'ok']);
    }

    /**
     * Muestra una vista con el listado de todas las notificaciones
     *
     * @return \Illuminate\View\View Vista de las notificaciones
     * 
     */
    public function verTodas()
    {
        # Datos de las notificaciones y del usuario
        $notificaciones = Notificaciones::obtenerNotificaciones(
            session()->get('Usuario_Id')
        );

        $cantidad = Notificaciones::obtenerCantidadNotificaciones(
            session()->get('Usuario_Id')
        );

        $datos = Usuarios::findOrFail(session()->get('Usuario_Id'));

        $notificacionesTodas = Notificaciones::obtenerNotificacionesTodas(
            session()->get('Usuario_Id')
        );
        
        return view(
            'cliente.notificaciones.listar',
            compact(
                'datos',
                'notificaciones', 
                'cantidad',
                'notificacionesTodas'
            )
        );
    }

    #Metodo que obtiene los datos de la api de PayU
    public function informacionPayu($datos)
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now());

        //$apiKey="4Vj8eK4rloUd272L48hsrarnUA";
        $apiKey="NDzo4w71RkoV65mpP4Fj3lI82v";
        $infoPago = new stdClass();
        //$infoPago->merchantId="508029";
        $infoPago->merchantId="708186";
        $infoPago->accountId="711450";
        //$infoPago->accountId="512321";
        $infoPago->description="Cobro Proyecto ".$datos['proyecto']->PRY_Nombre_Proyecto;
        $infoPago->referenceCode="Pago".$datos['factura'].'-'.$fecha->format("U");
        $infoPago->amount=$datos['total']->Costo;
        $infoPago->tax="0";
        $infoPago->taxReturnBase="0";
        $infoPago->currency="COP";
        $infoPago->signature=md5($apiKey."~".$infoPago->merchantId."~".$infoPago->referenceCode."~".$datos['total']->Costo."~COP");
        $infoPago->test="0";
        //$infoPago->test="1";
        $infoPago->buyerFullName=$datos['proyecto']->USR_Nombres_Usuario." ".$datos['proyecto']->USR_Apellidos_Usuario;
        $infoPago->buyerEmail=$datos['proyecto']->USR_Correo_Usuario;
        $infoPago->responseUrl=route("respuesta_pago_cliente");
        $infoPago->confirmationUrl=route("confirmacion_pago_cliente");

        return $infoPago;
    }

    #Metodo que obtiene los datos de la api de PayU
    public function informacionPayuAdicional($datos)
    {
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now());

        //$apiKey="4Vj8eK4rloUd272L48hsrarnUA";
        $apiKey="NDzo4w71RkoV65mpP4Fj3lI82v";
        $infoPago = new stdClass();
        //$infoPago->merchantId="508029";
        $infoPago->merchantId="708186";
        $infoPago->accountId="711450";
        //$infoPago->accountId="512321";
        $infoPago->description="Cobro Adicional Proyecto ".$datos['proyecto']->PRY_Nombre_Proyecto;
        $infoPago->referenceCode="Pago".$datos['factura'].'-'.$fecha->format("U");
        $infoPago->amount=$datos['total']->Costo;
        $infoPago->tax="0";
        $infoPago->taxReturnBase="0";
        $infoPago->currency="COP";
        $infoPago->signature=md5($apiKey."~".$infoPago->merchantId."~".$infoPago->referenceCode."~".$datos['total']->Costo."~COP");
        //$infoPago->test="0";
        $infoPago->test="1";
        $infoPago->buyerFullName=$datos['proyecto']->USR_Nombres_Usuario." ".$datos['proyecto']->USR_Apellidos_Usuario;
        $infoPago->buyerEmail=$datos['proyecto']->USR_Correo_Usuario;
        $infoPago->responseUrl=route("respuesta_pago_cliente_adicional");
        $infoPago->confirmationUrl=route("confirmacion_pago_cliente_adicional");

        return $infoPago;
    }

    #Metodo que obtiene los datos de la página de respuesta de PayU
    public function datosRespuesta()
    {
        $ApiKey = "4Vj8eK4rloUd272L48hsrarnUA";
        $merchant_id = session('merchantId');
        $referenceCode = session('referenceCode');
        $TX_VALUE = session('TX_VALUE');
        $New_value = number_format($TX_VALUE, 1, '.', '');
        $currency = session('currency');
        $transactionState = session('transactionState');
        $firma_cadena = "$ApiKey~$merchant_id~$referenceCode~$New_value~$currency~$transactionState";
        $firmacreada = md5($firma_cadena);
        $firma = session('signature');
        $reference_pol = session('reference_pol');
        $cus = session('cus');
        $extra1 = session('description');
        $pseBank = session('pseBank');
        $lapPaymentMethod = session('lapPaymentMethod');
        $transactionId = session('transactionId');

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