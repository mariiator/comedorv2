<?php
require_once $_SERVER['DOCUMENT_ROOT']."/db/db.php";
//FUNCION PARA RECOGER LOS DATOS DE LOS ALUMNOS QUE FALTAN POR ASISTIR Y HAN ASISTIDO EN ESA CLASE Y SUS FOTOS
function obtenerAlumnos($clase){
	global $conexion;
	$foto;
	$nom_persona;
	$array;
	try {
		$obtenerInfo = $conexion->prepare("
			SELECT 
				Persona.id, 
				Persona.Nombre, 
				Persona.apellido1, 
				Persona.apellido2, 
				Foto.foto,
				CASE WHEN Registro.persona_id IS NOT NULL THEN 1 ELSE 0 END AS esta_en_registro
			FROM
			    Etapa
			    INNER JOIN Clase ON Etapa.id = Clase.etapa_id
			    INNER JOIN Persona ON Persona.clase_id = Clase.id
			    INNER JOIN Asistencia ON Persona.id = Asistencia.persona_id
			    INNER JOIN Foto ON Foto.persona_id = Persona.id
			    LEFT JOIN Registro ON Registro.persona_id = Persona.id AND DATE(Registro.fecha) = CURDATE()
			WHERE 
			    Asistencia.anyo = YEAR(CURRENT_DATE())
			    AND Asistencia.mes = MONTH(CURRENT_DATE())
			    AND Clase.nombre = :clase
			    AND (
			        CASE
			            WHEN DAYOFWEEK(CURRENT_DATE()) = 2 THEN Asistencia.lunes
			            WHEN DAYOFWEEK(CURRENT_DATE()) = 3 THEN Asistencia.martes
			            WHEN DAYOFWEEK(CURRENT_DATE()) = 4 THEN Asistencia.miercoles
			            WHEN DAYOFWEEK(CURRENT_DATE()) = 5 THEN Asistencia.jueves
			            WHEN DAYOFWEEK(CURRENT_DATE()) = 6 THEN Asistencia.viernes
			        END
			    ) = 1;
		");
		$obtenerInfo->bindParam(':clase', $clase);
		$obtenerInfo->execute();
		$informacion=$obtenerInfo->fetchAll();
		foreach($informacion as $row){
			$id_persona = $row[0];
			$nom_persona = array($row[1],$row[2],$row[3]);
			$foto = $row[4];
			$array[$foto] = array($id_persona, join(" ",$nom_persona),$row[5]);
		}
		if(!empty($array)){
			return $array;
		}
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}
//FUNCION PARA INDICAR LA PERSONA QUE HA ASISTIDO EL DIA DE HOY
function asistido($id){
	global $conexion;
	try {
		$insertar = $conexion->prepare("
			INSERT INTO Registro (persona_id,fecha,lugar,hora) VALUES (:persona,CURDATE(),'Comedor',CURTIME());
		");
		$insertar->bindParam(':persona', $id);
		$insertar->execute();
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}
//FUNCION PARA ANULAR LA PERSONA QUE HA ASISTIDO EL DIA DE HOY
function anularAlumno($id){
	global $conexion;
	try {
		$insertar = $conexion->prepare("
			DELETE FROM Registro WHERE persona_id = :persona AND DATE(fecha) = CURDATE();
		");
		$insertar->bindParam(':persona', $id);
		$insertar->execute();
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}
//FUNCION PARA INDICAR QUE HAN ASISTIDO TODA LA CLASE
function marcarTodo($clase){
	global $conexion;
	try {
		$array = obtenerAlumnos($clase);
		foreach($array as $row){
			$insertar = $conexion->prepare("
				INSERT INTO Registro (persona_id,fecha,lugar,hora) VALUES (:persona,CURDATE(),'Comedor',CURTIME());
			");
			$insertar->bindParam(':persona', $row[0]);
			$insertar->execute();
		}
		
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}
//FUNCION DONDE NOS DEVUELVE LAS TABLAS DONDE SE MUESTRAN LOS ALUMNOS QUE TIENEN QUE ASISTIR POR CLASE
function mostrarTablas($clase){
	$alumnos = obtenerAlumnos($clase);
	$tabla = "<div id='listadoA'>";
	$tabla2 = "";
	foreach($alumnos as $foto => $valores){
		if($valores[2] == "0"){
			$tabla .= '
			<div id="'.$valores[0].'" class="container-2 ausente" onclick="javascript:doclick(\'/user/controlador/registrar.php\',\'#'.$valores[0].'\');">
				<a class="thumbnail" >
				<div class="image-2">
					<div class="image-overlap">
	    				<img class="alumno" id="'.$valores[0].'" src="data:image/png;base64,'.$foto.'" height="100%" onerror="imgError(this);">
					</div>
					<div class="bottom-overlap-2 transparent-back">
						<small>'.$valores[1].'</small>
					</div>
				</div>
				</a>
			</div>';
		}elseif($valores[2] == "1"){
			$tabla2 .= '
			<div id="'.$valores[0].'" class="container-2 asistente" onclick="javascript:doclick(\'/user/controlador/anular.php\',\'#'.$valores[0].'\');">
				<a class="thumbnail" >
				<div class="image-2">
					<div class="image-overlap-2">
	    				<img class="alumno" id="'.$valores[0].'" src="data:image/png;base64,'.$foto.'" height="100%" onerror="imgError(this);">
					</div>
					<div class="bottom-overlap-2 transparent-back">
						<small>'.$valores[1].'</small>
					</div>
					<div class="right-overlap transparent-back">
						<p>ANULAR</p>
					</div>
				</div>
				</a>
			</div>';
		}
	}
	$tabla .= "</div>
				<div id='listadoZ'>";
	$tabla .= '
			<div class="container-2" onclick="javascript:clickAll();" id="marcarTodo">
				<a class="thumbnail">
					<div class="image-2">
						<div class="image-overlap">
							<img src="/css/clase.png" height="100%">
						</div>
						<div class="bottom-overlap-2 transparent-back">
							<b>MARCAR TODOS</b>
						</div>
					</div>
				</a>
			</div>';
	if($tabla2 != ''){
		$tabla .= $tabla2;
	}
	$tabla .= "</div>";
	return $tabla;
}
?>