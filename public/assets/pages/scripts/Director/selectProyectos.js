$("#ClienteSelect").change(function(){
    $(".proyectos").hide();
    const myNode = document.getElementById("proyectos");
    myNode.innerHTML = '';
    var cliente = $(this).val();
    $.ajax({
        url: "/agregar/"+cliente+"/proyectos",
        method: 'get',
        success: function(respuesta){
            var proyectos = JSON.parse(respuesta);
            var html_proyectos = "<div class='form-group form-float'>\
                <div class='form-line focused'>\
                    <select name='ProyectoSelect' id='ProyectoSelect' class='form-control show-tick' data-live-search='true' required>\
                        <option value=''>-- Seleccione un Proyecto --</option>"+
                        recorrido(proyectos)+
                    "</select>\
                </div>\
            </div>";
            $(".proyectos").append(html_proyectos);
            $(".proyectos").show();
        }
    });
});

function recorrido(pry) {
    var str = "";
    var i;
    for(i=0; i<pry.length; i++){
        str += "<option value='"+pry[i].id+"'>\
            "+pry[i].PRY_Nombre_Proyecto+"\
        </option>";
    }
    return str;
}