document.addEventListener('DOMContentLoaded', function() {
    var inputFecha = document.getElementById('diaSeleccionado');
    inputFecha.addEventListener('change', function() {
        var fecha = inputFecha.value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/cocinero/controlador/controlador_listadomes.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
				var tablas = document.querySelectorAll('#listado');
				tablas.forEach(tabla => {
					tabla.remove();
				});
                var texto = xhr.responseText;
				document.getElementById('contenedor').innerHTML = texto;
                console.log(xhr.responseText);
            } else {
                console.error('Error en la solicitud. Estado:', xhr.status);
            }
        };
        xhr.send('dia=' + encodeURIComponent(fecha));
    });
});