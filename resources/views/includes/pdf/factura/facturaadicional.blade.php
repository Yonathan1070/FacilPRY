@extends('includes.pdf.layout')
@section('titulo')
Cuenta de Cobro
@endsection
@section('styles')
    <style>
        * {
            border: 0;
            box-sizing: content-box;
            color: inherit;
            font-family: inherit;
            font-size: inherit;
            font-style: inherit;
            font-weight: inherit;
            list-style: none;
            margin: 0;
            padding: 0;
            text-decoration: none;
            vertical-align: top;
        }

        /* heading */

        h1 {
            font: bold 100% sans-serif;
            letter-spacing: 0.5em;
            text-align: center;
            text-transform: uppercase;
        }

        /* table */

        table {
            font-size: 75%;
            table-layout: fixed;
            width: 100%;
        }

        table {
            border-collapse: separate;
            border-spacing: 2px;
        }

        th,
        td {
            border-width: 1px;
            padding: 0.5em;
            position: relative;
            text-align: left;
        }

        th,
        td {
            border-radius: 0.25em;
            border-style: solid;
        }

        th {
            background: #EEE;
            border-color: #BBB;
        }

        td {
            border-color: #DDD;
        }

        /* page */

        html {
            font: 16px/1 'Open Sans', sans-serif;
            overflow: auto;
            padding: 0.5in;
        }

        /* header */

        header {
            margin: 0 0 3em;
        }

        header:after {
            clear: both;
            content: "";
            display: table;
        }

        header h1 {
            background: #000;
            border-radius: 0.25em;
            color: #FFF;
            margin: 0 0 1em;
            padding: 0.5em 0;
        }

        header address {
            float: left;
            font-size: 75%;
            font-style: normal;
            line-height: 1.25;
            margin: 0 1em 1em 0;
        }

        header address p {
            margin: 0 0 0.25em;
        }

        header span,
        header img {
            display: block;
            float: right;
        }

        header span {
            margin: 0 0 1em 1em;
            max-height: 25%;
            max-width: 60%;
            position: relative;
        }

        header img {
            max-height: 100%;
            max-width: 100%;
        }

        header input {
            cursor: pointer;
            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
            height: 100%;
            left: 0;
            opacity: 0;
            position: absolute;
            top: 0;
            width: 100%;
        }

        /* article */

        article,
        article address,
        table.meta,
        table.inventory {
            margin: 0 0 3em;
        }

        article:after {
            clear: both;
            content: "";
            display: table;
        }

        article h1 {
            clip: rect(0 0 0 0);
            position: absolute;
        }

        article address {
            float: left;
            font-size: 75%;
            font-weight: bold;
        }

        /* table meta & balance */

        table.meta,
        table.balance {
            float: right;
            width: 36%;
        }

        table.meta:after,
        table.balance:after {
            clear: both;
            content: "";
            display: table;
        }

        /* table meta */

        table.meta th {
            width: 40%;
        }

        table.meta td {
            width: 60%;
        }

        /* table items */

        table.inventory {
            clear: both;
            width: 100%;
        }

        table.inventory th {
            font-weight: bold;
            text-align: center;
        }

        table.inventory td:nth-child(1) {
            width: 26%;
        }

        table.inventory td:nth-child(2) {
            width: 38%;
        }

        table.inventory td:nth-child(3) {
            text-align: right;
            width: 12%;
        }

        table.inventory td:nth-child(4) {
            text-align: right;
            width: 12%;
        }

        table.inventory td:nth-child(5) {
            text-align: right;
            width: 12%;
        }

        /* table balance */

        table.balance th,
        table.balance td {
            width: 50%;
        }

        table.balance td {
            text-align: right;
        }
    </style>
@endsection
@section('contenido')
<!-- Multiple Items To Be Open -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="body">
                <div class="row clearfix">
                    <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                        <header>
                            <h1>CUENTA DE COBRO ADICIONAL</h1>
                            <address>
                                <p>{{$datos['empresa']->EMP_Nombre_Empresa}}</p>
                                <p>Dirección: {{$datos['empresa']->EMP_Direccion_Empresa}}<br>Correo Electrónico: {{$datos['empresa']->EMP_Correo_Empresa}}</p>
                                <p>Telefono: {{$datos['empresa']->EMP_Telefono_Empresa}}</p>
                                <p>NIT: {{$datos['empresa']->EMP_NIT_Empresa}}</p>
                            </address>
                            <img height="200" src="{{public_path("assets\bsb\images\Logos/".$datos['empresa']->EMP_Logo_Empresa)}}">
                            
                        </header>
                        <article>
                            <address>
                                <p style="font-size: 125%;">Datos del Cliente</p>
                                <p>
                                    Empresa: {{$datos['empresaProyecto']->EMP_Nombre_Empresa}}<br>
                                    NIT Empresa: {{$datos['empresaProyecto']->EMP_NIT_Empresa}}<br>
                                    Proyecto: {{$datos['proyecto']->PRY_Nombre_Proyecto}}<br>
                                    Encargado: {{$datos['proyecto']->USR_Nombres_Usuario.' '.$datos['proyecto']->USR_Apellidos_Usuario}}<br>
                                    Cedula: {{$datos['proyecto']->USR_Documento_Usuario}}<br>
                                    Correo: {{$datos['proyecto']->USR_Correo_Usuario}}<br>
                                </p>
                            </address>
                            <table class="meta">
                                <tr>
                                    <th><span>Cobro # </span></th>
                                    <td><span>INK-{{$datos['factura']}}</span></td>
                                </tr>
                                <tr>
                                    <th><span>Fecha</span></th>
                                    <td><span>{{$datos['fecha']}}</span></td>
                                </tr>
                                <tr>
                                    <th><span>Monto</span></th>
                                    <td><span id="prefix">$</span><span>{{$datos['total']->Costo}}</span></td>
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
                                    @foreach ($datos['informacion'] as $informacion)
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
                                    <td><span data-prefix>$</span><span>{{$datos['total']->Costo}}</span></td>
                                </tr>
                            </table>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    <!-- #END# Multiple Items To Be Open -->
    </div>
</div>
@endsection