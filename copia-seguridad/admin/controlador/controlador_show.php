<?php
require_once $_SERVER['DOCUMENT_ROOT']."/admin/modelo/modelo_show.php";
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
//RECOJO EL VALOR DE LA COOKIE CONFIGURACION PARA SABER QUE SE HABIA PULSADO ANTES DE ACCEDER AL CONTROLADOR
$cookie = trim($_COOKIE['configuracion']);
//RECOJO EL VALOR DE LA COOKIE SHOW PARA SABER DONDE SE HA PULSADO
$show = trim($_COOKIE['show']);
//DECLARO EL ARRAY DONDE CONTIENE LA CONSULTA SEGUN LA COOKIE Y SHOW
$array = obtenerConsulta($cookie,$show);
//DECLARO LA VARIABLE TABLA DONDE ESTA VA A MOSTRAR LA TABLA SEGUN LA COOKIE Y EL ARRAY
$tabla = mostrarTabla($cookie,$array);

//SI LA PETICION DEL SERVIDOR ES POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	//SI DAMOS AL BOTON EDIT NOS REDIRIGIRA AL CONTROLADOR EDIT
	if(isset($_POST['Edit'])){
		setcookie("edit",$show,time() + 3600,"/");
		setcookie("show","",-1,"/");
		header("location:/admin/controlador/controlador_edit.php");
		exit();
	}elseif(isset($_POST['Delete'])){ //SI DAMOS AL BOTON DELETE NOS DEVOLVERA AL CONTROLADOR CONFIG
		sentenciaDelete($show,$cookie);
		header("location:/admin/controlador/controlador_config.php");
		exit();
	}elseif(isset($_POST['Volver'])){ //SI DAMOS AL BOTON DE VOLVER NOS DEVOLVERA AL CONTROLADOR DE CONFIG
		setcookie("show","",-1,"/");
		header("location:/admin/controlador/controlador_config.php");
		exit();
	}
}
require_once $_SERVER['DOCUMENT_ROOT']."/admin/vista/vista_show.php";
?>