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
	
	$fecha = $dia;
	$diasEntreSemana = obtenerDiasEntreSemana($mesRecibido, $yearRecibido);
	$tabla = mostrarCalendario($diasEntreSemana,$mesRecibido,$fecha);
	$pdfAlumnos = obtenerPdf($mesRecibido,$yearRecibido,"alumno");
	$pdfProfesor = obtenerPdf($mesRecibido,$yearRecibido,"profesor");
	if(!$pdfAlumnos){
		$pdfAlumnos = "/pdf/noMenu.pdf";
	}
	if(!$pdfProfesor){
		$pdfProfesor = "/pdf/noMenu.pdf";
	}
	echo $tabla;
	echo "SPLIT".$pdfAlumnos."SPLIT".$pdfProfesor;
}
?>