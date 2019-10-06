<li class="dd-item dd3-item" data-id="{{$item["id"]}}">
    <div class="dd-handle dd3-handle"></div>
    <div class="dd3-content {{$item["MN_Nombre_Ruta_Menu"]=="javascript:;" ? "font-weight-bold" : ""}}">
        <a href="{{route('editar_menu', ['id'=>$item["id"]])}}">{{$item["MN_Nombre_Menu"]." | Nombre Ruta -> ". $item["MN_Nombre_Ruta_Menu"]}} | Icono -> <i style="font-size:20px;" class="material-icons">{{isset($item["MN_Icono_Menu"]) ? $item["MN_Icono_Menu"] : ""}}</i></a>
        <a href="{{route('eliminar_menu', ['id' => $item['id']])}}" class="eliminar-menu btn-accion-tabla pull-right tooltipsC" title="Eliminar del Menú">
            <i style="font-size:20px;" class="material-icons text-danger">delete_forever</i>
        </a>
    </div>
</li>