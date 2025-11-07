<?php
//require_once $_SERVER['DOCUMENT_ROOT']."/cocinero/modelo/modelo_menus.php";
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
	header("location:/controlador/inicio_sesion.php");
	exit();
}else{ //SI ESTA DECLARADA LA COOKIE USUARIO HARA LO SIGUIENTE DEPENDIENDO DEL VALOR DE LA COOKIE
	$usuario = trim($_COOKIE['usuario']);
	if($usuario == "user" || $usuario == "admin"){
		setcookie("etapa","",-1,"/");
		setcookie("meses","",-1,"/");
		setcookie("clase","",-1,"/");
		setcookie("configuracion","",-1,"/");
		setcookie("show","",-1,"/");
		setcookie("edit","",-1,"/");
		setcookie("listado","",-1,"/");
		setcookie("tabla","",-1,"/");
		if($usuario == "admin"){
			//REDIRECCION AL CONTROLADOR DE ADMIN SI LA COOKIE USUARIO YA EXISTE Y SU VALOR ES ADMIN
			header("location:/admin/controlador/mejora/controlador_administracion - copia.php");
			exit();
		}elseif($usuario == "user"){
			//REDIRECCION AL CONTROLADOR DE MENU SI LA COOKIE USUARIO YA EXISTE Y SU VALOR ES USER
			header("location:/user/controlador/menu.php");
			exit();
		}
	}
}
$mensaje = "";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(isset($_POST['volver'])){ //AL PULSAR AL BOTON CERRAR NOS DEVOLVERA AL INICIO DE SESION DEL USUARIO
		header("location:/cocinero/controlador/controlador_cocinero.php");
		exit();
	}
}
require_once $_SERVER['DOCUMENT_ROOT']."/cocinero/vista/vista_cocinero.php";
?>