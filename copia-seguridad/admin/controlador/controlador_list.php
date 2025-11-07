<?php
require_once $_SERVER['DOCUMENT_ROOT']."/admin/modelo/modelo_list.php";
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
//SI NO ESTA DECLARADA LA COOKIE DE LISTADO NOS DEVOLVERA AL CONTROLADOR DE CONFIGURACION
if(!isset($_COOKIE['listado'])){
	header("location:/admin/controlador/controlador_listado.php");
	exit();
}
//DECLARO LAS VARIABLES MESES, FECHA, ARRAY, COOKIE, CLASES, ETAPA, ARRAYLISTADO, LISTADO, LISTADO2 Y LISTADO3
$meses;
$fecha;
$array;
$clases;
$etapa;
$arrayListado;
$listado = "";
$listado2 = "";
$listado3 = "";
$cookie = $_COOKIE['listado'];
//SI SE HA DADO AL BOTON DE MES RECOGERA EL VALOR DEL MES Y ACTUALIZARA LA FECHA
if(isset($_POST['mes'])){
    $meses = new DateTime($_POST['mes']);
    $fecha = $meses->format('Y-m-d');
}else{ //SI NO SE HA DADO AL BOTON DE MES SE CREARA LA FECHA CON LOS VALORES DEL DIA ACTUAL
    $meses = new DateTime();
    $fecha = $meses->format('Y-m-d');
}
//OBTENGO EL VALOR DEL ARRAY CON LA COOKIE DE LISTADO Y CON LA FECHA OBTENIDA
$array = consultaCookie($cookie,$fecha);
if($cookie == "Resumen Mensual"){ //SI EL VALOR DE LA COOKIE ES IGUAL A RESUMEN MENSUAL
	//OBTENGO EL VALOR DE LA TABLA QUE SE MOSTRARA EN LA VISTA
	$listado2 = mostrarTabla2($array);
}elseif($cookie == "Resumen Asistencias"){ //SI EL VALOR DE LA COOKIE ES IGUAL A RESUMEN MENSUAL
	//OBTENGO EL VALOR DE LA TABLA QUE SE MOSTRARA EN LA VISTA
	$listado3 = mostrarTabla3($array);
}

