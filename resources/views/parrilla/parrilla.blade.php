@extends('theme.bsb.'.strtolower(session()->get('Sub_Rol_Id')).'.layout')
@section('titulo')
Parrilla Organica
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
                        Parrilla Organica
                    </h2>
                    <ul class="header-dropdown" style="top:10px;">
                    </ul>
                </div>
                <div class="body table-responsive">
<div class="contenedor">
<div class="row header-calendar">
<div class="col" style="display: flex; justify-content: space-between; padding: 10px;">
  <h2 style="font-weight:bold;margin:10px;"><?= $array['mespanish']?> <small><?= $data['year']; ?></small></h2>
</div>

</div>
<div class="row">
<div class="col-xs-2 header-col">Lunes</div>
<div class="col-xs-2 header-col">Martes</div>
<div class="col-xs-2 header-col">Miercoles</div>
<div class="col-xs-2 header-col">Jueves</div>
<div class="col-xs-2 header-col">Viernes</div>
<div class="col-xs-2 header-col">Sabado</div>
<div class="col-xs-2 header-col">Domingo</div>
</div>
<?php $color='';?>
      <!-- inicio de semana -->
      @foreach ($data['calendar'] as $weekdata)
        <div class="row">
          <!-- ciclo de dia por semana -->
          @foreach  ($weekdata['datos'] as $dayweek)

          @if  ($dayweek['mes']==$array['mes'])
            <div class="col-xs-2 box-day">
              {{ $dayweek['dia']  }}
              <!-- evento -->
              @foreach  ($dayweek['evento'] as $event) 
               <?php if($event->PBL_Estado_Id==5)
                  $color='badge-success';
                  elseif($event->PBL_Estado_Id==6)
                  $color='badge-danger';
                  elseif($event->PBL_Estado_Id==13)
                  $color='badge-warning';
                  else $color='badge-info';
               ?>
                  <a class="badge <?=$color;?>" href="{{route('ver_publicacion',$event->id)}}">
                    {{ $event->PBL_Ubicacion }}
                  </a>
              @endforeach
            </div>
          @else
          <div class="col-xs-2 box-dayoff">
          </div>
          @endif


          @endforeach
        </div>
      @endforeach

</div>


                </div>
                </div>
            </div>
        </div>
    </div>
@endsection