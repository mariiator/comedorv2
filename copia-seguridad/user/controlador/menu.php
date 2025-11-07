<?php
require_once $_SERVER['DOCUMENT_ROOT']."/user/modelo/modelo_menu.php";
//SI QUEREMOS UTILIZAR COOKIES, EN CASO DE QUE DEJEN DE EXISTIR LAS COOKIES NOS REEDIRIJA DE NUEVO AL INICIO DE SESION
if(!isset($_COOKIE['usuario'])){
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
}else{
	$usuario = trim($_COOKIE['usuario']);
	if($usuario == "cocinero"){
		setcookie("etapa","",-1,"/");
		setcookie("meses","",-1,"/");
		setcookie("clase","",-1,"/");
		setcookie("configuracion","",-1,"/");
		setcookie("show","",-1,"/");
		setcookie("edit","",-1,"/");
		setcookie("listado","",-1,"/");
		setcookie("tabla","",-1,"/");
		//REDIRECCION AL CONTROLADOR DE COCINERO SI LA COOKIE USUARIO YA EXISTE Y SU VALOR ES COCINERO
		header("location:/cocinero/controlador/controlador_cocinero.php");
		exit();
	}elseif($usuario == "admin"){
		setcookie("etapa","",-1,"/");
		setcookie("meses","",-1,"/");
		setcookie("clase","",-1,"/");
		setcookie("configuracion","",-1,"/");
		setcookie("show","",-1,"/");
		setcookie("edit","",-1,"/");
		setcookie("listado","",-1,"/");
		setcookie("tabla","",-1,"/");
		//REDIRECCION AL CONTROLADOR DE MENU SI LA COOKIE USUARIO YA EXISTE Y SU VALOR ES ADMIN
		header("location:/admin/controlador/controlador_administracion.php");
		exit();
	}
}
$array = obtenerEtapas();
require_once $_SERVER['DOCUMENT_ROOT']."/user/vista/vista_menu.php";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(isset($_POST['etapa'])){ //AL PULSAR AL BOTON DE ETAPA NOS REDIRIGIRA AL CONTROLADOR ASISTENCIA Y ESTE CONTROLADOR RECIBIRA LA ETAPA QUE HEMOS PULSADO CON LA COOKIE ETAPA
		$etapa = $_POST['etapa'];
		setcookie("etapa",$etapa,time() + 3600,"/");
		header("location:/user/controlador/controlador_asistencia.php");
		exit();
	}
	if(isset($_POST['Cerrar'])){ //AL PULSAR AL BOTON CERRAR NOS DEVOLVERA AL INICIO DE SESION DEL USUARIO
		setcookie("usuario","",-1,"/");
		header("location:/controlador/inicio_sesion.php");
		exit();
	}
}
?>