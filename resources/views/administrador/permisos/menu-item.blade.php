@if ($item["submenu"]==[])
<li class="dd-item dd3-item" data-id="{{$item["id"]}}">
    <div class="dd-handle dd3-handle"></div>
    <div class="dd3-content {{$item["MN_Nombre_Ruta_Menu"]=="javascript:;" ? "font-weight-bold" : ""}}">
        <a href="{{route('editar_permiso_administrador', ['id' => $item["id"]])}}">{{$item["MN_Mombre_Menu"]." | Nombre Ruta -> ". $item["MN_Nombre_Ruta_Menu"]}} Icono -> <i class='material-icons' style="font-size: 17px;">{{$item["MN_Icono_Menu"]}}</i></a>
    </div>
</li>
@else
<li class="dd-item dd3-item" data-id="{{$item["id"]}}">
    <div class="dd-handle dd3-handle"></div>
    <div class="dd3-content {{$item["MN_Nombre_Ruta_Menu"] == "javascript:;" ? "font-weight-bold" : ""}}">
        <a href="{{route('editar_permiso_administrador', ['id' => $item["id"]])}}">{{$item["MN_Mombre_Menu"]." | Nombre Ruta -> " . $item["MN_Nombre_Ruta_Menu"]}} Icono -> <i class='material-icons' style="font-size: 17px;">{{$item["MN_Icono_Menu"]}}</i></a>
    </div>
    <ol class="dd-list">
        @foreach ($item["submenu"] as $submenu)
        @include("administrador.menu.menu-item", ["item"=>$submenu])
        @endforeach
    </ol>
</li>
@endif