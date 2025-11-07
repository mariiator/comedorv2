<?php
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/user/modelo/modelo_alumnos.php";
//RECOGE LOS LOS VALORES QUE SE LE HAN ENVIADO Y ACTUALIZO LA BASE DE DATOS
if(isset($_POST['alumno'])){
	$nombre = $_POST['alumno'];
	//HAGO EL SUBSTR PORQUE EL AJAX LE ENVIA EL ID DEL ELEMENTO, ES DECIR, LE ENVIA '#ID', LO HAGO PARA QUITAR EL #
	$alumno = substr($nombre, 1);
	anularAlumno($alumno);
}
?>