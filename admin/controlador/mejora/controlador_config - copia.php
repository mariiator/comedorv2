<?php
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/admin/modelo/modelo_config.php";
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
	if($usuario == "cocinero" || $usuario == "user"){
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
		}elseif($usuario == "user"){
			//REDIRECCION AL CONTROLADOR DE MENU SI LA COOKIE USUARIO YA EXISTE Y SU VALOR ES USER
			header("location:/comedorv2/user/controlador/menu.php");
			exit();
		}
	}
}
//ARRAY DONDE MUESTRA LOS VALORES DE LOS DROPDOWN DEL NAV
$arrayConfiguracion = array("Etapas","Clases","Alumnos","Profesores");
$dropDownListado = array("Listado Mensual","Resumen Mensual","Resumen Asistencias");
//SI NO ESTA DECLARADA LA COOKIE DE CONFIGURACION NOS DEVOLVERA AL CONTROLADOR DE CONFIGURACION
if(!isset($_COOKIE['configuracion'])){
	header("location:/comedorv2/admin/controlador/mejora/controlador_administracion - copia.php");
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
	if(isset($_POST['nuevo'])){ //SI DAMOS AL BOTON DE NUEVO NOS ENVIARA AL CONTROLADOR EDIT
		header("location:/comedorv2/admin/controlador/mejora/controlador_edit - copia.php");
		exit();
	}elseif(isset($_POST['show'])){ //SI DAMOS AL BOTON DE SHOW NOS ENVIARA AL CONTROLADOR SHOW
		$id = $_POST['show'];
		setcookie("show",$id,time() + 3600,"/");
		header("location:/comedorv2/admin/controlador/mejora/controlador_show - copia.php");
		exit();
	}elseif(isset($_POST['edit'])){ //SI DAMOS AL BOTON DE EDIT NOS ENVIARA AL CONTROLADOR EDIT
		$id = $_POST['edit'];
		setcookie("edit",$id,time() + 3600,"/");
		header("location:/comedorv2/admin/controlador/mejora/controlador_edit - copia.php");
		exit();
	}elseif(isset($_POST['configuracion']) || isset($_POST['altas/bajas']) || isset($_POST['listado']) || isset($_POST['Cerrar'])){ //SI SE HA PULSADO ALGUNO DE ESTOS BOTONES
		//ELIMINO LAS COOKIES
		setcookie("etapa","",-1,"/");
		setcookie("meses","",-1,"/");
		setcookie("clase","",-1,"/");
		setcookie("configuracion","",-1,"/");
		setcookie("show","",-1,"/");
		setcookie("edit","",-1,"/");
		setcookie("listado","",-1,"/");
		setcookie("tabla","",-1,"/");
		if(isset($_POST['configuracion'])){ //SI SE HA DADO AL BOTON DE CONFIGURACION NOS REDIRIGIRA AL CONTROLADOR DE CONFIG
			//RECOJO EL VALOR DEL BOTON PULSADO
			$valor = $_POST['configuracion'];
			//CREO LA COOKIE CONFIGURACION CON EL VALOR DEL BOTON QUE SE HA PULSADO
			setcookie("configuracion",$valor,time() + 3600,"/");
			//REDIRIJO AL CONTROLADOR CONFIG
			header("location:/comedorv2/admin/controlador/mejora/controlador_config - copia.php");
			exit();
		}elseif (isset($_POST['altas/bajas'])) { //SI SE HA DADO AL BOTON DE ALTA/BAJA NOS REDIRIGIRA AL CONTROLADOR DE ALTA/BAJA
			//REDIRIJO AL CONTROLADOR ALTA/BAJA
			header("location:/comedorv2/admin/controlador/mejora/controlador_altabaja - copia.php");
			exit();
		}elseif(isset($_POST['listado'])) { //SI SE HA DADO AL BOTON DE LISTADO NOS REDIRIGIRA AL CONTROLADOR DE LISTADO
			//RECOJO EL VALOR DEL BOTON PULSADO
			$valor = $_POST['listado'];
			setcookie("listado",$valor,time() + 3600,"/");
			//REDIRIJO AL CONTROLADOR LIST
			header("location:/comedorv2/admin/controlador/mejora/controlador_list - copia.php");
			exit();
		}elseif(isset($_POST['Cerrar'])){ //SI SE HA DADO AL BOTON DE CERRAR NOS DEVOLVERA DE NUEVO AL CONTROLADOR DE INICIO DE SESION
			//ELIMINO LA COOKIE USUARIO
			setcookie("usuario","",-1,"/");
			header("location:/comedorv2/controlador/inicio_sesion.php");
			exit();
		}
	}
}

require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/admin/vista/vista_config - copia.php";
?>