$(document).ready(function(){
    $('#nestable').nestable().on('change', function(){
        const data = {
            menu:window.JSON.stringify($('#nestable').nestable('serialize')),
            _token: $('input[name=_token]').val()
        };
        $.ajax({
            url:'guardar-orden',
            type:'POST',
            dataType:'JSON',
            data:data,
            success:function(respuesta){
                if (respuesta.mensaje == "ok") {
                    FacilPry.notificaciones('Menú Modificado', 'FacilPRY', 'success');
                } else{
                    FacilPry.notificaciones('No se ha podido modificar el orden del menú', 'FacilPRY', 'error');
                }
            }
        });
    });

    $('.eliminar-menu').on('click', function(event){
        event.preventDefault();
        const url = $(this).attr('href');
        swal({
            title: '¿Está seguro que desea eliminar el registro?',
            text: 'Esta acción no se puede deshacer!',
            icon: 'warning',
            buttons: {
                cancel: "Cancelar",
                confirm: "Aceptar"
            },
        }).then((value) => {
            if(value){
                window.location.href = url;
            }
        });
    })

    $('#nestable').nestable('expandAll');
});