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
//DECLARO UN ARRAY CON LOS NOMBRES DE LOS BOTONES
$array = array("Listado Mensual","Resumen Mensual","Resumen Asistencias");
//SI LA PETICION DEL SERVIDOR ES POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(isset($_POST['admin'])){ //SI SE HA PULSADO ALGUN BOTON CON EL NAME ADMIN
		$valor = $_POST['admin'];
		//CREO LA COOKIE LISTADO DONDE GUARDO EL VALOR DEL BOTON QUE SE HA PULSADO
		setcookie("listado",$valor,time() + 3600,"/");
		//REDIRIJO AL CONTROLADOR LIST
		header("location:/admin/controlador/controlador_list.php");
		exit();
	}elseif(isset($_POST['Volver'])){ //SI SE HA PULSADO AL BOTON DE VOLVER
		header("location:/admin/controlador/controlador_administracion.php");
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
				<?php
					if(!empty($array)){
						foreach ($array as $valor) {
							echo "<div><input type='submit' name='admin' value=\"$valor\" class='btn btn-primary btn-lg' /></div>";
						}
					}
				?>
			</div>
			<input type="submit" value="Volver" name="Volver" class="btn btn-warning btn-lg" id="center" />
		</form>
	</div>
</body>
</html>