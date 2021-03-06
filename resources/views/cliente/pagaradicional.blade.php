@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Pagar
@endsection
@section('styles')
<link href="{{asset("assets/css/factura.css")}}" rel="stylesheet">
@endsection
@section("scripts")
    <script src="{{asset("assets/pages/scripts/Director/index.js")}}" type="text/javascript"></script>

    <script>
        function crearBotonPayu(){
            var id = document.getElementById("idProyecto").value;
            
            $.ajax({
                dataType: "json",
                method: "get",
                url: "/cliente/"+id+"/info-pago-adicional"
            }).done(function( infoPago ){
                var html_button = "<form method='post' action='https://checkout.payulatam.com/ppp-web-gateway-payu/'>\
                    <input name='merchantId' type='hidden' value='"+infoPago.merchantId+"'>\
                    <input name='accountId' type='hidden' value='"+infoPago.accountId+"' >\
                    <input name='description' type='hidden'  value='"+infoPago.description+"'>\
                    <input name='referenceCode' type='hidden' value='"+infoPago.referenceCode+"'>\
                    <input name='amount' type='hidden'  value='"+infoPago.amount+"'>\
                    <input name='tax' type='hidden'  value='"+infoPago.tax+"'  >\
                    <input name='taxReturnBase' type='hidden'  value='"+infoPago.taxReturnBase+"' >\
                    <input name='currency' type='hidden'  value='"+infoPago.currency+"' >\
                    <input name='signature' type='hidden'  value='"+infoPago.signature+"'  >\
                    <input name='test' type='hidden'  value='"+infoPago.test+"' >\
                    <input name='buyerFullName' type='hidden'  value='"+infoPago.buyerFullName+"' >\
                    <input name='buyerEmail' type='hidden'  value='"+infoPago.buyerEmail+"' >\
                    <input name='responseUrl' type='hidden'  value='"+infoPago.responseUrl+"' >\
                    <input name='confirmationUrl' type='hidden'  value='"+infoPago.confirmationUrl+"' >\
                    <input name='Submit' type='submit'  value='PAGAR CON PAYÚ' class='btn btn-info' >\
                </form>";

                $("#idPayuButtonContainer").append(html_button);
            });
        }
        $(document).ready(function(){
            crearBotonPayu();
        });
    </script>
@endsection
@section('contenido')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header table-resposive">
                    <header>
                        <h1>CUENTA DE COBRO</h1>
                        <address>
                            <p>{{$datosU['empresa']->EMP_Nombre_Empresa}}</p>
                            <p>Dirección: {{$datosU['empresa']->EMP_Direccion_Empresa}}<br>Correo Electrónico: {{$datosU['empresa']->EMP_Correo_Empresa}}</p>
                            <p>Telefono: {{$datosU['empresa']->EMP_Telefono_Empresa}}</p>
                            <p>NIT: {{$datosU['empresa']->EMP_NIT_Empresa}}</p>
                        </address>
                        <img height="200" src="{{asset("assets\bsb\images\Logos/".$datosU['empresa']->EMP_Logo_Empresa)}}"> 
                    </header>
                    <article>
                        <address>
                            <p style="font-size: 125%;">Datos del Cliente</p>
                                <p>
                                    Empresa: {{$datosU['empresaProyecto']->EMP_Nombre_Empresa}}<br>
                                    NIT Empresa: {{$datosU['empresaProyecto']->EMP_NIT_Empresa}}<br>
                                    Proyecto: {{$datosU['proyecto']->PRY_Nombre_Proyecto}}<br>
                                    Encargado: {{$datosU['proyecto']->USR_Nombres_Usuario.' '.$datosU['proyecto']->USR_Apellidos_Usuario}}<br>
                                    Cédula: {{$datosU['proyecto']->USR_Documento_Usuario}}<br>
                                    Correo: {{$datosU['proyecto']->USR_Correo_Usuario}}<br>
                                </p>
                        </address>
                        <table class="meta">
                            <tr>
                                <th><span>Cuenta # </span></th>
                                <td><span>INK-{{$datosU['factura']}}</span></td>
                            </tr>
                            <tr>
                                <th><span>Fecha</span></th>
                                <td><span>{{$datosU['fecha']}}</span></td>
                            </tr>
                            <tr>
                                <th><span>Monto</span></th>
                                <td><span id="prefix">$</span><span>{{$datosU['total']->Costo}}</span></td>
                            </tr>
                        </table>
                        <table class="inventory">
                            <thead>
                                <tr>
                                    <th><span>Descripción</span></th>
                                    <th><span>Fecha de adicion</span></th>
                                    <th><span>Costo</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datosU['informacion'] as $informacion)
                                    <tr>
                                        <td><span>{{$informacion->FACT_AD_Descripcion}}</span></td>
                                        <td><span>{{$informacion->FACT_AD_Fecha_Factura}}</span></td>
                                        <td><span data-prefix>$</span><span>{{$informacion->FACT_AD_Precio_Factura}}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <table class="balance">
                            <tr>
                                <th><span>Total</span></th>
                                <td><span data-prefix>$</span><span>{{$datosU['total']->Costo}}</span></td>
                            </tr>
                        </table>
                    </article>
                </div>
                <div class="body">
                    <input type="hidden" id="idProyecto" value="{{$informacion->Id_Proyecto}}">
                    <div id="idPayuButtonContainer"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection