<?php
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/admin/modelo/modelo_altabaja.php";

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
$arrayListado = array("Listado Mensual","Resumen Mensual","Resumen Asistencias");

//DECLARO UN ARRAY CON LOS NOMBRES DE LOS MESES EN ORDEN PARA QUE LUEGO SE VEA EN LA VISTA
$nombreMeses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre","Enero");

//DECLARO LA VARIABLE MESES DONDE OBTIENE LA FECHA ACTUAL
$meses = new DateTime();

//DECLARO EL MES ACTUAL
$mesActual = $meses->format('m');

//AÑADO UN MES A LA FECHA ACTUAL
//$meses->modify('+1 month');

//DECLARO EL MES SIGUIENTE
$sigMes = $mesActual+1;

//ARRAY DONDE GUARDO LOS NOMBRES DEL MES ACTUAL Y EL SIGUIENTE MES
$array = [$nombreMeses[$mesActual-1], $nombreMeses[$sigMes-1]];

//DECLARO LAS VARIABLES ETAPAS, MESELEGIDO, BOTON Y CLASENAVEGADOR
$etapas;
$mesElegido = 0;
$boton = "";
$claseNavegador = "";
if(isset($_COOKIE['clase'])){
	$claseNavegador = $_COOKIE['clase'];
}
//SI ESTA DECLARADO LA COOKIE MESES NOS HARA LO SIGUIENTE
if(isset($_COOKIE['meses'])){

	//OBTENGO EL VALOR DE LA COOKIE MESES
	$cookieMes = $_COOKIE['meses'];
	//OBTENGO SI HAY DATOS EN LA BASE DE DATOS EN ESE MES, SI HAY DATOS NOS DEVOLVERA TRUE, EN CASO CONTRARIO SERA FALSE
	$booleanMes = obtenerMes($cookieMes);

	//SI EL VALOR DE BOOLEAN ES TRUE TENDREMOS QUE MOSTRAR LAS ETAPAS
	if($booleanMes){

		//OBTENGO LAS ETAPAS
		$etapas = obtenerEtapas();

		if(!isset($_COOKIE['resumen']) && isset($_COOKIE['etapa'])){ //SI NO ESTA DECLARADA LA COOKIE RESUMEN Y SI ESTA DECLARADA LA COOKIE ETAPA MOSTRARA LAS CLASES

			//LE DOY VALOR A LA VARIABLE BOTON PARA QUE TENGA VALOR DIFERENTE
			$boton = "Resumen";
			//OBTENGO EL VALOR DE LA COOKIE ETAPA
			$cookieEtapa = $_COOKIE['etapa'];
			//OBTENGO LAS CLASES DE ESA ETAPA
			$clases = obtenerClases($cookieEtapa);

			if(isset($_COOKIE['clase'])){ //SI ESTA DECLARADO LA COOKIE CLASE

				//OBTENGO EL VALOR DE LA COOKIE CLASE
				$claseNavegador = $_COOKIE['clase'];
				//OBTENGO LOS ALUMNOS DE ESA CLASE
				$alumnos = obtenerAlumnos($claseNavegador,$cookieMes);
				//OBTENGO EL VALOR DE LA TABLA
				$tabla = mostrarTabla($alumnos,$cookieMes);

			}

		}elseif(isset($_COOKIE['resumen']) && isset($_COOKIE['clase']) && isset($_COOKIE['etapa']) && isset($_COOKIE['meses'])){ //SI ESTA DECLARADA LA COOKIE RESUMEN, CLASE, ETAPA Y MESES NOS HARA LO SIGUIENTE PARA MOSTRAR LA TABLA DE RESUMEN DE ESE MES
			
			//LE DOY VALOR DIFERENTE A LA VARIABLE BOTON
			$boton = "Alumnos";
			//OBTENGO LAS ETAPAS
			$etapas = obtenerEtapas();
			//OBTENGO EL VALOR DE LA COOKIE ETAPA
			$cookieEtapa = $_COOKIE['etapa'];
			//OBTENGO LAS CLASES DE ESA ETAPA
			$clases = obtenerClases($cookieEtapa);
			//OBTENGO EL VALOR DE LA COOKIE CLASE
			$claseNavegador = $_COOKIE['clase'];
			//OBTENGO EL VALOR DE LA COOKIE MESES
			$cookieMes = $_COOKIE['meses'];
			//OBTENGO LOS ALUMNOS DE ESA CLASE Y ESE MES
			$alumnos = obtenerAlumnos($claseNavegador,$cookieMes);
			//OBTENGO LA TABLA DE RESUMEN
			$tabla = mostrarTablaAsistencias($alumnos,$cookieMes);

		}

	}else{ //SI EL VALOR DE BOOLEAN ES FALSE ENVIAREMOS EL ARRAY DE ETAPAS VACIO PARA QUE NO MUESTRE ESTAS ETAPAS
		
		//MOSTRAR CUANDO NO HAY DATOS DE ESE MES
		$etapas = array();

	}
}

