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
//SI NO ESTA DECLARADA LA COOKIE DE ETAPA NOS REDIRIGIRA AL CONTROLADOR MENU
if(!isset($_COOKIE['clase'])){
	header('location:/user/controlador/controlador_clases.php');
	exit();
}
$clase = trim($_COOKIE['clase']);
$tabla = mostrarTablas($clase);
//SI LA PETICION DEL SERVIDOR ES POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	//CUANDO DEMOS AL BOTON VOLVER NOS DEVUELVE A LA PAGINA DE INICIO
	if(isset($_POST['Volver'])){ //CUANDO DEMOS AL BOTON VOLVER NOS DEVUELVE A LA PAGINA DE INICIO
		setcookie("clase","",-1,"/");
		header("location:/user/controlador/controlador_clases.php");
		exit();
	}elseif (isset($_POST['Principal'])) { //CUANDO DEMOS AL BOTON PRINCIPAL NOS DEVUELVE AL CONTROLADOR ASISTENCIA (LA PAGINA ANTERIOR)
		setcookie("clase","",-1,"/");
		header('location:/user/controlador/menu.php');
		exit();
	}
}

require_once $_SERVER['DOCUMENT_ROOT']."/user/vista/vista_alumnos.php";
?>
