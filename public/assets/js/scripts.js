$(document).ready(function(){
    $('.alert[data-auto-dismiss]').each(function(index, element){
        const $element = $(element),
            timeout = $element.data('auto-dismiss') || 5000;
        setTimeout(function(){
            $element.alert('close');
        }, timeout);
    });

    $('body').tooltip({
        trigger: 'hover',
        selector: '.tooltipsC',
        placement: 'top',
        html: true,
        container: 'body'
    });

    //Ventana de roles
    const modal = $('#modal-seleccionar-rol');
    if (modal.length && modal.data('rol-set') == 'NO') {
        modal.modal('show');
    }

    $('.asignar-rol').on('click', function(event) {
        event.preventDefault();
        var data = {
            "Rol_Id" : $(this).data('rolid'),
            "Rol_Nombre" : $(this).data('rolnombre'),
            "Sub_Rol_Id" : $(this).data('subrolid'),
            "_token" : $('input[name=_token]').val()
        };
        ajaxRequest(data, '/elegir-rol', 'asignar-rol');
    });

    function ajaxRequest(data, url, funcion) {
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function (respuesta) {
                if (funcion == 'asignar-rol' && respuesta.ruta != null) {
                    $('#modal-seleccionar-rol').hide();
                    window.location.href = respuesta.ruta;
                }
            }
        });
    }
});