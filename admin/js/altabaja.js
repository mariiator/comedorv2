function changeAll(idAlumno, mes) {
    var dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
    var allChecked = true;
    var allUnchecked = true;
    dias.forEach(function(day) {
        var idcheckbox = idAlumno + day + mes;
        var checkbox = document.getElementById(idcheckbox);
        if (!checkbox.checked) {
            allChecked = false;
        } else {
            allUnchecked = false;
        }
    });
    if (allChecked) {
        dias.forEach(function(day) {
            var idcheckbox = idAlumno + day + mes;
            var checkbox = document.getElementById(idcheckbox);
            checkbox.click();
        });
    } else {
        dias.forEach(function(day) {
            var idcheckbox = idAlumno + day + mes;
            var checkbox = document.getElementById(idcheckbox);
            if (!checkbox.checked) {
                checkbox.click();
            }
        });
    }
}
function saveChange(sfAction,idcheckbox) {
	//alert(idcheckbox);
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
function imgError(image) {
	image.onerror = "";
	image.src = "/comedorv2/css/silueta.png";
	return true;
}