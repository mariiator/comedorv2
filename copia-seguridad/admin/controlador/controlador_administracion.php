<?php
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
//SI LA PETICION DEL SERVIDOR ES POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	//SI SE HA DADO AL BOTON DE CONFIGURACION NOS REDIRIGIRA AL CONTROLADOR DE CONFIGURACION
	if(isset($_POST['configuracion'])){
		header("location:/admin/controlador/controlador_configuracion.php");
		exit();
	}
	elseif (isset($_POST['altas/bajas'])) { //SI SE HA DADO AL BOTON DE ALTA/BAJA NOS REDIRIGIRA AL CONTROLADOR DE ALTA/BAJA
		header("location:/admin/controlador/controlador_altabaja.php");
		exit();
	}elseif (isset($_POST['listado'])) { //SI SE HA DADO AL BOTON DE LISTADO NOS REDIRIGIRA AL CONTROLADOR DE LISTADO
		header("location:/admin/controlador/controlador_listado.php");
		exit();
	}
	if(isset($_POST['Cerrar'])){ //SI SE HA DADO AL BOTON DE CERRAR NOS DEVOLVERA DE NUEVO AL CONTROLADOR DE INICIO DE SESION
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
		<title>COMEDOR</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
		<link href="/admin/css/administracion.css" rel="stylesheet">
		<link rel="icon" href="/css/corazon_maristas.jpg" type="image/x-icon">
	</head>
<body>
    <div class="container ">
		<form action="" method="post" class="card-body">
			<div class='botones'>
				<div>
					<input type='submit' name='configuracion' value='Configuracion' class='btn btn-primary btn-lg' />
				</div>
				<div>
					<input type='submit' name='altas/bajas' value='Altas / Bajas' class='btn btn-primary btn-lg' />
				</div>
				<div>
					<input type='submit' name='listado' value='Listado' class='btn btn-primary btn-lg' />
				</div>
				<input type='submit' value='Cerrar' name='Cerrar' class='btn btn-warning btn-lg' id='center' />
			</div>
		</form>
	</div>
</body>
</html>