//SI LA PETICION DEL SERVIDOR ES POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(isset($_POST['mes'])){ //SI SE HA DADO AL BOTON MES

		//OBTENGO EL VALOR DEL MES
		$mes = $_POST['mes'];
		//OBTENGO EL VALOR DE LA POSICION DEL MES DENTRO DEL ARRAY NOMBREMESES
		$key = array_search($mes, $nombreMeses);
		//OBTENGO EL VALOR DEL MES ELEGIDO
		$mesElegido = $key + 1;
		//CREO LA COOKIE MESES CON EL MES QUE HEMOS PULSADO
		setcookie("meses",$mesElegido,time() + 3600,"/");

		if(obtenerMes($mesElegido)){ //SI EL MES QUE HEMOS PULSADO DEVUELVE TRUE

			//OBTENGO LAS ETAPAS
			$etapas = obtenerEtapas();

		}else{ //SI EL MES QUE HEMOS PULSADO DEVUELVE FALSE

			//LE DOY VALOR VACIO A ETAPAS
			$etapas = array();

		}

		if(isset($_COOKIE['etapa']) && !isset($_COOKIE['resumen'])){ //SI ESTA DECLARADA LA COOKIE ETAPA Y NO ESTA DECLARADA LA COOKIE RESUMEN

			//OBTENGO EL VALOR DE LA COOKIE ETAPA
			$cookieEtapa = $_COOKIE['etapa'];
			//OBTENGO LAS CLASES DE ESA ETAPA
			$clases = obtenerClases($cookieEtapa);
			
			if(isset($_COOKIE['clase'])){ //SI ESTA DECLARADO LA COOKIE CLASE

				//OBTENGO EL VALOR DE LA COOKIE CLASE
				$cookieClase = $_COOKIE['clase'];
				//OBTENGO LOS ALUMNOS DE ESA CLASE
				$alumnos = obtenerAlumnos($cookieClase,$mesElegido);
				//OBTENGO EL VALOR DE LA TABLA
				$tabla = mostrarTabla($alumnos,$mesElegido);
				//DOY VALOR AL VALOR DEL BOTON
				$boton = "Resumen";

			}

		}elseif(isset($_COOKIE['etapa']) && isset($_COOKIE['resumen'])){ //SI ESTA DECLARADA LA COOKIE ETAPA Y LA COOKIE RESUMEN

			//OBTENGO EL VALOR DE LA COOKIE ETAPA
			$cookieEtapa = $_COOKIE['etapa'];
			//OBTENGO LAS CLASES DE ESA ETAPA
			$clases = obtenerClases($cookieEtapa);
			
			if(isset($_COOKIE['clase'])){//SI ESTA DECLARADO LA COOKIE CLASE

				//OBTENGO EL VALOR DE LA COOKIE CLASE
				$cookieClase = $_COOKIE['clase'];
				//OBTENGO LOS ALUMNOS DE ESA CLASE
				$alumnos = obtenerAlumnos($cookieClase,$mesElegido);
				//OBTENGO EL VALOR DE LA TABLA
				$tabla = mostrarTablaAsistencias($alumnos,$mesElegido);
				//DOY VALOR AL VALOR DEL BOTON
				$boton = "Alumnos";

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

	}elseif(isset($_POST['clase']) && isset($_COOKIE['meses'])){ //SI SE HA DADO AL BOTON CLASE Y EXISTE EL VALOR DE LA COOKIE MESES

		//OBTENGO EL VALOR DE LA CLASE QUE SE HA PULSADO
		$claseNavegador = $_POST['clase'];
		$cookieMes = $_COOKIE['meses'];
		$alumnos = obtenerAlumnos($claseNavegador,$cookieMes);
		
		if(isset($_COOKIE['resumen'])){ //OBTENGO LOS ALUMNOS QUE HAY EN ESA CLASE

			$boton = "Alumnos";
			$tabla = mostrarTablaAsistencias($alumnos,$cookieMes);

		}else{

			//OBTENGO LA TABLA QUE SE VA A MOSTRAR 
			$tabla = mostrarTabla($alumnos,$cookieMes);

		}

		//CREO LA COOKIE CLASE CON EL VALOR DE LA CLASE QUE SE HA PULSADO
		setcookie("clase",$claseNavegador,time() + 3600,"/");
	}elseif(isset($_POST['mesAnterior'])){ //SI SE PULSA AL BOTON DE COPIAR EL MES ANTERIOR

		//INSERT DE TODOS LOS VALORES DEL MES ACTUAL/ANTERIOR DE LA TABLA ASISTENCIA PARA EL NUEVO MES
		copiarMes();

	}elseif(isset($_POST['Resumen']) && isset($_COOKIE['meses'])){ //SI SE PULSA AL BOTON DE RESUMEN Y EXISTE LA COOKIE MESES
		
		if(!isset($_COOKIE['resumen'])){ //SI NO ESTA CREADA LA COOKIE RESUMEN LA CREAREMOS
			
			//CREO LA COOKIE RESUMEN
			setcookie("resumen","true",time() + 3600,"/");
			//LE DOY VALOR A LA VARIABLE BOTON
			$boton = "Alumnos";
			//RECOJO EL VALOR DE LA COOKIE CLASE
			$clase = $_COOKIE['clase'];
			//RECOJO EL VALOR DE LA COOKIE MESES
			$cookieMes = $_COOKIE['meses'];
			//OBTENGO LOS ALUMNOS DE LA CLASE Y MES
			$alumnos = obtenerAlumnos($clase,$cookieMes);
			//OBTENGO LA TABLA DE LOS ALUMNOS
			$tabla = mostrarTablaAsistencias($alumnos,$cookieMes);

		}else{

			//DOY VALOR A LA VARIABLE BOTON
			$boton = "Resumen";
			//ELIMINO LA COOKIE RESUMEN
			setcookie("resumen","",-1,"/");
			//RECOJO EL VALOR DE LA COOKIE MESES
			$cookieMes = $_COOKIE['meses'];
			//RECOJO EL VALOR DE LA COOKIE CLASE
			$cookieClase = $_COOKIE['clase'];
			//OBTENGO LOS ALUMNOS DE ESA CLASE Y MES
			$alumnos = obtenerAlumnos($cookieClase,$cookieMes);
			//OBTENGO LA TABLA DE ESOS ALUMNOS Y MES
			$tabla = mostrarTabla($alumnos,$cookieMes);

		}

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
}
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/admin/vista/vista_altabaja - copia.php";
?>