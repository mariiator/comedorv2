<?php
require_once $_SERVER['DOCUMENT_ROOT']."/admin/modelo/modelo_edit.php";
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
$edit;
//SI ESTA DECLARADA LA COOKIE EDIT NOS HARA LO SIGUIENTE
if(isset($_COOKIE['edit'])){
	$edit = trim($_COOKIE['edit']);
	$array = obtenerConsulta($cookie,$edit);
	$tabla = mostrarTabla($cookie,$array);
}else{ //SINO ESTA DECLARADA LA COOKIE EDIT NOS MOSTRARA LA TABLA VACIA PARA PODER INTRODUCIR NUEVOS DATOS
	$tabla = mostrarTabla($cookie,array());
}

//SI LA PETICION DEL SERVIDOR ES POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	//SI DAMOS AL BOTON DE UPDATE NOS HARA LO SIGUIENTE SEGUN EL VALOR DE LA COOKIE
	if(isset($_POST['Update'])){
		$nombre = $_POST['nombre'];
		if($cookie == "Etapas"){
			$update = [$nombre];
		}elseif($cookie == "Clases"){
			$etapa = $_POST['clase'];
			$update = [$nombre,$etapa];
		}elseif($cookie == "Alumnos"){
			$apellido1 = $_POST['apellido1'];
			$apellido2 = $_POST['apellido2'];
			$clase = $_POST['clase'];
			$update = [$nombre,$apellido1,$apellido2,$clase];
		}elseif($cookie == "Profesores"){
			$apellido1 = $_POST['apellido1'];
			$apellido2 = $_POST['apellido2'];
			$correo = $_POST['correo'];
			$update = [$nombre,$apellido1,$apellido2,$correo];
		}
		sentenciaUpdate($update,$cookie,$edit);
	}elseif(isset($_POST['Delete'])){ //SI DAMOS AL BOTON DE DELETE
		sentenciaDelete($edit,$cookie);
		setcookie("edit","",-1,"/");
		header("location:/admin/controlador/controlador_config.php");
		exit();
	}elseif(isset($_POST['Volver'])){ //SI DAMOS AL BOTON DE VOLVER NOS DEVOLVERA AL CONTROLADOR CONFIG
		setcookie("edit","",-1,"/");
		header("location:/admin/controlador/controlador_config.php");
		exit();
	}elseif(isset($_POST['Crear'])){ //SI DAMOS AL BOTON DE CREAR NOS HARA LO SIGUIENTE SEGUN EL VALOR DE LA COOKIE
		$nombre = $_POST['nombre'];
		$insert;
		if($cookie == "Etapas"){
			$insert = [$nombre];
		}elseif($cookie == "Clases"){
			$etapa = $_POST['etapa'];
			$insert = [$nombre,$etapa];
		}elseif($cookie == "Alumnos"){
			$apellido1 = $_POST['apellido1'];
			$apellido2 = $_POST['apellido2'];
			$clase = $_POST['clase'];
			$insert = [$nombre,$apellido1,$apellido2,$clase];
		}elseif($cookie == "Profesores"){
			$apellido1 = $_POST['apellido1'];
			$apellido2 = $_POST['apellido2'];
			$correo = $_POST['correo'];
			$insert = [$nombre,$apellido1,$apellido2,$correo];
		}
		sentenciaInsert($cookie,$insert);
	}
}
require_once $_SERVER['DOCUMENT_ROOT']."/admin/vista/vista_edit.php";
?>