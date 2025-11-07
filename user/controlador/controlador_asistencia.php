<?php
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/user/modelo/modelo_asistencia.php";
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
	exit();
}
//RECOJO EL VALOR DE LA COOKIE ETAPA PARA SABER QUE ETAPA SE HABIA PULSADO ANTES DE ACCEDER AL CONTROLADOR
$etapa = trim($_COOKIE['etapa']);
//DECLARO UNA VARIABLE DONDE LE DOY EL VALOR DEL TOTAL DE PERSONAS QUE TIENEN QUE ASISTIR EL DIA DE HOY SEGUN LA ETAPA
$asistir = obtenerAsistencias($etapa);
//DECLARO UNA VARIABLE DONDE LE DOY EL VALOR DEL TOTAL DE PERSONAS QUE HAN ASISTIDO EL DIA DE HOY SEGUN LA ETAPA
$asistido = obtenerTotalPersonas($etapa);
//OBTENGO EL TOTAL DE PERSONAS QUE FALTAN POR ASISTIR RESTANDO EL TOTAL DE PERSONAS QUE TIENEN QUE ASISTIR EL DIA DE HOY MENOS EL TOTAL DE PERSONAS QUE HAN ASISTIDO EL DIA DE HOY
$faltan = $asistir - $asistido;
//SI LA PETICION DEL SERVIDOR ES POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	//CUANDO DEMOS AL BOTON VOLVER NOS DEVUELVE A LA PAGINA DE INICIO
	if(isset($_POST['Volver'])){
		header("location:/comedorv2/user/controlador/menu.php");
		setcookie("etapa","",-1,"/");
		exit();
	}elseif(isset($_POST['asistir'])){ //BOTON/LINK DONDE NOS REDIRIGE AL CONTROLADOR PARA VER LAS TABLAS DEL TOTAL DE PERSONAS QUE TIENEN QUE ASISTIR AL DIA DE HOY
		setcookie("tabla","asistir",time() + 3600,"/");
		header('location:/comedorv2/user/controlador/controlador_tablas.php');
		exit();
	}elseif(isset($_POST['asistido'])){//BOTON/LINK DONDE NOS REDIRIGE AL CONTROLADOR PARA VER LAS TABLAS DEL TOTAL DE PERSONAS QUE HAN ASISTIDO AL DIA DE HOY
		setcookie("tabla","asistido",time() + 3600,"/");
		header('location:/comedorv2/user/controlador/controlador_tablas.php');
		exit();
	}elseif(isset($_POST['faltan'])){ //BOTON/LINK DONDE NOS REDIRIGE AL CONTROLADOR PARA VER LAS TABLAS DEL TOTAL DE PERSONAS QUE FALTAN POR ASISTIR AL DIA DE HOY
		setcookie("tabla","faltan",time() + 3600,"/");
		header('location:/comedorv2/user/controlador/controlador_tablas.php');
		exit();
	}elseif(isset($_POST['Lista'])){ //BOTON DONDE NOS REDIRIGE AL CONTROLADOR PARA PASAR LA LISTA DE LAS PERSONAS
		header('location:/comedorv2/user/controlador/controlador_clases.php');
		exit();
	}elseif(isset($_POST['Atras'])){ //BOTON DONDE NOS DEVUELVE AL CONTROLADOR DONDE SALEN LAS ETAPAS
		setcookie("etapa","",-1,"/");
		header('location:/comedorv2/user/controlador/menu.php');
		exit();
	}
}
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/user/vista/vista_asistencias.php";
?>
