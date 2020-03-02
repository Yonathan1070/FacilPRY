function detalle(button){
    var actividad = button.id;

    $.ajax({
        url: "/actividades/"+actividad+"/detalle",
        method: 'get',
        success: function(respuesta){
            var datos = JSON.parse(respuesta);
            document.getElementById('nombreActividad').value = datos.ACT_Nombre_Actividad;
            document.getElementById('descripcionActividad').value = datos.ACT_Descripcion_Actividad;
            document.getElementById('fechaInicioActividad').value = datos.ACT_Fecha_Inicio_Actividad;
            document.getElementById('fechaFinActividad').value = datos.ACT_Fecha_Fin_Actividad;
            document.getElementById('estadoActividad').value = datos.EST_Nombre_Estado;
        }
    });
}

function detalleActividad(button){
    var actividad = button.id;

    $.ajax({
        url: "/actividades/"+actividad+"/detalle",
        method: 'get',
        success: function(respuesta){
            var datos = JSON.parse(respuesta);
            document.getElementById('nombreEmpresaDetalle').value = datos.EMP_Nombre_Empresa;
            document.getElementById('nombreProyectoDetalle').value = datos.PRY_Nombre_Proyecto;
            document.getElementById('nombreRequerimientoDetalle').value = datos.REQ_Nombre_Requerimiento;
            document.getElementById('nombreActividadDetalle').value = datos.ACT_Nombre_Actividad;
            document.getElementById('descripcionActividadDetalle').value = datos.ACT_Descripcion_Actividad;
            document.getElementById('fechaInicioActividadDetalle').value = datos.ACT_Fecha_Inicio_Actividad;
            document.getElementById('fechaFinActividadDetalle').value = datos.ACT_Fecha_Fin_Actividad;
        }
    });
}