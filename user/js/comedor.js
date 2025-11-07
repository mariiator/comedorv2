function doclick(sfAction,elemento) {
	el = $(elemento).find(".image-overlap");
	if (!el[0]){
		sfAction = sfAction.replace("registrar", "anular");
		doclick2(sfAction,elemento);
	}else {
		$(elemento).toggleClass("ausente");
		$(elemento).toggleClass("asistente");
		$("#listadoZ").append($(elemento));
		el.toggleClass("image-overlap");
		el.toggleClass("image-overlap-2");
		$(elemento).find(".image-2").append("<div class='right-overlap transparent-back'><P>ANULAR</P></div>");
		if ($(elemento).hasClass("container-2") && $(elemento).hasClass("asistente")) {
			var xhr = new XMLHttpRequest();
			xhr.open('POST', sfAction, true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

			xhr.onload = function() {
			    if (xhr.status === 200) {
			        console.log(xhr.responseText);
			    } else {
			        console.error('Error en la solicitud. Estado:', xhr.status);
			    }
			};
			xhr.send('alumno=' + encodeURIComponent(elemento));
		}
	}
}
function doclick2(sfAction,elemento) {
	el = $(elemento).find(".image-overlap-2");
	if (!el[0]) { 
		sfAction = sfAction.replace("anular", "registrar");
		doclick(sfAction,elemento);
	}else {
		var xhr = new XMLHttpRequest();
		xhr.open('POST', sfAction, true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        $(elemento).toggleClass("ausente");
		$(elemento).toggleClass("asistente");
		$("#listadoA").append($(elemento));
		el.toggleClass("image-overlap-2");
		el.toggleClass("image-overlap");
		$(elemento).find(".right-overlap").remove();

		xhr.onload = function() {
		    if (xhr.status === 200) {
		        console.log(xhr.responseText);
		    } else {
		        console.error('Error en la solicitud. Estado:', xhr.status);
		    }
		};
		xhr.send('alumno=' + encodeURIComponent(elemento));
	}
}
function clickAll() {
	$(".ausente").each(function (index) {
		$(this).click();
	});
}
function imgError(image) {
	image.onerror = "";
	image.src = "/comedorv2/css/silueta.png";
	return true;
}