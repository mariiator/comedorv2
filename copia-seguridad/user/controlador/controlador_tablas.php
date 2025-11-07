<?php
require_once $_SERVER['DOCUMENT_ROOT']."/user/modelo/modelo_tablas.php";
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
//SI NO ESTA DECLARADA LA COOKIE DE ETAPA NOS REDIRIGIRA AL CONTROLADOR MENU
if(!isset($_COOKIE['etapa'])){
	header('location:/user/controlador/menu.php');
	setcookie("tabla","",-1,"/");
	exit();
}
//RECOJO EL VALOR DE LA COOKIE ETAPA PARA SABER QUE ETAPA SE HABIA PULSADO ANTES DE ACCEDER AL CONTROLADOR
$etapa = trim($_COOKIE['etapa']);
//RECOJO EL VALOR DE LA COOKIE TABLA PARA SABER A QUE TABLA HEMOS PULSADO ANTES DE ACCEDER AL CONTROLADOR
$cok = trim($_COOKIE['tabla']);
//DECLARO LA VARIABLE TABLA DONDE SERA LA TABLA QUE HAY QUE MOSTRAR
$tabla = eleccion($cok,$etapa);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	//CUANDO DAMOS AL BOTON VOLVER NOS DEVUELVE A LA PAGINA DE INICIO	
	if(isset($_POST['Volver'])){
		setcookie("tabla","",-1,"/");
		header("location:/user/controlador/controlador_asistencia.php");
		exit();
	}
}
require_once $_SERVER['DOCUMENT_ROOT']."/user/vista/vista_tablas.php";
?>