//SI LA PETICION DEL SERVIDOR ES POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['siguiente'])){ //SI SE HA DADO AL BOTON DE SIGUIENTE
    	//MODIFICAMOS EL VALOR DE MESES Y LE AÑADIMOS UN MES MAS
        $meses->modify('+1 month');
        $fecha = $meses->format('Y-m-d');
    }elseif(isset($_POST['anterior'])){ //SI SE HA DADO AL BOTON DE ANTERIOR
    	//MODIFICAMOS EL VALOR DE MESES Y LE RESTAREMOS UN MES
        $meses->modify('-1 month');
        $fecha = $meses->format('Y-m-d');
    }elseif(isset($_POST['Volver'])){ //SI SE HA PULSADO AL BOTON DE VOLVER
		setcookie("etapa","",-1,"/");
		setcookie("clase","",-1,"/");
		setcookie("listado","",-1,"/");
		header("location:/admin/controlador/controlador_listado.php");
		exit();
    }
    if($cookie == "Listado Mensual"){ //SI EL VALOR DE LA COOKIE ES IGUAL A LISTADO MENSUAL
    	if(isset($_POST['Etapa'])){ //SI SE HA PULSADO AL BOTON DE ETAPA
    		//RECOJO EL VALOR DE LA ETAPA QUE SE HA PULSADO
	    	$etapa = $_POST['Etapa'];
	    	//CREAMOS LA COOKIE ETAPA CON EL VALOR DE LA ETAPA QUE SE HA PULSADO
	    	setcookie("etapa",$etapa,time() + 3600,"/");
	    	//OBTENGO LAS CLASES QUE HAY EN ESA ETAPA
	    	$clases = obtenerClases($etapa);
	    }elseif(isset($_POST['Clase'])){ // SI SE HA PULSADO AL BOTON CLASE
	    	//RECOJO EL VALOR DE LA CLASE PULSADA
	    	$clase = $_POST['Clase'];
	    	//OBTENGO LOS ALUMNOS QUE HAY EN ESA CLASE
	    	$alumnos = obtenerAlumnos($clase,$fecha);
	    	//CREAMOS LA COOKIE CLASE CON EL VALOR DE LA CLASE QUE SE HA PULSADO
	    	setcookie("clase",$clase,time() + 3600,"/");
	    	//OBTENGO LAS ASISTENCIAS DE LA CLASE SELECCIONADA
	    	$asistencias = obtenerAsistencias($clase,$meses->format('Y-m'));
	    	//RECOJO EL VALOR DE LA FECHA
	    	$year = explode("-",$meses->format('Y-m'));
	    	$mes = $year[1];
	    	$anyo = $year[0];
	    	//OBTENGO LOS DIAS ENTRE SEMANA DONDE HAN ASISTIDO AL MENOS ALGUN ALUMNO
	    	$dias = obtenerDiasEntreSemana($mes,$anyo);
	    	//LE DOY VALORES AL ARRAYLISTADO
	    	$arrayListado = [$alumnos,$asistencias,$dias];
	    	//OBTENGO EL VALOR DE LA TABLA QUE SE MOSTRARA EN LA VISTA
	    	$listado = mostrarTabla($arrayListado);
	    }
    }elseif($cookie == "Resumen Mensual") { //SI EL VALOR DE LA COOKIE ES IGUAL A RESUMEN MENSUAL
    	//OBTENGO EL ARRAY QUE SE VA A MOSTRAR
    	$array = consultaCookie($cookie,$fecha);
    	//OBTENGO EL VALOR DE LA TABLA QUE SE MOSTRARA EN LA VISTA
		$listado2 = mostrarTabla2($array);
	}elseif($cookie == "Resumen Asistencias") { //SI EL VALOR DE LA COOKIE ES IGUAL A RESUMEN ASISTENCIAS
		//OBTENGO EL ARRAY QUE SE VA A MOSTRAR
    	$array = consultaCookie($cookie,$fecha);
    	//OBTENGO EL VALOR DE LA TABLA QUE SE MOSTRARA EN LA VISTA
		$listado3 = mostrarTabla3($array);
	}
}
//SI ESTA DECLARADO LA COOKIE ETAPA Y NO SE HA PULSADO AL BOTON ETAPA
if(isset($_COOKIE['etapa']) && !isset($_POST['Etapa'])){
	//OBTENGO EL VALOR DE LA COOKIE ETAPA
	$etapa = $_COOKIE['etapa'];
	//OBTENGO LAS CLASES QUE HAY EN LA ETAPA
	$clases = obtenerClases($etapa);
	//SI ESTA DECLARADA LA COOKIE CLASE Y NO SE HA PULSADO AL BOTON CLASE
	if(isset($_COOKIE['clase']) && !isset($_POST['Clase'])){
		//OBTENGO EL VALOR DE LA COOKIE CLASE
		$clase = $_COOKIE['clase'];
		//OBTENGO LOS ALUMNOS DE ESA CLASE
		$alumnos = obtenerAlumnos($clase,$meses->format('Y-m-d'));
		//OBTENGO LAS ASISTENCIAS DE ESA CLASE
		$asistencias = obtenerAsistencias($clase,$meses->format('Y-m'));
		//RECOJO EL VALOR DE LA FECHA
		$year = explode("-",$meses->format('Y-m'));
		$mes = $year[1];
		$anyo = $year[0];
		//OBTENGO LOS DIAS ENTRE SEMANA DONDE HAN ASISTIDO AL MENOS ALGUN ALUMNO
		$dias = obtenerDiasEntreSemana($mes,$anyo);
		//LE DOY VALORES AL ARRAYLISTADO
		$arrayListado = [$alumnos,$asistencias,$dias];
		//OBTENGO EL VALOR DE LA TABLA QUE SE MOSTRARA EN LA VISTA
		$listado = mostrarTabla($arrayListado);
	}
}
require_once $_SERVER['DOCUMENT_ROOT']."/admin/vista/vista_list.php";
?>