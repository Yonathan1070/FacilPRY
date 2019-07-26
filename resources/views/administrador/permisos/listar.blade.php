@extends('theme.bsb.administrador.layout')
@section('titulo')
Sistema de Permisos
@endsection
@section('styles')
    <!-- JQuery Nestable Css -->
    <link href="{{asset('assets/bsb/plugins/nestable/jquery-nestable.css')}}" rel="stylesheet" />
    <style>
        .switch label .lever {
            content: "";
            display: inline-block;
            position: relative;
            width: 30px;
            height: 10px;
            background-color: #818181;
            border-radius: 15px;
            margin-right: 10px;
            transition: background 0.3s ease;
            vertical-align: middle;
            margin: 0 16px;
        }
    
        .switch label .lever:after {
            content: "";
            position: absolute;
            display: inline-block;
            width: 12px;
            height: 12px;
            background-color: #F1F1F1;
            border-radius: 21px;
            box-shadow: 0 1px 3px 1px rgba(0, 0, 0, 0.4);
            left: -5px;
            top: -1px;
            transition: left 0.3s ease, background .3s ease, box-shadow 0.1s ease;
        }
    </style>
@endsection
@section('contenido')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('includes.form-exito')
            @include('includes.form-error')
            <div class="card">
                <div class="header">
                    <h2>
                        Permisos
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                        <li class="dropdown">
                            <a class="btn btn-success waves-effect" href="{{route('crear_menu_administrador')}}"><i
                                    class="material-icons" style="color:white;">add</i> Nuevo Menú</a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    @csrf
                    <table class="table table-striped talbe-bordered table-hover" id="tabla-data">
                        <thead>
                            <tr>
                                <th>Menú</th>
                                @foreach ($rols as $id => $nombre)
                                    <th class="text-center">{{$nombre}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menus as $key => $menu)
                                @if ($menu["MN_Menu_Id"] != 0)
                                    @break
                                @endif
                                <tr>
                                    <td><i class='material-icons' style="font-size: 17px;">trending_down</i> {{$menu["MN_Nombre_Ruta_Menu"]}}</td>
                                    @foreach ($rols as $id => $nombre)
                                        <td class="text-center">
                                            <div class="switch">
                                                <label >
                                                    <input
                                                        type="checkbox" 
                                                        class="menu_rol" 
                                                        name="menu_rol[]" 
                                                        data-menuid={{$menu["id"]}}
                                                        value="{{$id}}" {{in_array($id, array_column($menusRols[$menu["id"]], "id"))? "checked" : ""}}>
                                                    <span class="lever" ></span>
                                                </label>
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <!-- Jquery Nestable -->
    <script src="{{asset('assets/bsb/plugins/nestable/jquery.nestable.js')}}"></script>
    <script src="{{asset('assets/bsb/js/pages/ui/sortable-nestable.js')}}"></script>

    <script src="{{asset("assets/pages/scripts/Administrador/menu-rol/index.js")}}" type="text/javascript"></script>
@endsection