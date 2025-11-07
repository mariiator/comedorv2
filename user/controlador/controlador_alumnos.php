<?php
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/user/modelo/modelo_alumnos.php";
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
//SI NO ESTA DECLARADA LA COOKIE DE ETAPA NOS REDIRIGIRA AL CONTROLADOR MENU
if(!isset($_COOKIE['clase'])){
	header('location:/comedorv2/user/controlador/controlador_clases.php');
	exit();
}
$clase = trim($_COOKIE['clase']);
$tabla = mostrarTablas($clase);
//SI LA PETICION DEL SERVIDOR ES POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	//CUANDO DEMOS AL BOTON VOLVER NOS DEVUELVE A LA PAGINA DE INICIO
	if(isset($_POST['Volver'])){ //CUANDO DEMOS AL BOTON VOLVER NOS DEVUELVE A LA PAGINA DE INICIO
		setcookie("clase","",-1,"/");
		header("location:/comedorv2/user/controlador/controlador_clases.php");
		exit();
	}elseif (isset($_POST['Principal'])) { //CUANDO DEMOS AL BOTON PRINCIPAL NOS DEVUELVE AL CONTROLADOR ASISTENCIA (LA PAGINA ANTERIOR)
		setcookie("clase","",-1,"/");
		header('location:/comedorv2/user/controlador/menu.php');
		exit();
	}
}

require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/user/vista/vista_alumnos.php";
?>
