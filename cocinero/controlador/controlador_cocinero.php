<?php
//require_once $_SERVER['DOCUMENT_ROOT']."/cocinero/modelo/modelo_cocinero.php";
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
	header("location:/controlador/inicio_sesion.php");
	exit();
}else{ //SI ESTA DECLARADA LA COOKIE USUARIO HARA LO SIGUIENTE DEPENDIENDO DEL VALOR DE LA COOKIE
	$usuario = trim($_COOKIE['usuario']);
	if($usuario == "user" || $usuario == "admin"){
		setcookie("etapa","",-1,"/");
		setcookie("meses","",-1,"/");
		setcookie("clase","",-1,"/");
		setcookie("configuracion","",-1,"/");
		setcookie("show","",-1,"/");
		setcookie("edit","",-1,"/");
		setcookie("listado","",-1,"/");
		setcookie("tabla","",-1,"/");
		if($usuario == "admin"){
			//REDIRECCION AL CONTROLADOR DE ADMIN SI LA COOKIE USUARIO YA EXISTE Y SU VALOR ES ADMIN
			header("location:/admin/controlador/mejora/controlador_administracion - copia.php");
			exit();
		}elseif($usuario == "user"){
			//REDIRECCION AL CONTROLADOR DE MENU SI LA COOKIE USUARIO YA EXISTE Y SU VALOR ES USER
			header("location:/user/controlador/menu.php");
			exit();
		}
	}
}

//SI LA PETICION DEL SERVIDOR ES POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(isset($_POST['menus'])){ //SI SE HA PULSADO AL BOTON DE MENUS NOS REDIRECCIONARA AL CONTROLADOR DE MENUS
		header("location:/cocinero/controlador/controlador_menus.php");
		exit();
	}elseif(isset($_POST['listado'])){ //SI SE HA PULSADO AL BOTON DE LISTADO NOS REDIRECCIONARA AL CONTROLADOR DE LISTADO
		header("location:/cocinero/controlador/controlador_listado.php");
		exit();
	}elseif(isset($_POST['ver'])){ //SI SE HA PULSADO AL BOTON DE LISTADO NOS REDIRECCIONARA AL CONTROLADOR DE LISTADO
		header("location:/cocinero/controlador/controlador_ver_menus.php");
		exit();
	}elseif(isset($_POST['Cerrar'])){ //AL PULSAR AL BOTON CERRAR NOS DEVOLVERA AL INICIO DE SESION DEL USUARIO
		//ELIMINO LA COOKIE USUARIO
		setcookie("usuario","",-1,"/");
		header("location:/controlador/inicio_sesion.php");
		exit();
	}
}
?>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>COCINA</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
		<link href="/cocinero/css/cocinero.css" rel="stylesheet">
		<link rel="icon" href="/css/corazon_maristas.jpg" type="image/x-icon">
	</head>
<body>
    <div class="container ">
		<form action="" method="post" class="card-body">
			<div class='botones'>
				<div><input type="submit" value="Subir Menus" name="menus" class="btn btn-primary btn-lg" /></div>
				<div><input type="submit" value="Listado" name="listado" class="btn btn-primary btn-lg" /></div>
				<div><input type="submit" value="Ver Menus" name="ver" class="btn btn-primary btn-lg" /></div>
				<div><input type="submit" value="Cerrar" name="Cerrar" class="btn btn-success btn-lg" /></div>
			</div>
		</form>
	</div>
</body>
</html>