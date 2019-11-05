function ajax(slug, id, menuId) {
    ajaxRequest(slug, id, menuId);
}
function ajaxRequest(slug, id, menuId) {
    $.ajax({
        url: '/administrador/asignar-permiso/'+id+'-'+menuId+'/'+slug,
        type: 'GET',
        success: function (respuesta) {
            if (respuesta.mensaje == "okMA") {
                FacilPry.notificaciones('Menú Asignado', 'FacilPRY', 'success');
            } else if (respuesta.mensaje == "ngMA") {
                FacilPry.notificaciones('El menú se encuentra asignado', 'FacilPRY', 'error');
            }else if (respuesta.mensaje == "okMD") {
                FacilPry.notificaciones('Menú des-asignado', 'FacilPRY', 'success');
            }else if (respuesta.mensaje == "ngMD") {
                FacilPry.notificaciones('El menú no se encuentra asignado', 'FacilPRY', 'error');
            } else if (respuesta.mensaje == "okPA") {
                FacilPry.notificaciones('Permiso Asignado.', 'FacilPRY', 'success');
            } else if (respuesta.mensaje == "ngPA") {
                FacilPry.notificaciones('El Permiso se encuentra asignado', 'FacilPRY', 'error');
            } else if (respuesta.mensaje == "okPD") {
                FacilPry.notificaciones('Permiso des-asignado.', 'FacilPRY', 'success');
            } else if (respuesta.mensaje == "ngPD") {
                FacilPry.notificaciones('El Permiso no se encuentra asignado', 'FacilPRY', 'error');
            }
        },
        error: function () {

        }
    });
}