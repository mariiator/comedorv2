<?php
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/admin/modelo/modelo_list.php";
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
//SI NO ESTA DECLARADA LA COOKIE DE LISTADO NOS DEVOLVERA AL CONTROLADOR DE CONFIGURACION
if(!isset($_COOKIE['listado'])){
	header("location:/comedorv2/admin/controlador/mejora/controlador_administracion - copia.php");
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
	    	//RECOJO EL VALOR DE LA FECHA
	    	$year = explode("-",$meses->format('Y-m'));
	    	$mes = $year[1];
	    	$anyo = $year[0];
	    	//OBTENGO LOS DIAS ENTRE SEMANA DONDE HAN ASISTIDO AL MENOS ALGUN ALUMNO
	    	$dias = obtenerDiasEntreSemana($mes,$anyo);
	    	//OBTENGO EL VALOR DE LA TABLA QUE SE MOSTRARA EN LA VISTA
	    	//$listado = mostrarTabla($alumnos,$dias,$clase . " (" . $mes . ")" );
        $listado = mostrarTabla($alumnos, $mes, $anyo, $clase." (" .$mes.")" );
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
		//RECOJO EL VALOR DE LA FECHA
		$year = explode("-",$meses->format('Y-m'));
		$mes = $year[1];
		$anyo = $year[0];
		//OBTENGO LOS DIAS ENTRE SEMANA DONDE HAN ASISTIDO AL MENOS ALGUN ALUMNO
		$dias = obtenerDiasEntreSemana($mes,$anyo);
		//OBTENGO EL VALOR DE LA TABLA QUE SE MOSTRARA EN LA VISTA
		//$listado = mostrarTabla($alumnos,$dias,$clase . " (" . $mes .")" );
    $listado = mostrarTabla($alumnos, $mes, $anyo, $clase." (" .$mes.")" );
	}
}
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/admin/vista/vista_list - copia.php";
?>