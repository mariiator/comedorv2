<?php
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/modelo/modelo_inicio.php";
if(isset($_COOKIE['usuario'])){ //SI ESTA DECLARADA LA COOKIE DE USUARIO NOS REDIRECCIONARA SEGUN EL VALOR DE ESTA A UN SITIO U OTRO
	$usuario = trim($_COOKIE['usuario']);
	if($usuario == "admin"){
		//REDIRECCION AL CONTROLADOR DEL ADMINISTRADOR SI ESTA DECLARADA LA COOKIE DE ADMIN
		header("location:/comedorv2/admin/controlador/mejora/controlador_administracion - copia.php");
		exit();
	}elseif($usuario == "cocinero"){
		//REDIRECCION AL CONTROLADOR DEL COCINERO SI ESTA DECLARADA LA COOKIE DE COCINERO
		header("location:/comedorv2/cocinero/controlador/controlador_cocinero.php");
		exit();
	}elseif($usuario == "user"){
		//REDIRECCION AL CONTROLADOR DEL COMEDOR SI ESTA DECLARADA LA COOKIE DE USER
		header("location:/comedorv2/user/controlador/menu.php");
		exit();
	}
}elseif(isset($_COOKIE['profesor'])){ //SI ESTA DECLARADA LA COOKIE DEL PROFESOR NOS REDIRECCIONARA AL CONTROLADOR DEL PROFESOR
	header('location:/comedorv2/profesor/controlador/controlador_profesor.php');
	exit();
}
$boolean = true;
$_username = "";
$_password = "";
//SI LA PETICION DEL SERVIDOR ES POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	//RECOJO LOS VALORES DEL USUARIO Y LA PASSWORD
	$_username = strtolower($_POST['user']);
	$_password = $_POST['password'];
	if(isset($_POST['botonProfe'])){
		header('location:https://comedorprofes.maristaschamberi.com/user_portal.php');
		exit();
	}else{
		if($_username == 'comedor' && $_password == 'ch2016'){//SI SE HA INTRODUCIDO COMO USUARIO Y PASSWORD LAS SIGUIENTES NOS REDIRIGIRA AL CONTROLADOR MENU
			setcookie("usuario","user",time() + 36000,"/");
			header("location:/comedorv2/user/controlador/menu.php");
			exit();
		}elseif($_username == 'administrador' && $_password == "superclaveChamberi"){ //SI SE HA INTRODUCIDO COMO USUARIO Y PASSWORD LAS SIGUIENTES NOS REDIRIGIRA AL CONTROLADOR ADMINISTRACION
			setcookie("usuario","admin",time() + 36000,"/");
			header("location:/comedorv2/admin/controlador/mejora/controlador_administracion - copia.php");
			exit();
		}elseif($_username == 'cocinero' && $_password == "cocinaMaristasChamberi"){ //SI SE HA INTRODUCIDO COMO USUARIO Y PASSWORD LAS SIGUIENTES NOS REDIRIGIRA AL CONTROLADOR COCINERO
			setcookie("usuario","cocinero",time() + 36000,"/");
			header("location:/comedorv2/cocinero/controlador/controlador_cocinero.php");
			exit();
		}else{ //EN CASO DE QUE NO HAYAMOS INTRODUCIDO EL USUARIO DE ADMIN, COMEDOR O COCINERO, NOS HARA LO SIGUIENTE
			$boolean = false;
		}
	}
}
?>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>LOGIN COMEDOR</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
		<link href="/comedorv2/css/inicio_sesion.css" rel="stylesheet">
		<!--<link rel="stylesheet" href="/comedorv2/css/responsive_v2.css">-->
		<link rel="icon" href="/comedorv2/css/corazon_maristas.jpg" type="image/x-icon">
		<script type="text/javascript">
			//CONSTANTE DE LOS OJOS DONDE INDICA EL VALOR OPEN Y VALOR CERRADO
			const eyeIcons = {
				open: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="eye-icon"><path d="M12 15a3 3 0 100-6 3 3 0 000 6z" /><path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd" /></svg>',
				closed: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="eye-icon"><path d="M3.53 2.47a.75.75 0 00-1.06 1.06l18 18a.75.75 0 101.06-1.06l-18-18zM22.676 12.553a11.249 11.249 0 01-2.631 4.31l-3.099-3.099a5.25 5.25 0 00-6.71-6.71L7.759 4.577a11.217 11.217 0 014.242-.827c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113z" /><path d="M15.75 12c0 .18-.013.357-.037.53l-4.244-4.243A3.75 3.75 0 0115.75 12zM12.53 15.713l-4.243-4.244a3.75 3.75 0 004.243 4.243z" /><path d="M6.75 12c0-.619.107-1.213.304-1.764l-3.1-3.1a11.25 11.25 0 00-2.63 4.31c-.12.362-.12.752 0 1.114 1.489 4.467 5.704 7.69 10.675 7.69 1.5 0 2.933-.294 4.242-.827l-2.477-2.477A5.25 5.25 0 016.75 12z" /></svg>'
			};
			function addListeners() {
				const toggleButton = document.querySelector(".toggle-button");
				if (!toggleButton) {
					return;
				}
				toggleButton.addEventListener("click", togglePassword);
			}
			//FUNCION QUE NOS PERMITE VER LA CONTRASEÑA
			function togglePassword() {
				const passwordField = document.querySelector("#password-field");
				const toggleButton = document.querySelector(".toggle-button");
				if (!passwordField || !toggleButton) {
					return;
				}
				toggleButton.classList.toggle("open");
				const isEyeOpen = toggleButton.classList.contains("open");
				toggleButton.innerHTML = isEyeOpen ? eyeIcons.closed : eyeIcons.open;
				passwordField.type = isEyeOpen ? "text" : "password";
			}
			document.addEventListener("DOMContentLoaded", addListeners);
		</script>
	</head>
<body>
    <div class="container">
		<div class="row align-items-center" style="height: 100vh">
			<div class="card" id="card">
				<form action="" method="post">
					<h3 id="titulo">LOGIN COMEDOR</h3>
					<div class="form-floating mb-3">
						<input type="text" name="user" placeholder="Usuario" id="floatingInput" class="form-control <?php if(!$boolean){ echo 'is-invalid';} ?>" value="<?php if(!$boolean){ echo $_username;} ?>"/>
						<label for="floatingInput">Usuario</label>
					</div>
					<div class="form-floating mb-3">
						<input type="password" name="password" placeholder="Contraseña" id="password-field" class="form-control <?php if(!$boolean){ echo 'is-invalid';} ?>" value="<?php if(!$boolean){ echo $_password;} ?>"/>
						<label for="password-field">Contraseña</label>
						<div class="toggle-button">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="eye-icon">
								<path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
								<path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd" />
							</svg>
						</div>
					</div>
					<div id="botones">
						<input type="submit" id="botonLogin" name="submit" value="Login" class="btn btn-primary btn-lg" />
						<input type="submit" id="botonProfe" name="botonProfe" value="Profesor" class="btn btn-primary btn-lg" />
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>