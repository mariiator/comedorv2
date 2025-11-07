<?php
require_once $_SERVER['DOCUMENT_ROOT']."/db/db.php";
//FUNCION DONDE HACEMOS UNA SENTENCIA PARA OBTENER LAS ETAPAS QUE HAY
function obtenerEtapas() {
	global $conexion;
	$array;
	$id;
	$etapa;
	try {
		$obtenerInfo = $conexion->prepare("SELECT * FROM Etapa;");
		$obtenerInfo->execute();
		$informacion=$obtenerInfo->fetchAll();
		foreach($informacion as $row){
			$id = $row[0];
			$etapa = $row[1];
			$array[$id] = $etapa;
		}
		return $array;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}
?>