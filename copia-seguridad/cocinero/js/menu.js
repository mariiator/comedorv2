function check(input){
    var mes = document.getElementById("mes");
    var enviar = document.getElementById("enviar");
    var dialogo = document.getElementById("dialogo");
    var allowedExtensions = /\.(pdf)$/i;
    if(typeof input != 'undefined' && input.value != ""){
        var fileName = input.files[0].name;
        if(allowedExtensions.test(fileName)){
            mes.disabled = false;
            mes.addEventListener('change', function (){
                var fecha = mes.value;
                if(fecha != ""){
                    enviar.disabled = false;
                }else{
                    enviar.disabled = true;
                    dialogo.close();
                }
            });
        }else{
            enviar.disabled = true;
            mes.disabled = true;
            dialogo.close();
        }
    }else{
        mes.value = "";
        enviar.disabled = true;
        mes.disabled = true;
        dialogo.close();
    }
}

function cerrarDialog(){
    var dialogo = document.getElementById("dialogo");
    dialogo.close();
}

function onClick(){
    var input = document.getElementById("fichero");
    var fileName = input.files[0];
    var fecha = document.getElementById("mes").value;
    var xhr = new XMLHttpRequest();
    var formData = new FormData();
    formData.append('fichero', fileName);
    formData.append('fecha', fecha);
    xhr.open('POST', '/cocinero/controlador/controlador_fichero.php', true);
    xhr.onload = function(){
        if (xhr.status === 200) {
            var contenedor = document.getElementById('contenedor');
            var hijo = document.getElementById('hijo');
            if(contenedor.contains(hijo)){
                console.log("TIENE HIJOS");
                contenedor.removeChild(hijo);
            }
            var texto = xhr.responseText;
            var elementoHijo = document.createElement('div');
            elementoHijo.id = 'hijo';
            elementoHijo.innerHTML = texto;
            contenedor.appendChild(elementoHijo);
            console.log(texto);
        } else {
            console.error('Error en la solicitud. Estado:', xhr.status);
        }
    };
    xhr.send(formData);
}

function actualizar(){
    var input = document.getElementById("fichero");
    var fileName = input.files[0];
    var fecha = document.getElementById("mes").value;
    var xhr = new XMLHttpRequest();
    var formData = new FormData();
    formData.append('fichero', fileName);
    formData.append('fecha', fecha);
    formData.append('actualizar', 'true');
    xhr.open('POST', '/cocinero/controlador/controlador_fichero.php', true);
    xhr.onload = function(){
        if (xhr.status === 200){
            var contenedor = document.getElementById('contenedor');
            var hijo = document.getElementById('hijo');
            if(contenedor.contains(hijo)){
                console.log("TIENE HIJOS");
                contenedor.removeChild(hijo);
            }
            var texto = xhr.responseText;
            var elementoHijo = document.createElement('div');
            elementoHijo.id = 'hijo';
            elementoHijo.innerHTML = texto;
            contenedor.appendChild(elementoHijo);
            input.value = '';
            check();
            console.log(texto);
        } else {
            console.error('Error en la solicitud. Estado:', xhr.status);
        }
    };
    xhr.send(formData);
}