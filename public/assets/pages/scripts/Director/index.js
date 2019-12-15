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
                    InkBrutalPRY.notificaciones('El registro fue eliminado correctamente', 'InkBrutalPRY', 'success');
                } else if (respuesta.mensaje == "rd") {
                    InkBrutalPRY.notificaciones('El rol es por defecto del sistema, no es posible eliminarlo.', 'InkBrutalPRY', 'error');
                } else if (respuesta.mensaje == "np") {
                    InkBrutalPRY.notificaciones('No tiene permisos para entrar en este modulo.', 'InkBrutalPRY', 'error');
                } else {
                    InkBrutalPRY.notificaciones('El registro no pudo ser eliminado o hay otro recurso usándolo', 'InkBrutalPRY', 'error');
                }
            },
            error: function () {

            }
        });
    }
});