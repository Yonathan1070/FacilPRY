function icono() {
    var x = document.getElementById("MN_Icono_Menu");
    var contenido = "";
    for (var i = 0; i < x.value.length; i++) {
        if(x.value.charAt(i) == " ")
            contenido = contenido + "_";
        else
            contenido = contenido + x.value.charAt(i);
    }
    var i = document.getElementById("mostrar-icono");
    i.innerHTML = x.value.toLowerCase();
    x.value = contenido.toLowerCase();
}
function route() {
    var x = document.getElementById("MN_Nombre_Ruta_Menu");
    var contenido = "";
    for (var i = 0; i < x.value.length; i++) {
        if(x.value.charAt(i) == " ")
            contenido = contenido + "_";
        else
            contenido = contenido + x.value.charAt(i);
    }
    x.value=contenido.toLowerCase();
}