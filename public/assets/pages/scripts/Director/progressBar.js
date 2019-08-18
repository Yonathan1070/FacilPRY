function mostrarBarra(obj) {
    var indicadorSeleccionado = obj.options[obj.selectedIndex].value;
    var div = document.getElementById('progressBar');
    if (div !== null) {
        while (div.hasChildNodes()) {
            div.removeChild(div.lastChild);
        }
    }
    $.ajax({
        dataType: "json",
        method: "get",
        url: "/director/decisiones/" + indicadorSeleccionado + "/total-indicador"
    }).done(function (dato) {
        var html_progress = "<div class='progress'>\
                    <div class='progress-bar bg-cyan progress-bar-striped active' role='progressbar' style='width: "+ dato.total + "%' aria-valuenow='" + dato.total + "' aria-valuemin='0' aria-valuemax='100'>\
                        "+ dato.indicador + " "+ dato.total +"%\
                    </div>\
                </div>";
        $("#progressBar").append(html_progress);
    });
}