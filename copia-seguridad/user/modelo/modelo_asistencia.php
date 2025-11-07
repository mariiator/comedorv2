<?php
require_once $_SERVER['DOCUMENT_ROOT']."/db/db.php";

//FUNCION DONDE OBTIENE EL TOTAL DE PERSONAS DEPENDIENDO DE LA ETAPA, QUE TIENEN QUE ASISTIR EL DIA DE HOY
function obtenerAsistencias($etapa) {
	global $conexion;
	try {
		$obtenerInfo = $conexion->prepare("
			SELECT COUNT(*) AS total
				FROM Etapa
					INNER JOIN Clase ON Etapa.id = Clase.etapa_id
					INNER JOIN Persona ON Persona.clase_id = Clase.id
					INNER JOIN Asistencia ON Persona.id = Asistencia.persona_id
				WHERE 
					Asistencia.anyo = YEAR(CURRENT_DATE())
					AND Asistencia.mes = MONTH(CURRENT_DATE())
					AND Etapa.nombre = :etapa
					AND
				(CASE
					WHEN DAYOFWEEK(CURRENT_DATE()) = 2 THEN Asistencia.lunes
					WHEN DAYOFWEEK(CURRENT_DATE()) = 3 THEN Asistencia.martes
					WHEN DAYOFWEEK(CURRENT_DATE()) = 4 THEN Asistencia.miercoles
					WHEN DAYOFWEEK(CURRENT_DATE()) = 5 THEN Asistencia.jueves
					WHEN DAYOFWEEK(CURRENT_DATE()) = 6 THEN Asistencia.viernes
				END) = 1;
		");
		$obtenerInfo->bindParam(':etapa', $etapa);
		$obtenerInfo->execute();
		$informacion=$obtenerInfo->fetchColumn();
		return $informacion;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}

//FUNCION QUE DEVUELVE EL VALOR DEL TOTAL DE PERSONAS QUE HAN ASISTIDO SEGUN LA ETAPA
function obtenerTotalPersonas($etapa) {
	global $conexion;
	$array = array();
	$total = 0;
	try {
		$obtenerInfo = $conexion->prepare("
			SELECT COUNT(*) AS TOTAL
			FROM Etapa
			    INNER JOIN Clase ON Etapa.id = Clase.etapa_id
			    INNER JOIN Persona ON Clase.id = Persona.clase_id
			    INNER JOIN Registro ON Persona.id = Registro.persona_id
			WHERE
			    DATE(Registro.fecha) = CURDATE()
			    AND Etapa.nombre = :etapa
			    AND Registro.hora <= CURTIME();
		");
		$obtenerInfo->bindParam(':etapa', $etapa);
		$obtenerInfo->execute();
		$informacion=$obtenerInfo->fetchColumn();
		return $informacion;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}
?>