$('.menu_rol').on('change', function(){
    var data = {
        MN_RL_Menu_Id: $(this).data('menuid'),
        MN_RL_Rol_Id: $(this).val(),
        _token: $('input[name=_token]').val()
    };
    if($(this).is(':checked')){
        data.estado = 1
    }else{
        data.estado = 0
    }
    ajaxRequest('/administrador/permisos/asignar-permiso', data);
});

function ajaxRequest(url, data){
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function(respuesta){
            Biblioteca.notificaciones(respuesta.respuesta, 'FACILPRY', 'success');
        }
    });
}