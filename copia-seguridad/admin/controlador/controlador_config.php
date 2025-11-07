<?php
require_once $_SERVER['DOCUMENT_ROOT']."/admin/modelo/modelo_config.php";
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
//SI NO ESTA DECLARADA LA COOKIE DE CONFIGURACION NOS DEVOLVERA AL CONTROLADOR DE CONFIGURACION
if(!isset($_COOKIE['configuracion'])){
	header("location:/admin/controlador/controlador_configuracion.php");
	exit();
}
//RECOJO EL VALOR DE LA COOKIE CONFIGURACION PARA SABER QUE BOTON SE DIO ANTERIORMENTE
$cookie = $_COOKIE['configuracion'];
//DECLARO UN ARRAY QUE CONTIENE LA CONSULTA SEGUN LA COOKIE DE CONFIGURACION
$array = obtenerConsulta($cookie);
//DECLARO LA VARIABLE TABLA DONDE ESTA VA A MOSTRAR LA TABLA SEGUN LA COOKIE Y EL ARRAY
$tabla = mostrarTabla($cookie,$array);

//SI LA PETICION DEL SERVIDOR ES POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	//SI DAMOS AL BOTON DE VOLVER NOS DEVOLVERA AL CONTROLADOR DE CONFIGURACION
	if(isset($_POST['Volver'])){
		setcookie("configuracion","",-1,"/");
		header("location:/admin/controlador/controlador_configuracion.php");
		exit();
	}elseif(isset($_POST['nuevo'])){ //SI DAMOS AL BOTON DE NUEVO NOS ENVIARA AL CONTROLADOR EDIT
		header("location:/admin/controlador/controlador_edit.php");
		exit();
	}elseif(isset($_POST['show'])){ //SI DAMOS AL BOTON DE SHOW NOS ENVIARA AL CONTROLADOR SHOW
		$id = $_POST['show'];
		setcookie("show",$id,time() + 3600,"/");
		header("location:/admin/controlador/controlador_show.php");
		exit();
	}elseif(isset($_POST['edit'])){ //SI DAMOS AL BOTON DE EDIT NOS ENVIARA AL CONTROLADOR EDIT
		$id = $_POST['edit'];
		setcookie("edit",$id,time() + 3600,"/");
		header("location:/admin/controlador/controlador_edit.php");
		exit();
	}
}

require_once $_SERVER['DOCUMENT_ROOT']."/admin/vista/vista_config.php";
?>