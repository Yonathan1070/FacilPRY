function detalle(button){
    var calificacion = button.id;

    $.ajax({
        url: "/calificaciones/"+calificacion,
        method: 'get',
        success: function(respuesta){
            var datos = JSON.parse(respuesta);
            document.getElementById('perfilOperacion').value = datos.USR_Nombres_Usuario+' '+datos.USR_Apellidos_Usuario;
            document.getElementById('decisionTomada').value = datos.DCS_Nombre_Decision;
            document.getElementById('descripcionDecision').value = datos.DCS_Descripcion_Decision;
            document.getElementById('calificacionObtenida').value = datos.CALIF_calificacion;
        }
    });
}