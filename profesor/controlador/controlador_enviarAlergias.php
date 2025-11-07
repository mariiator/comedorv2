<?php
require_once $_SERVER['DOCUMENT_ROOT']."/profesor/modelo/modelo_profesor.php";
$correo = $_COOKIE['profesor'];
if($_POST['alergias']){
	var_dump("entra en el post");
	$alergias = $_POST['alergias'];
	guardarAlergia($alergias, $correo);
	echo $alergias;
}
?>