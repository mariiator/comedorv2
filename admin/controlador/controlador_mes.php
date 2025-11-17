<?php
//SI QUEREMOS UTILIZAR COOKIES, EN CASO DE QUE DEJEN DE EXISTIR LAS COOKIES NOS REEDIRIJA DE NUEVO AL INICIO DE SESION
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/admin/modelo/modelo_list.php";
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
		$year = explode("-",$fecha);
		$mes = $year[1];
		$anyo = $year[0];
		//OBTENGO LOS DIAS DONDE HAN ASISTIDO AL MENOS UN ALUMNO
		$dias = obtenerDiasEntreSemana($mes,$anyo);
		//OBTENGO EL VALOR DE LA TABLA QUE SE MOSTRARA EN LA VISTA
		$listado = mostrarTabla($alumnos,$dias, $clase);
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