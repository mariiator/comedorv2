<?php
require_once $_SERVER['DOCUMENT_ROOT']."/admin/modelo/modelo_altabaja.php";
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
//DECLARO UN ARRAY CON LOS NOMBRES DE LOS MESES EN ORDEN PARA QUE LUEGO SE VEA EN LA VISTA
$nombreMeses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
//OBTENGO LA FECHA ACTUAL
$meses = new DateTime();
//DECLARO EL MES ACTUAL
$mesActual = $meses->format('m');
//AÑADO UN MES A LA FECHA ACTUAL
$meses->modify('+1 month');
//DECLARO EL MES SIGUIENTE
$sigMes = $meses->format('m');
//ARRAY DONDE GUARDO LOS NOMBRES DEL MES ACTUAL Y EL SIGUIENTE MES
$array = [$nombreMeses[$mesActual-1], $nombreMeses[$sigMes-1]];
$etapas;
$mesElegido = 0;
$boton = "";

//SI ESTA DECLARADO LA COOKIE MESES
if(isset($_COOKIE['meses'])){
	//OBTENGO EL VALOR DE LA COOKIE MESES
	$cookieMes = $_COOKIE['meses'];
	//OBTENGO SI HAY DATOS EN LA BASE DE DATOS EN ESE MES
	$booleanMes = obtenerMes($cookieMes);
	//SI EL VALOR DE BOOLEAN ES TRUE TENDREMOS QUE MOSTRAR LAS ETAPAS
	if($booleanMes == true){
		//OBTENGO LAS ETAPAS
		$etapas = obtenerEtapas();
		if(!isset($_COOKIE['resumen']) && isset($_COOKIE['etapa'])){
			$boton = "Resumen";
			//OBTENGO EL VALOR DE LA COOKIE ETAPA
			$cookieEtapa = $_COOKIE['etapa'];
			//OBTENGO LAS CLASES DE ESA ETAPA
			$clases = obtenerClases($cookieEtapa);
			//SI ESTA DECLARADO LA COOKIE CLASE
			if(isset($_COOKIE['clase'])){
				//OBTENGO EL VALOR DE LA COOKIE CLASE
				$cookieClase = $_COOKIE['clase'];
				//OBTENGO LOS ALUMNOS DE ESA CLASE
				$alumnos = obtenerAlumnos($cookieClase,$cookieMes);
				//OBTENGO EL VALOR DE LA TABLA
				$tabla = mostrarTabla($alumnos,$cookieMes);
			}
		}elseif(isset($_COOKIE['resumen']) && isset($_COOKIE['clase']) && isset($_COOKIE['etapa'])){
			$valor = "Alumnos";
			$etapas = obtenerEtapas();
			$cookieEtapa = $_COOKIE['etapa'];
			$clases = obtenerClases($cookieEtapa);
			$cookieClase = $_COOKIE['clase'];
			$cookieMes = $_COOKIE['meses'];
			$alumnos = obtenerAlumnos($cookieClase,$cookieMes);
			$tabla = mostrarTablaAsistencias($alumnos);
		}
	}else{ //SI EL VALOR DE BOOLEAN ES FALSE ENVIAREMOS EL ARRAY DE ETAPAS VACIO PARA QUE NO MUESTRE ESTAS ETAPAS
		//MOSTRAR CUANDO NO HAY DATOS DE ESE MES
		$etapas = array();
	}
}
//SI LA PETICION DEL SERVIDOR ES POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(isset($_POST['Volver'])){ //SI SE HA DADO AL BOTON DE VOLVER
		setcookie("meses","",-1,"/");
		setcookie("etapa","",-1,"/");
		setcookie("clase","",-1,"/");
		setcookie("resumen","",-1,"/");
		header("location:/admin/controlador/controlador_administracion.php");
		exit();
	}elseif(isset($_POST['mes'])){ //SI SE HA DADO AL BOTON MES
		$mes = $_POST['mes'];
		$key = array_search($mes, $nombreMeses);
		$mesElegido = $key + 1;
		//CREO LA COOKIE MESES CON EL MES QUE HEMOS PULSADO
		setcookie("meses",$mesElegido,time() + 3600,"/");
		if(obtenerMes($mesElegido)){ //SI EL MES QUE HEMOS PULSADO DEVUELVE TRUE
			$etapas = obtenerEtapas();
		}else{ //SI EL MES QUE HEMOS PULSADO DEVUELVE FALSE
			$etapas = array();
		}
		if(isset($_COOKIE['etapa']) && !isset($_COOKIE['resumen'])){
			//OBTENGO EL VALOR DE LA COOKIE ETAPA
			$cookieEtapa = $_COOKIE['etapa'];
			//OBTENGO LAS CLASES DE ESA ETAPA
			$clases = obtenerClases($cookieEtapa);
			//SI ESTA DECLARADO LA COOKIE CLASE
			if(isset($_COOKIE['clase'])){
				//OBTENGO EL VALOR DE LA COOKIE CLASE
				$cookieClase = $_COOKIE['clase'];
				//OBTENGO LOS ALUMNOS DE ESA CLASE
				$alumnos = obtenerAlumnos($cookieClase,$mesElegido);
				//OBTENGO EL VALOR DE LA TABLA
				$tabla = mostrarTabla($alumnos,$mesElegido);
			}
		}elseif(isset($_COOKIE['etapa']) && isset($_COOKIE['resumen'])){
			//OBTENGO EL VALOR DE LA COOKIE ETAPA
			$cookieEtapa = $_COOKIE['etapa'];
			//OBTENGO LAS CLASES DE ESA ETAPA
			$clases = obtenerClases($cookieEtapa);
			//SI ESTA DECLARADO LA COOKIE CLASE
			if(isset($_COOKIE['clase'])){
				//OBTENGO EL VALOR DE LA COOKIE CLASE
				$cookieClase = $_COOKIE['clase'];
				//OBTENGO LOS ALUMNOS DE ESA CLASE
				$alumnos = obtenerAlumnos($cookieClase,$mesElegido);
				//OBTENGO EL VALOR DE LA TABLA
				$tabla = mostrarTablaAsistencias($alumnos);
			}
		}
	}elseif(isset($_POST['etapa'])){ //SI SE HA DADO AL BOTON ETAPA
		//ELIMINAREMOS EL VALOR DE LA COOKIE CLASE SI EXISTE
		$tabla = "";
		//OBTENGO EL VALOR DE LA ETAPA QUE SE HA PULSADO
		$etapa = $_POST['etapa'];
		//OBTENGO LAS CLASES DE LA ETAPA QUE SE HA PULSADO
		$clases = obtenerClases($etapa);
		//CREO LA COOKIE ETAPA CON EL VALOR DE LA ETAPA QUE SE HA PULSADO
		setcookie("etapa",$etapa,time() + 3600,"/"); 
	}elseif(isset($_POST['clase'])){ //SI SE HA DADO AL BOTON CLASE
		//OBTENGO EL VALOR DE LA CLASE QUE SE HA PULSADO
		$clase = $_POST['clase'];
		$cookieMes = $_COOKIE['meses'];
		$alumnos = obtenerAlumnos($clase,$cookieMes);
		//OBTENGO LOS ALUMNOS QUE HAY EN ESA CLASE
		if(isset($_COOKIE['resumen'])){
			$tabla = mostrarTablaAsistencias($alumnos);
		}else{
			//OBTENGO LA TABLA QUE SE VA A MOSTRAR 
			$tabla = mostrarTabla($alumnos,$cookieMes);
		}
		//CREO LA COOKIE CLASE CON EL VALOR DE LA CLASE QUE SE HA PULSADO
		setcookie("clase",$clase,time() + 3600,"/"); 
	}elseif(isset($_POST['mesAnterior'])){ //SI SE PULSA AL BOTON DE COPIAR EL MES ANTERIOR
		//INSERT DE TODOS LOS VALORES DEL MES ACTUAL/ANTERIOR DE LA TABLA ASISTENCIA PARA EL NUEVO MES
		copiarMes();
	}elseif(isset($_POST['Resumen'])){ //SI SE PULSA AL BOTON DE RESUMEN
		if(!isset($_COOKIE['resumen'])){
			setcookie("resumen","true",time() + 3600,"/");
			$boton = "Alumnos";
			$clase = $_COOKIE['clase'];
			$cookieMes = $_COOKIE['meses'];
			$alumnos = obtenerAlumnos($clase,$cookieMes);
			$tabla = mostrarTablaAsistencias($alumnos);
		}else{
			$boton = "Resumen";
			setcookie("resumen","",-1,"/");
			$cookieMes = $_COOKIE['meses'];
			$cookieClase = $_COOKIE['clase'];
			$alumnos = obtenerAlumnos($cookieClase,$cookieMes);
			$tabla = mostrarTabla($alumnos,$cookieMes);
		}
	}
}
require_once $_SERVER['DOCUMENT_ROOT']."/admin/vista/vista_altabaja.php";
?>