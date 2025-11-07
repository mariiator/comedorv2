<?php
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/user/modelo/modelo_menu.php";
//SI NO EXISTE LA COOKIE USUARIO NOS REDIRECCIONARA AL INICIO DE SESION Y POR SI SIGUE ALGUNA DE LAS SIGUIENTES COOKIES CREADAS NOS LAS ELIMINARA
if(!isset($_COOKIE['usuario'])){
	setcookie("etapa","",-1,"/");
	setcookie("meses","",-1,"/");
	setcookie("clase","",-1,"/");
	setcookie("configuracion","",-1,"/");
	setcookie("show","",-1,"/");
	setcookie("edit","",-1,"/");
	setcookie("listado","",-1,"/");
	setcookie("tabla","",-1,"/");
	header("location:/comedorv2/controlador/inicio_sesion.php");
	exit();
}else{ //SI ESTA DECLARADA LA COOKIE USUARIO HARA LO SIGUIENTE DEPENDIENDO DEL VALOR DE LA COOKIE
	$usuario = trim($_COOKIE['usuario']);
	if($usuario == "cocinero" || $usuario == "admin"){
		setcookie("etapa","",-1,"/");
		setcookie("meses","",-1,"/");
		setcookie("clase","",-1,"/");
		setcookie("configuracion","",-1,"/");
		setcookie("show","",-1,"/");
		setcookie("edit","",-1,"/");
		setcookie("listado","",-1,"/");
		setcookie("tabla","",-1,"/");
		if($usuario == "cocinero"){
			//REDIRECCION AL CONTROLADOR DE COCINERO SI LA COOKIE USUARIO YA EXISTE Y SU VALOR ES COCINERO
			header("location:/comedorv2/cocinero/controlador/controlador_cocinero.php");
			exit();
		}elseif($usuario == "admin"){
			//REDIRECCION AL CONTROLADOR DE ADMINISTRACION SI LA COOKIE USUARIO YA EXISTE Y SU VALOR ES ADMIN
			header("location:/comedorv2/admin/controlador/mejora/controlador_administracion - copia.php");
			exit();
		}
	}
}
$array = obtenerEtapas();
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/user/vista/vista_menu.php";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(isset($_POST['etapa'])){ //AL PULSAR AL BOTON DE ETAPA NOS REDIRIGIRA AL CONTROLADOR ASISTENCIA Y ESTE CONTROLADOR RECIBIRA LA ETAPA QUE HEMOS PULSADO CON LA COOKIE ETAPA
		$etapa = $_POST['etapa'];
		setcookie("etapa",$etapa,time() + 3600,"/");
		header("location:/comedorv2/user/controlador/controlador_asistencia.php");
		exit();
	}
	if(isset($_POST['Cerrar'])){ //AL PULSAR AL BOTON CERRAR NOS DEVOLVERA AL INICIO DE SESION DEL USUARIO
		setcookie("usuario","",-1,"/");
		header("location:/comedorv2/controlador/inicio_sesion.php");
		exit();
	}
}
?>