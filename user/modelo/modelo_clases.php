<?php
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/db/db.php";
//CONSULTA PARA PODER RECOGER TODAS LAS CLASES SEGUN LA ETAPA
function obtenerClases($id) {
	global $conexion;
	$array;
	$id_clase;
	$nom_clase;
	try {
		$obtenerInfo = $conexion->prepare("
			SELECT
				Clase.id,
				Clase.nombre
			FROM 
				Clase 
				INNER JOIN Etapa ON Clase.etapa_id = Etapa.id
                INNER JOIN Persona ON Clase.id = Persona.clase_id
                INNER JOIN Asistencia ON Persona.id = Asistencia.persona_id
			WHERE 
				Etapa.nombre = :id
                AND (
			        CASE
			            WHEN DAYOFWEEK(CURRENT_DATE()) = 2 THEN Asistencia.lunes
			            WHEN DAYOFWEEK(CURRENT_DATE()) = 3 THEN Asistencia.martes
			            WHEN DAYOFWEEK(CURRENT_DATE()) = 4 THEN Asistencia.miercoles
			            WHEN DAYOFWEEK(CURRENT_DATE()) = 5 THEN Asistencia.jueves
			            WHEN DAYOFWEEK(CURRENT_DATE()) = 6 THEN Asistencia.viernes
			        END
			    ) = 1
            GROUP BY 
            	Clase.nombre, Clase.id
            ORDER BY
            	Clase.nombre;
        ");
		$obtenerInfo->bindParam(':id', $id);
		$obtenerInfo->execute();
		$informacion=$obtenerInfo->fetchAll();
		foreach($informacion as $row){
			$id_clase = $row[0];
			$nom_clase = $row[1];
			$array[$id_clase] = $nom_clase;
		}
		return $array;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}
?>