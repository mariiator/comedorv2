<?php
require_once $_SERVER['DOCUMENT_ROOT']."/cocinero/modelo/modelo_listado.php";
//AÑADIR AQUI AL IGUAL QUE HAGO EN EL CALENDARIO LA SELECCION DE MESES
if(isset($_POST['dia'])){ //AL PULSAR AL BOTON CERRAR NOS DEVOLVERA AL INICIO DE SESION DEL USUARIO
	$fecha = $_POST['dia'];
	$profesores = obtenerProfesores($fecha);
	$tabla = mostrarTabla($profesores,$fecha);
	echo $tabla;
}
?>