<?php
require_once $_SERVER['DOCUMENT_ROOT']."/profesor/modelo/modelo_profesor.php";
//SI QUEREMOS UTILIZAR COOKIES, EN CASO DE QUE DEJEN DE EXISTIR LAS COOKIES NOS REEDIRIJA DE NUEVO AL INICIO DE SESION
if(!isset($_COOKIE['profesor'])){
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
	if(!isset($_COOKIE['alergias'])){
		header("location:/profesor/controlador/controlador_profesor.php");
		exit();
	}
}
$correo = $_COOKIE['profesor'];
$alergias = consultarAlergias($correo);
//SI LA PETICION DEL SERVIDOR ES POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['Volver'])){ //SI SE HA PULSADO AL BOTON DE VOLVER
		setcookie("alergias","",-1,"/");
		header("location:/profesor/controlador/controlador_profesor.php");
		exit();
    }
}
require_once $_SERVER['DOCUMENT_ROOT']."/profesor/vista/vista_alergias.php";
?>