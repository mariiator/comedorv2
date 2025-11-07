<?php
require_once $_SERVER['DOCUMENT_ROOT']."/profesor/modelo/modelo_profesor.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/phpCAS-1.3.6/source/CAS.php';
//SI QUEREMOS UTILIZAR COOKIES, EN CASO DE QUE DEJEN DE EXISTIR LAS COOKIES NOS REEDIRIJA DE NUEVO AL INICIO DE SESION
if(!isset($_COOKIE['profesor'])){
	setcookie("etapa","",-1,"/");
	setcookie("meses","",-1,"/");
	setcookie("clase","",-1,"/");
	setcookie("configuracion","",-1,"/");
	setcookie("show","",-1,"/");
	setcookie("edit","",-1,"/");
	setcookie("listado","",-1,"/");
	setcookie("tabla","",-1,"/");
	header("location:/controlador/inicio_sesion.php");
	exit();
}
//DECLARO LA VARIABLE MESES
$meses;
$fecha;
//SI SE HA DADO AL BOTON DE MES RECOGERA EL VALOR DEL MES Y ACTUALIZARA LA FECHA
if(isset($_POST['mes'])){
    $meses = new DateTime($_POST['mes']);
    $fecha = $meses->format('Y-m-d');
}else{ //SI NO SE HA DADO AL BOTON DE MES SE CREARA LA FECHA CON LOS VALORES DEL DIA ACTUAL
    $meses = new DateTime();
    $fecha = $meses->format('Y-m-d');
}
$correo = $_COOKIE['profesor'];
$mesActual = new DateTime();
$fechaActual = $mesActual->format('Y-m-d');
$arrayDia = explode("-", $fecha);
$year = $arrayDia[0];
$month = $arrayDia[1];
$diasEntreSemana = obtenerDiasEntreSemana($month, $year);
$tabla = mostrarCalendario($diasEntreSemana,$month,$fechaActual);
$pdfAlumnos = obtenerPdf($month,$year,"alumno");
$pdfProfesor = obtenerPdf($month,$year,"profesor");
//SI LA PETICION DEL SERVIDOR ES POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['siguiente'])){ //SI SE HA DADO AL BOTON DE SIGUIENTE
    	//MODIFICAMOS EL VALOR DE MESES Y LE AÑADIMOS UN MES MAS
        $meses->modify('+1 month');
        $fecha = $meses->format('Y-m-d');
        $arrayDia = explode("-", $fecha);
		$year = $arrayDia[0];
		$month = $arrayDia[1];
		$pdfAlumnos = obtenerPdf($month,$year,"alumno");
		$pdfProfesor = obtenerPdf($month,$year,"profesor");
        $diasEntreSemana = obtenerDiasEntreSemana($month, $year);
		$tabla = mostrarCalendario($diasEntreSemana,$month,$fechaActual);
		if(!$pdfAlumnos){
			$pdfAlumnos = "/pdf/noMenu.pdf";
		}
		if(!$pdfProfesor){
			$pdfProfesor = "/pdf/noMenu.pdf";
		}
    }elseif(isset($_POST['anterior'])){ //SI SE HA DADO AL BOTON DE ANTERIOR
    	//MODIFICAMOS EL VALOR DE MESES Y LE RESTAREMOS UN MES
        $meses->modify('-1 month');
        $fecha = $meses->format('Y-m-d');
        $arrayDia = explode("-", $fecha);
		$year = $arrayDia[0];
		$month = $arrayDia[1];
        $diasEntreSemana = obtenerDiasEntreSemana($month, $year);
		$tabla = mostrarCalendario($diasEntreSemana,$month,$fechaActual);
		$pdfAlumnos = obtenerPdf($month,$year,"alumno");
		$pdfProfesor = obtenerPdf($month,$year,"profesor");
		if(!$pdfAlumnos){
			$pdfAlumnos = "/pdf/noMenu.pdf";
		}
		if(!$pdfProfesor){
			$pdfProfesor = "/pdf/noMenu.pdf";
		}
    }elseif(isset($_POST['logout'])){ //SI SE HA PULSADO AL BOTON DE CERRAR
    	setcookie("profesor","",-1,"/");
    	phpCAS::client(CAS_VERSION_2_0,'login01.globaleduca.es',443,'/mi010');
		phpCAS::logout();
    }elseif(isset($_POST['alergias'])){ //SI SE HA PULSADO AL BOTON DE ALERGIAS
    	setcookie("alergias","1",time() + 36000, "/");
		header("location:/profesor/controlador/controlador_alergias.php");
		exit();
    }
}
require_once $_SERVER['DOCUMENT_ROOT']."/profesor/vista/vista_profesor.php";
?>