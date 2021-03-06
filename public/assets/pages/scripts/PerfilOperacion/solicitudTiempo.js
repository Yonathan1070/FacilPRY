function tiempo(button){
    var actividad = button.id;

    $.ajax({
        url: "/perfil-operacion/actividades/"+actividad,
        method: 'get',
        success: function(respuesta){
            var datos = JSON.parse(respuesta);
            document.getElementById('nombreActividad').value = datos.ACT_Nombre_Actividad;
            document.getElementById('descripcionActividad').value = datos.ACT_Descripcion_Actividad;
            document.getElementById('fechaInicioActividad').value = datos.ACT_Fecha_Inicio_Actividad;
            document.getElementById('fechaFinActividad').value = datos.ACT_Fecha_Fin_Actividad;
            document.getElementById('formularioSolicitud').setAttribute('action', '/perfil-operacion/actividades/'+actividad+'/solicitud');
        }
    });
}