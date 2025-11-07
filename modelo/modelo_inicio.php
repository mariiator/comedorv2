<?php
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/db/db.php";
//FUNCION DONDE HACEMOS UNA SENTENCIA PARA OBTENER LAS ETAPAS QUE HAY
function obtenerProfesores($correo) {
	global $conexion;
	try {
		$obtenerInfo = $conexion->prepare("SELECT Id FROM Profesores WHERE Correo = :correo;");
		$obtenerInfo->bindParam(':correo', $correo);
		$obtenerInfo->execute();
		$id=$obtenerInfo->fetchColumn();
		return $id;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}
?>