<?php
require_once $_SERVER['DOCUMENT_ROOT']."/admin/modelo/modelo_altabaja.php";
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
	}elseif($usuario == "user"){
		setcookie("etapa","",-1,"/");
		setcookie("meses","",-1,"/");
		setcookie("clase","",-1,"/");
		setcookie("configuracion","",-1,"/");
		setcookie("show","",-1,"/");
		setcookie("edit","",-1,"/");
		setcookie("listado","",-1,"/");
		setcookie("tabla","",-1,"/");
		//REDIRECCION AL CONTROLADOR DE MENU SI LA COOKIE USUARIO YA EXISTE Y SU VALOR ES USER
		header("location:/user/controlador/menu.php");
		exit();
	}
}
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