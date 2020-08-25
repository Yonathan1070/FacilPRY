$.get('/obtener-sesion', function (data) {
    if(data.estado) {
        return true;
    } else {
        document.location.href = "/activar-sesion";
    }
});

var IDLE_TIMEOUT = 300; //seconds
var _idleSecondsCounter = 0;
document.onclick = function() {
    _idleSecondsCounter = 0;
};
document.onmousemove = function() {
    _idleSecondsCounter = 0;
};
document.onkeypress = function() {
    _idleSecondsCounter = 0;
};
window.setInterval(CheckIdleTime, 1000);

function CheckIdleTime() {
    _idleSecondsCounter++;
    var oPanel = document.getElementById("SecondsUntilExpire");
    if (oPanel)
        oPanel.innerHTML = (IDLE_TIMEOUT - _idleSecondsCounter) + "";
    if (_idleSecondsCounter >= IDLE_TIMEOUT) {
        document.location.href = "/inactivar-sesion";
        alert("Sesion Suspendida");
    }
}
