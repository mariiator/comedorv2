document.addEventListener('DOMContentLoaded', function() {
    var inputFecha = document.getElementById('mes');

    inputFecha.addEventListener('change', function() {
        var fecha = inputFecha.value;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/admin/controlador/controlador_mes.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (xhr.status === 200) {
				var tablas = document.querySelectorAll('#tablaClases');
				tablas.forEach(tabla => {
					tabla.remove();
				});
				let cookie = getCookie("listado");
				if(cookie == "Listado Mensual"){
               		document.getElementById('clases').insertAdjacentHTML('afterend', xhr.responseText);
               	}else if(cookie == "Resumen Mensual" || cookie == "Resumen Asistencias"){
                	document.getElementById('tablas').insertAdjacentHTML('afterend', xhr.responseText);
                }
                console.log(xhr.responseText);
            } else {
                console.error('Error en la solicitud. Estado:', xhr.status);
            }
        };
        xhr.send('fecha=' + encodeURIComponent(fecha));
    });
});

function getCookie(cname) {
	let name = cname + "=";
	let decodedCookie = decodeURIComponent(document.cookie);
	let ca = decodedCookie.split(';');
	for(let i = 0; i <ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}