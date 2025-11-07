<?php
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/user/modelo/modelo_tablas.php";
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
if(!isset($_COOKIE['etapa'])){
	header('location:/comedorv2/user/controlador/menu.php');
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
		header("location:/comedorv2/user/controlador/controlador_asistencia.php");
		exit();
	}
}
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/user/vista/vista_tablas.php";
?>
