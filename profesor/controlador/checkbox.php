<?php
//SI QUEREMOS UTILIZAR COOKIES, EN CASO DE QUE DEJEN DE EXISTIR LAS COOKIES NOS REEDIRIJA DE NUEVO AL INICIO DE SESION
require_once $_SERVER['DOCUMENT_ROOT']."/profesor/modelo/modelo_profesor.php";
//DECLARO LA VARIABLE FECHA
if(isset($_GET['value']) && isset($_GET['id'])){
	$boolean = $_GET['value'];
	$letras = $_GET['id'];
	//SI EL VALOR DE BOOLEAN ES FALSE DAREMOS DE BAJA LA ASISTENCIA DE ESE MES
	if($boolean == 'false'){
		var_dump("BAJA");
		darBajaAsistencia($letras);
	}else{ //SI EL VALOR ES TRUE DAREMOS DE ALTA LA ASISTENCIA DE ESE MES
		var_dump("ALTA");
		darAltaAsistencia($letras);
	}
}
?>