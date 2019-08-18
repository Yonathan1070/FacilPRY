function avance(id){
    $('.progress').remove();
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
        $("#progressBar").append(html_progress);
    });
    $('.danger').popover({ 
        html : true,
        content: function() {
        return $('#popover_content_wrapper').html();
        }
    });
};