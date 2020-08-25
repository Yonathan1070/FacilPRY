<!-- Right Sidebar -->
<aside id="rightsidebar" class="right-sidebar">
    <ul class="nav nav-tabs tab-nav-right" role="tablist">
        <li role="presentation" class="active"><a href="#usuarios" data-toggle="tab">USUARIOS EN LINEA</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active in active" id="usuarios">
            <ul class="demo-choose-skin">
                @foreach ($sesiones as $sesion)
                    <li style="cursor: default;">
                        <div class="red" style="background-color: transparent;">
                            <img src="{{route('foto_perfil', ['id' => $sesion->id])}}" width="24" height="24" />
                        </div>
                        <span>{{$sesion->USR_Nombres_Usuario.' '.$sesion->USR_Apellidos_Usuario}}</span>
                        @if ($sesion->SES_USR_Estado_Sesion == true)
                            <span class="label label-success">-</span>
                        @else
                            <span class="label label-danger">-</span>
                        @endif
                        <br/>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</aside>
<!-- #END# Right Sidebar -->