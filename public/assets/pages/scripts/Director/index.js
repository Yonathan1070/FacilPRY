$(document).ready(function () {
    $("#tabla-data").on('submit', '.form-eliminar', function () {
        event.preventDefault();
        const form = $(this);
        swal({
            title: '¿Está seguro que desea eliminar el registro?',
            text: 'Esta acción no se puede deshacer!',
            icon: 'warning',
            buttons: {
                cancel: "Cancelar",
                confirm: "Aceptar"
            },
        }).then((value) => {
            if (value) {
                ajaxRequest(form);
            }
        });
    });

    function ajaxRequest(form) {
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function (respuesta) {
                if (respuesta.mensaje == "ok") {
                    location.reload();
                    FacilPry.notificaciones('El registro fue eliminado correctamente', 'FacilPRY', 'success');
                } else if (respuesta.mensaje == "rd") {
                    FacilPry.notificaciones('El rol es por defecto del sistema, no es posible eliminarlo.', 'FacilPRY', 'error');
                } else if (respuesta.mensaje == "np") {
                    FacilPry.notificaciones('No tiene permisos para entrar en este modulo.', 'FacilPRY', 'error');
                } else {
                    FacilPry.notificaciones('El registro no pudo ser eliminado o hay otro recurso usándolo', 'FacilPRY', 'error');
                }
            },
            error: function () {

            }
        });
    }
});