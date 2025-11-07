document.addEventListener("DOMContentLoaded", function() {
    var plato = document.getElementById('plato');
    var div = document.getElementById('contenedor');
    document.querySelectorAll('#contenedor input[type="radio"]').forEach(radio => {
        radio.addEventListener('click', function(event) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/valoracion/controlador/controlador_valoracion.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function(){
                if (xhr.status === 200) {
                    switch(plato.innerHTML){
                        case 'Primer plato':
                            plato.innerHTML = "";
                            plato.innerHTML = "Segundo plato";
                            break;
                        case 'Segundo plato':
                            plato.innerHTML = "";
                            plato.innerHTML = "Postre";
                            break;
                        case 'Postre':
                            plato.innerHTML = "";
                            plato.innerHTML = "Gracias por su valoración la tendremos en cuenta";
                            div.innerHTML = "";
                            setTimeout(recargarPagina, 3000);
                    }
                } else {
                    console.error('Error en la solicitud. Estado:', xhr.status);
                }
            };
            xhr.send('VALOR=' + this.value + "&PLATO=" + plato.innerHTML);
        });
    });

    function recargarPagina() {
        location.reload();
    }
});
