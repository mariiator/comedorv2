<?php
//SI QUEREMOS UTILIZAR COOKIES, EN CASO DE QUE DEJEN DE EXISTIR LAS COOKIES NOS REEDIRIJA DE NUEVO AL INICIO DE SESION
require_once $_SERVER['DOCUMENT_ROOT']."/admin/modelo/modelo_list.php";
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
//DECLARO LAS VARIABLES FECHA, ETAPA, CLASES, ARRAYLISTADO, LISTADO, LISTADO2 Y LISTADO3
$fecha;
$etapa;
$clases;
$arrayListado;
$listado = "";
$listado2 = "";
$listado3 = "";
//SI RECIBE EL VALOR DE FECHA
if(isset($_POST['fecha'])){
	//GUARDO EL VALOR DE LA FECHA
	$fecha = $_POST['fecha'];
	$fecha .= "-01";
}
//OBTENGO EL VALOR DE LA COOKIE LISTADO
$cookie = $_COOKIE['listado'];
//OBTENGO EL ARRAY DEPENDIENDO DEL VALOR DE LA COOKIE Y FECHA
$array = consultaCookie($cookie,$fecha);
//SI ESTA DECLARADO EL VALOR DE LA COOKIE ETAPA
if(isset($_COOKIE['etapa'])){
	//OBTENGO EL VALOR DE LA COOKIE ETAPA
	$etapa = $_COOKIE['etapa'];
	//OBTENGO LAS CLASES DE LA ETAPA
	$clases = obtenerClases($etapa);
	//SI ESTA DECLARADA LA COOKIE CLASE, RECIBE EL VALOR DE FECHA Y EL VALOR DE LA COOKIE ES IGUAL A LISTADO MENSUAL
	if(isset($_COOKIE['clase']) && isset($_POST['fecha']) && $cookie == "Listado Mensual"){
		//OBTENGO EL VALOR DE LA COOKIE CLASE
		$clase = $_COOKIE['clase'];
		//OBTENGO LOS ALUMNOS DE LA CLASE Y LA FECHA
		$alumnos = obtenerAlumnos($clase,$fecha);
		//OBTENGO LAS ASISTENCIAS DE ESA CLASE EN ESA FECHA
		$asistencias = obtenerAsistencias($clase,$fecha);
		$year = explode("-",$fecha);
		$mes = $year[1];
		$anyo = $year[0];
		//OBTENGO LOS DIAS DONDE HAN ASISTIDO AL MENOS UN ALUMNO
		$dias = obtenerDiasEntreSemana($mes,$anyo);
		//LE DOY VALORES AL ARRAY LISTADO
		$arrayListado = [$alumnos,$asistencias,$dias];
		//OBTENGO EL VALOR DE LA TABLA QUE SE MOSTRARA EN LA VISTA
		$listado = mostrarTabla($arrayListado);
		echo $listado;
	}
}
if(isset($_POST['fecha']) && $cookie == "Resumen Mensual"){ //SI RECIBE VALORES DE LA FECHA Y EL VALOR DE LA COOKIE ES IGUAL A RESUMEN MENSUAL
	//OBTENGO EL VALOR DE LA TABLA QUE SE MOSTRARA EN LA VISTA
	$listado2 = mostrarTabla2($array);
	echo $listado2;
}elseif(isset($_POST['fecha']) && $cookie == "Resumen Asistencias"){ //SI RECIBE VALORES DE LA FECHA Y EL VALOR DE LA COOKIE ES IGUAL A RESUMEN ASISTENCIAS
	//OBTENGO EL VALOR DE LA TABLA QUE SE MOSTRARA EN LA VISTA
	$listado3 = mostrarTabla3($array);
	echo $listado3;
}
?>