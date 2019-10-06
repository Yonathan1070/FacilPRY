@extends('theme.bsb.administrador.layout')
@section('titulo')
Listado Menú
@endsection
@section('styles')
<!-- JQuery Nestable Css -->
<link href="{{asset('assets/bsb/plugins/nestable/jquery-nestable.css')}}" rel="stylesheet" />
@endsection
@section('contenido')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @include('includes.form-error')
            @include('includes.form-exito')
        <div class="card">
            <div class="header">
                <h2>
                    Listado de Menús
                </h2>
                <ul class="header-dropdown" style="top:10px;">
                    <li class="dropdown">
                        <a class="btn btn-success waves-effect" href="{{route('crear_menu')}}">
                            <i class="material-icons" style="color:white;">add</i> Nuevo Menú
                        </a>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="clearfix m-b-20">
                    <div class="dd nestable-with-handle">
                        @csrf
                        <div class="dd" id="nestable">
                            <ol class="dd-list">
                                @foreach ($menus as $key => $item)
                                    @if ($item['MN_Menu_Id'] != 0)
                                        @break
                                    @endif
                                    @include('administrador.menu.menu-item', ['item' => $item])
                                @endforeach
                            </ol>
                        </div>
                    </div>
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
<script src="{{asset('assets/pages/scripts/Administrador/menu/index.js')}}"></script>
@endsection