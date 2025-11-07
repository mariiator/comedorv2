<?php
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/admin/modelo/modelo_altabaja.php";
//RECOGE LOS LOS VALORES QUE SE LE HAN ENVIADO Y ACTUALIZO LA BASE DE DATOS
if(isset($_GET['value']) && isset($_GET['id'])){
	$boolean = $_GET['value'];
	$letras = $_GET['id'];
	$mes = $_COOKIE['meses'];
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