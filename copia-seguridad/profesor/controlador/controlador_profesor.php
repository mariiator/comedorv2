<?php
    $ldaprdn  = "pablo";
    $ldappass = "En6misma2!";
    $ldapconn = ldap_connect("ldap://ldap01.globaleduca.es") or die("Could not connect to LDAP server.");

if ($ldapconn) 
{
    $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);
    if ($ldapbind) 
    {
        echo "LDAP bind successful...";
    }
    else 
    {
        echo "LDAP bind failed...";
    }
}
$Result = ldap_search($ldapconn, "OU=IT,DC='Domain',DC=corp", "(samaccountname=$ldaprdn)", array("dn"));
$data = ldap_get_entries($ldapconn, $Result);
print_r($data);
?>

/*require_once $_SERVER['DOCUMENT_ROOT']."/profesor/modelo/modelo_profesor.php";
//SI QUEREMOS UTILIZAR COOKIES, EN CASO DE QUE DEJEN DE EXISTIR LAS COOKIES NOS REEDIRIJA DE NUEVO AL INICIO DE SESION
if(!isset($_COOKIE['profesor'])){
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
}
//DECLARO LA VARIABLE MESES
$meses;
$fecha;
//SI SE HA DADO AL BOTON DE MES RECOGERA EL VALOR DEL MES Y ACTUALIZARA LA FECHA
if(isset($_POST['mes'])){
    $meses = new DateTime($_POST['mes']);
    $fecha = $meses->format('Y-m-d');
}else{ //SI NO SE HA DADO AL BOTON DE MES SE CREARA LA FECHA CON LOS VALORES DEL DIA ACTUAL
    $meses = new DateTime();
    $fecha = $meses->format('Y-m-d');
}
$correo = $_COOKIE['profesor'];
$mesActual = new DateTime();
$fechaActual = $mesActual->format('Y-m-d');
$calendario = obtenerCalendario($correo,$fecha);
$arrayDia = explode("-", $fecha);
$year = $arrayDia[0];
$month = $arrayDia[1];
$diasEntreSemana = obtenerDiasEntreSemana($month, $year);
$tabla = mostrarCalendario($calendario,$diasEntreSemana,$month,$fechaActual);
$pdf = obtenerPdf($month,$year);
//SI LA PETICION DEL SERVIDOR ES POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['siguiente'])){ //SI SE HA DADO AL BOTON DE SIGUIENTE
    	//MODIFICAMOS EL VALOR DE MESES Y LE AÑADIMOS UN MES MAS
        $meses->modify('+1 month');
        $fecha = $meses->format('Y-m-d');
        $arrayDia = explode("-", $fecha);
		$year = $arrayDia[0];
		$month = $arrayDia[1];
		$pdf = obtenerPdf($month,$year);
        $calendario = obtenerCalendario($correo,$fecha);
        $diasEntreSemana = obtenerDiasEntreSemana($month, $year);
		$tabla = mostrarCalendario($calendario,$diasEntreSemana,$month,$fechaActual);
		if(!$pdf){
			$pdf = "/pdf/noMenu.pdf";
		}
    }elseif(isset($_POST['anterior'])){ //SI SE HA DADO AL BOTON DE ANTERIOR
    	//MODIFICAMOS EL VALOR DE MESES Y LE RESTAREMOS UN MES
        $meses->modify('-1 month');
        $fecha = $meses->format('Y-m-d');
        $arrayDia = explode("-", $fecha);
		$year = $arrayDia[0];
		$month = $arrayDia[1];
        $calendario = obtenerCalendario($correo,$fecha);
        $diasEntreSemana = obtenerDiasEntreSemana($month, $year);
		$tabla = mostrarCalendario($calendario,$diasEntreSemana,$month,$fechaActual);
		$pdf = obtenerPdf($month,$year);
		if(!$pdf){
			$pdf = "/pdf/noMenu.pdf";
		}
    }elseif(isset($_POST['Volver'])){ //SI SE HA PULSADO AL BOTON DE VOLVER
		setcookie("profesor","",-1,"/");
		header("location:/controlador/inicio_sesion.php");
		exit();
    }
}
require_once $_SERVER['DOCUMENT_ROOT']."/profesor/vista/vista_profesor.php";
*/
?>