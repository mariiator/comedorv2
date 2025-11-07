<?php
require_once $_SERVER['DOCUMENT_ROOT']."/cocinero/modelo/modelo_listado.php";
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
	if($usuario == "admin"){
		setcookie("etapa","",-1,"/");
		setcookie("meses","",-1,"/");
		setcookie("clase","",-1,"/");
		setcookie("configuracion","",-1,"/");
		setcookie("show","",-1,"/");
		setcookie("edit","",-1,"/");
		setcookie("listado","",-1,"/");
		setcookie("tabla","",-1,"/");
		//REDIRECCION AL CONTROLADOR DE MENU SI LA COOKIE USUARIO YA EXISTE Y SU VALOR ES ADMIN
		header("location:/admin/controlador/administracion.php");
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
$diaActual = new DateTime();
$fecha = $diaActual->format("Y-m-d");
$profesores = obtenerProfesores($fecha);
$tabla = mostrarTabla($profesores,$fecha);
//AÑADIR AQUI AL IGUAL QUE HAGO EN EL CALENDARIO LA SELECCION DE MESES
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(isset($_POST['Volver'])){ //AL PULSAR AL BOTON CERRAR NOS DEVOLVERA AL INICIO DE SESION DEL USUARIO
		header("location:/cocinero/controlador/controlador_cocinero.php");
		exit();
	}
}
require_once $_SERVER['DOCUMENT_ROOT']."/cocinero/vista/vista_listado.php";
?>