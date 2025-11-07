<?php
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/db/db.php";
//FUNCION DONDE NOS DEVUELVE LA TABLA QUE QUEREMOS MOSTRAR SEGUN LAS DOS COOKIES QUE RECOGEMOS
function eleccion($id,$etapa){
	$array;
	if($id == "asistir"){
		$array = obtenerPersonas($etapa);
		$tabla = mostrarTabla($array);
	}elseif($id == "asistido"){
		$array = obtenerAsistencia($etapa);
		$tabla = mostrarTablaAsistencia($array);
	}elseif($id == "faltan"){
		$total = obtenerPersonas($etapa);
		$asistencia = obtenerAsistencia($etapa);
		$array = obtenerFaltas($total,$asistencia);
		$tabla = mostrarTabla($array);
		if(!empty($array)){
			$boton = obtenerBoton($array);
			$tabla .= "<a href='".$boton."' class='btn btn-warning btn-lg'>ENVIAR CORREOS</a>";
		}
	}
	return $tabla;
}

//FUNCION DONDE HACEMOS UNA SENTENCIA PARA OBTENER LAS PERSONAS QUE TIENEN QUE ASISTIR EL DIA DE HOY SEGUN LA ETAPA EN LA QUE ESTAN
function obtenerPersonas($etapa) {
	global $conexion;
	$clase;
	$nombre;
	$array = array();
	try {
		$obtenerInfo = $conexion->prepare("
			SELECT Clase.nombre, Persona.Nombre, Persona.apellido1, Persona.apellido2, Persona.id
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
		$informacion=$obtenerInfo->fetchAll();
		foreach($informacion as $row){
			$clase = $row[0];
			$nombre = array($row[1],$row[2],$row[3]);
			$array[$row[4]] = [$clase,join(" ",$nombre)];
		}
		return $array;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}

//FUNCION DONDE HACEMOS UNA SENTENCIA PARA OBTENER LAS PERSONAS QUE HAN ASISTIDO EL DIA DE HOY SEGUN LA ETAPA EN LA QUE ESTAN
function obtenerAsistencia($etapa){
	global $conexion;
	$clase;
	$nombre;
	$array = array();
	try {
		$obtenerInfo = $conexion->prepare("
				SELECT 
					Clase.nombre, 
					Persona.Nombre, 
					Persona.apellido1, 
					Persona.apellido2, 
					Registro.hora,
					Persona.id
				FROM 
					Etapa
					INNER JOIN Clase ON Etapa.id = Clase.etapa_id
					INNER JOIN Persona ON Persona.clase_id = Clase.id
					INNER JOIN Registro ON Persona.id = Registro.persona_id
				WHERE
					DATE(Registro.fecha) = CURDATE()
			    	AND Etapa.nombre = :etapa
			    	AND Registro.hora <= CURTIME()
			    ORDER BY
			    	Clase.nombre;
		");
		$obtenerInfo->bindParam(':etapa', $etapa);
		$obtenerInfo->execute();
		$informacion=$obtenerInfo->fetchAll();
		foreach($informacion as $row){
			$nombre = array($row[1],$row[2],$row[3]);
			$array[$row[5]] = [$row[0],$row[4],join(" ",$nombre)];
		}
		return $array;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}

//FUNCION PARA OBTENER LAS PERSONAS QUE FALTAN POR ASISTIR EL DIA DE HOY SEGUN LA ETAPA EN LA QUE ESTAN
function obtenerFaltas($total,$asistencia){
	$array = array();
	foreach($total as $id => $elemento){
		if (!array_key_exists($id, $asistencia)) {
	        $array[$id] = [$elemento[0],$elemento[1]];
	    }
	}
	return $array;
}

function obtenerBoton($array){
	$boton = "https://intranet.maristaschamberi.com/scripts/emailcomedor.php?";
	foreach($array as $id => $elemento){
		$boton .= $id."=enviar&";
	}
	return $boton;
}

//FUNCION DONDE DEVUELVO LA TABLA PARA LAS PERSONAS QUE TIENEN QUE ASISTIR Y QUE FALTAN POR ASISTIR EL DIA DE HOY
function mostrarTabla($array){
	$tabla = "<table class='table table-striped'><tr><th>Clase</th><th>Alumno</th></tr>";
	foreach($array as $id => $elemento){
		$tabla .= "<tr><td>".$elemento[0]."</td><td>".$elemento[1]."</td></tr>";
	}
	$tabla .= "</table>";
	return $tabla;
}

//FUNCION DONDE DEVUELVO LA TABLA PARA LAS PERSONAS QUE HAN ASISTIDO EL DIA DE HOY
function mostrarTablaAsistencia($array){
	$tabla = "<table class='table table-striped'><tr><th>Clase</th><th>Alumno</th><th>Hora</th></tr>";
	foreach($array as $id => $valor){
		$tabla .= "<tr><td>".$valor[0]."</td><td>".$valor[2]."</td><td>".$valor[1]."</tr>";
	}
	$tabla .= "</table>";
	return $tabla;
}
?>