function avance(id){
    if ($("#progressBar"+id).is(':visible')) {
        $("#progressBar"+id).hide();
    } else {
        var div = document.getElementById('progressBar'+id);
        if(div != null){
            while (div.hasChildNodes()){
                div.removeChild(div.lastChild);
            }
            $.ajax({
                dataType: "json",
                method: "get",
                url: "/director/proyectos/" + id
            }).done(function (dato) {
                    var html_progress = "<div class='progress'>\
                            <div class='progress-bar bg-cyan progress-bar-striped active' role='progressbar' style='width: "+ dato.porcentaje + "%' aria-valuenow='" + dato.porcentaje + "' aria-valuemin='0' aria-valuemax='100'>\
                                "+ dato.porcentaje + "%\
                            </div>\
                        </div>";
                    $("#progressBar"+id).append(html_progress);
            });
        }
        $("#progressBar"+id).show();
    }
    
};