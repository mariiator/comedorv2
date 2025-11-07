document.addEventListener('DOMContentLoaded', function() {
    var inputFecha = document.getElementById('mes');
    inputFecha.addEventListener('change', function() {
        var fecha = inputFecha.value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/profesor/controlador/controlador_mes.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
				var tablas = document.querySelectorAll('#calendario');
				tablas.forEach(tabla => {
					tabla.remove();
				});
                var texto = xhr.responseText;
                var separar = texto.split('SPLIT');
                var calendario = separar[0];
                var pdf = separar[1];
				document.getElementById('contenedor').insertAdjacentHTML('afterend', calendario);
                document.getElementById('pdf').href = pdf;
                console.log(xhr.responseText);
                bordesTabla();
            } else {
                console.error('Error en la solicitud. Estado:', xhr.status);
            }
        };
        xhr.send('fecha=' + encodeURIComponent(fecha));
    });
    bordesTabla();
});

function deseleccionarRadio(obj) {
    var checkboxes = document.getElementsByName(obj.name);
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] !== obj) {
            checkboxes[i].checked = false;
        }
    }
}

function saveChange(sfAction,idcheckbox) {
    valor = document.getElementById(idcheckbox).checked;
        $.ajax({
            url: sfAction,
            data: ({
                "value" : valor,
                "id" : idcheckbox
            }),
            dataType: "html"
        })
}

function bordesTabla(){
    var table = document.getElementById("calendario");
    var lastRow = table.rows[table.rows.length - 1];
    var penultimateRow = table.rows[table.rows.length - 2];

    // Aplicar bordes redondeados al primer y último td de la última fila
    lastRow.cells[0].style.borderBottomLeftRadius = "10px";
    lastRow.cells[lastRow.cells.length - 1].style.borderBottomRightRadius = "10px";

    // Si el último tr es más corto que el penúltimo tr, aplicar bordes redondeados al último td del penúltimo tr
    if (lastRow.cells.length < penultimateRow.cells.length) {
        penultimateRow.cells[penultimateRow.cells.length - 1].style.borderBottomRightRadius = "10px";
    }
}