<?php
//SI QUEREMOS UTILIZAR COOKIES, EN CASO DE QUE DEJEN DE EXISTIR LAS COOKIES NOS REEDIRIJA DE NUEVO AL INICIO DE SESION
require_once $_SERVER['DOCUMENT_ROOT']."/profesor/modelo/modelo_profesor.php";
//DECLARO LA VARIABLE FECHA
$meses;
$diaActual = new DateTime();
$dia = $diaActual->format('Y-m-d');
$mesActual = $dia[1];
$yearActual = $dia[0];
//SI RECIBE EL VALOR DE FECHA
if(isset($_POST['fecha'])){
	//GUARDO EL VALOR DE LA FECHA
	$meses = $_POST['fecha'];
	$meses .= "-01";
	$fechaRecibida = explode("-",$meses);
	$mesRecibido = $fechaRecibida[1];
	$yearRecibido = $fechaRecibida[0];
	$correo = $_COOKIE['profesor'];
	$fecha = $dia;
	$calendario = obtenerCalendario($correo,$meses);
	$diasEntreSemana = obtenerDiasEntreSemana($mesRecibido, $yearRecibido);
	$tabla = mostrarCalendario($calendario,$diasEntreSemana,$mesRecibido,$fecha);
	$pdf = obtenerPdf($mesRecibido,$yearRecibido);
	if(!$pdf){
		$pdf = "/pdf/noMenu.pdf";
	}
	echo $tabla;
	echo "SPLIT".$pdf;
}
?>