<?php
require_once $_SERVER['DOCUMENT_ROOT']."/user/modelo/modelo_alumnos.php";
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
//SI NO ESTA DECLARADA LA COOKIE DE CLASES NOS REDIRIGIRA AL CONTROLADOR CLASES
if(!isset($_COOKIE['clase'])){
	header('location:/user/controlador/controlador_clases.php');
	exit();
}

//RECOGE LOS LOS VALORES QUE SE LE HAN ENVIADO Y ACTUALIZO LA BASE DE DATOS
if(isset($_POST['alumno'])){
	$nombre = $_POST['alumno'];
	//HAGO EL SUBSTR PORQUE EL AJAX LE ENVIA EL ID DEL ELEMENTO, ES DECIR, LE ENVIA '#ID', LO HAGO PARA QUITAR EL #
	$alumno = substr($nombre, 1);
	asistido($alumno);
}
?>
