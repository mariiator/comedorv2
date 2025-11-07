<?php
require_once $_SERVER['DOCUMENT_ROOT']."/modelo/modelo_inicio.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/phpCAS-1.3.6/source/CAS.php';
phpCAS::setDebug();
phpCAS::client(CAS_VERSION_2_0,'login01.globaleduca.es',443,'/mi010');
phpCAS::setNoCasServerValidation();
phpCAS::forceAuthentication();
$casuser = phpCAS::getUser();
$idProfesor = obtenerProfesores($casuser);
if(!empty($idProfesor)){
	setcookie("profesor",$idProfesor,time()+36000,"/");
	header('location:/profesor/controlador/controlador_profesor.php');
	exit();
}
?>