$('.menu-usuario').on('change', function () {
    var id = document.getElementById('IdUsuario').value;
    var menuId = $(this).data('menuid');
    if($(this).is(':checked')) {
        var slug = 'agregar';
    } else {
        var slug = 'quitar';
    }
    ajaxRequest(slug, id, menuId);
});

$('.permiso-usuario').on('change', function () {
    var id = document.getElementById('IdUsuario').value;
    var menuId = $(this).data('permisoid');
    if($(this).is(':checked')) {
        var slug = 'agregarPermiso';
    } else {
        var slug = 'quitarPermiso';
    }
    ajaxRequest(slug, id, menuId);
});

$('.rol-usuario').on('change', function () {
    var id = document.getElementById('IdUsuario').value;
    var menuId = $(this).data('rolid');

    if($(this).is(':checked')) {
        var slug = 'agregarRol';
    } else {
        var slug = 'quitarRol';
    }
    ajaxRequest(slug, id, menuId);
});

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
            } else if (respuesta.mensaje == "okRA") {
                InkBrutalPRY.notificaciones('Rol Asignado.', 'InkBrutalPRY', 'success');
            } else if (respuesta.mensaje == "ngRA") {
                InkBrutalPRY.notificaciones('El Rol se encuentra asignado', 'InkBrutalPRY', 'error');
            } else if (respuesta.mensaje == "okRD") {
                InkBrutalPRY.notificaciones('Rol des-asignado.', 'InkBrutalPRY', 'success');
            } else if (respuesta.mensaje == "ngRD") {
                InkBrutalPRY.notificaciones('El Rol no se encuentra asignado', 'InkBrutalPRY', 'error');
            }
        },
        error: function () {

        }
    });
}