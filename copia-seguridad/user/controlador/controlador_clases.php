<?php
require_once $_SERVER['DOCUMENT_ROOT']."/user/modelo/modelo_clases.php";
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
	setcookie("clase","",-1,"/");
	exit();
}
//RECOJO EL VALOR DE LA COOKIE ETAPA PARA SABER QUE ETAPA SE HABIA PULSADO ANTES DE ACCEDER AL CONTROLADOR
$etapa = trim($_COOKIE['etapa']);
//DECLARO UNA VARIABLE ARRAYCLASES PARA PODER OBTENER EL NOMBRE DE TODAS LAS CLASES DE ESA ETAPA
$arrayClases = obtenerClases($etapa);
//DECLARO LAS VARIABLES CLASE Y ALUMNOS
$clase;
$alumnos = array();
//SI LA PETICION DEL SERVIDOR ES POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	//CUANDO DEMOS AL BOTON VOLVER NOS DEVUELVE A LA PAGINA DE INICIO
	if(isset($_POST['clase'])){
		$clase = $_POST['clase'];
		setcookie("clase",$clase,time() + 3600,"/");
		header('location:/user/controlador/controlador_alumnos.php');
		exit();
	}elseif(isset($_POST['Volver'])){ //CUANDO DEMOS AL BOTON VOLVER NOS DEVUELVE A LA PAGINA DE INICIO
		setcookie("etapa","",-1,"/");
		header("location:/user/controlador/controlador_asistencia.php");
		exit();
	}
}
require_once $_SERVER['DOCUMENT_ROOT']."/user/vista/vista_clases.php";
?>
