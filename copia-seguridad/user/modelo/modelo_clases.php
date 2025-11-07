<?php
require_once $_SERVER['DOCUMENT_ROOT']."/db/db.php";
//CONSULTA PARA PODER RECOGER TODAS LAS CLASES SEGUN LA ETAPA
function obtenerClases($id) {
	global $conexion;
	$array;
	$id_clase;
	$nom_clase;
	try {
		$obtenerInfo = $conexion->prepare("SELECT * FROM Clase INNER JOIN Etapa ON Clase.etapa_id = Etapa.id WHERE Etapa.nombre = :id;");
		$obtenerInfo->bindParam(':id', $id);
		$obtenerInfo->execute();
		$informacion=$obtenerInfo->fetchAll();
		foreach($informacion as $row){
			$id_clase = $row[0];
			$nom_clase = $row[2];
			$array[$id_clase] = $nom_clase;
		}
		return $array;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}
?>