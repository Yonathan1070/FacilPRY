function ajax(slug, id, menuId) {
    ajaxRequest(slug, id, menuId);
}
function ajaxRequest(slug, id, menuId) {
    $.ajax({
        url: '/administrador/asignar-permiso/'+id+'-'+menuId+'/'+slug,
        type: 'GET',
        success: function (respuesta) {
            if (respuesta.mensaje == "okMA") {
                InkBrutalPRY.notificaciones('Menú Asignado', 'InkBrutalPRY', 'success');
            } else if (respuesta.mensaje == "ngMA") {
                InkBrutalPRY.notificaciones('El menú se encuentra asignado', 'InkBrutalPRY', 'error');
            }else if (respuesta.mensaje == "okMD") {
                InkBrutalPRY.notificaciones('Menú des-asignado', 'InkBrutalPRY', 'success');
            }else if (respuesta.mensaje == "ngMD") {
                InkBrutalPRY.notificaciones('El menú no se encuentra asignado', 'InkBrutalPRY', 'error');
            } else if (respuesta.mensaje == "okPA") {
                InkBrutalPRY.notificaciones('Permiso Asignado.', 'InkBrutalPRY', 'success');
            } else if (respuesta.mensaje == "ngPA") {
                InkBrutalPRY.notificaciones('El Permiso se encuentra asignado', 'InkBrutalPRY', 'error');
            } else if (respuesta.mensaje == "okPD") {
                InkBrutalPRY.notificaciones('Permiso des-asignado.', 'InkBrutalPRY', 'success');
            } else if (respuesta.mensaje == "ngPD") {
                InkBrutalPRY.notificaciones('El Permiso no se encuentra asignado', 'InkBrutalPRY', 'error');
            }
        },
        error: function () {

        }
    });
}