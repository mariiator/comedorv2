<?php
require_once $_SERVER['DOCUMENT_ROOT']."/db/db.php";
//FUNCION DONDE REALIZA UNA CONSULTA SEGUN LA COOKIE Y NOS DEVUELVE EL VALOR DEL ARRAY
function consultaCookie($cookie,$fecha){
	global $conexion;
	$query;
	$array;
	if($cookie == "Listado Mensual"){ //SI EL VALOR DE LA COOKIE ES IGUAL A LISTADO MENSUAL NOS REALIZARA LA SIGUIENTE CONSULTA
		$query = "SELECT * FROM Etapa;";
		try {
			$obtenerInfo = $conexion->prepare($query);
			$obtenerInfo->execute();
			$informacion=$obtenerInfo->fetchAll();
			foreach($informacion as $row){
				$clave = $row[0];
				$array[$clave] = $row[1];
			}
		} catch (PDOException $ex) {
			echo $ex->getMessage();
			return false;
		}
	}elseif($cookie == "Resumen Mensual"){ //SI EL VALOR DE LA COOKIE ES IGUAL A RESUMEN MENSUAL NOS REALIZARA LA SIGUIENTE CONSULTA
		$query = "SELECT * FROM Etapa;";
		$array;
		try {
			$arrayClase;
			$obtenerInfo = $conexion->prepare($query);
			$obtenerInfo->execute();
			$informacion=$obtenerInfo->fetchAll();
			foreach($informacion as $row){
				$id = $row[0];
				$etapa = "
				SELECT
					COUNT(*) AS total,
					DATE(Registro.fecha) AS fecha,
					Clase.nombre AS clase_nombre
				FROM 
					Etapa
					INNER JOIN Clase ON Etapa.id = Clase.etapa_id
					INNER JOIN Persona ON Clase.id = Persona.clase_id
					INNER JOIN Registro ON Persona.id = Registro.persona_id
				WHERE
					Etapa.id = :id
					AND YEAR(Registro.fecha) = YEAR(:fecha)
					AND MONTH(Registro.fecha) = MONTH(:fecha)
				GROUP BY
					fecha, clase_nombre;
				";
				$obtenerInfo2 = $conexion->prepare($etapa);
				$obtenerInfo2->bindParam(':id', $id);
				$obtenerInfo2->bindParam(':fecha', $fecha);
				$obtenerInfo2->execute();
				$informacion2=$obtenerInfo2->fetchAll();
				$array[$row[1]] = $informacion2;
			}
		} catch (PDOException $ex) {
			echo $ex->getMessage();
			return false;
		}
	}elseif($cookie == "Resumen Asistencias"){ //SI EL VALOR DE LA COOKIE ES IGUAL A RESUMEN ASISTENCIAS NOS REALIZARA LA SIGUIENTE CONSULTA
		$arrayClases = array();
		$arrayEtapas = array();
		$etapas = "
				SELECT
					Etapa.nombre AS NombreEtapa,
					SUM(CASE WHEN Asistencia.lunes = 1 THEN 1 ELSE 0 END) AS Lunes,
					SUM(CASE WHEN Asistencia.martes = 1 THEN 1 ELSE 0 END) AS Martes,
					SUM(CASE WHEN Asistencia.miercoles = 1 THEN 1 ELSE 0 END) AS Miercoles,
					SUM(CASE WHEN Asistencia.jueves = 1 THEN 1 ELSE 0 END) AS Jueves,
					SUM(CASE WHEN Asistencia.viernes = 1 THEN 1 ELSE 0 END) AS Viernes
				FROM Etapa
					INNER JOIN Clase ON Etapa.id = Clase.etapa_id
					INNER JOIN Persona ON Persona.clase_id = Clase.id
					INNER JOIN Asistencia ON Persona.id = Asistencia.persona_id
				WHERE 
					Asistencia.anyo = YEAR(:fecha)
					AND Asistencia.mes = MONTH(:fecha)
				GROUP BY
					Etapa.nombre;
				";
		$clases = "
				SELECT
					Etapa.nombre AS NombreEtapa,
					Clase.nombre AS NombreClase,
					SUM(CASE WHEN Asistencia.lunes = 1 THEN 1 ELSE 0 END) AS Lunes,
					SUM(CASE WHEN Asistencia.martes = 1 THEN 1 ELSE 0 END) AS Martes,
					SUM(CASE WHEN Asistencia.miercoles = 1 THEN 1 ELSE 0 END) AS Miercoles,
					SUM(CASE WHEN Asistencia.jueves = 1 THEN 1 ELSE 0 END) AS Jueves,
					SUM(CASE WHEN Asistencia.viernes = 1 THEN 1 ELSE 0 END) AS Viernes
				FROM Etapa
					INNER JOIN Clase ON Etapa.id = Clase.etapa_id
					INNER JOIN Persona ON Persona.clase_id = Clase.id
					INNER JOIN Asistencia ON Persona.id = Asistencia.persona_id
				WHERE 
					Asistencia.anyo = YEAR(:fecha)
					AND Asistencia.mes = MONTH(:fecha)
				GROUP BY
					Etapa.nombre, Clase.nombre;
				";
		try {
			$obtenerInfo = $conexion->prepare($etapas);
			$obtenerInfo->bindParam(':fecha', $fecha);
			$obtenerInfo->execute();
			$informacion=$obtenerInfo->fetchAll();
			foreach($informacion as $row){
				$arrayEtapas[] = $row;
			}
			$obtenerInfo2 = $conexion->prepare($clases);
			$obtenerInfo2->bindParam(':fecha', $fecha);
			$obtenerInfo2->execute();
			$informacion2=$obtenerInfo2->fetchAll();
			foreach($informacion2 as $row){
				$arrayClases[] = $row;
			}
			$array = [$arrayEtapas, $arrayClases];
		} catch (PDOException $ex) {
			echo $ex->getMessage();
			return false;
		}
	}
	return $array;
}

//FUNCION DONDE REALIZO UNA SENTENCIA DONDE RECOGE EL VALOR DE LAS CLASES
function obtenerClases($etapa) {
	global $conexion;
	$array;
	$id_clase;
	$nom_clase;
	try {
		$obtenerInfo = $conexion->prepare("SELECT * FROM Clase INNER JOIN Etapa ON Clase.etapa_id = Etapa.id WHERE Etapa.nombre = :id;");
		$obtenerInfo->bindParam(':id', $etapa);
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
//FUNCION DONDE REALIZO UNA SENTENCIA DONDE RECOGE EL VALOR DE LOS ALUMNOS
function obtenerAlumnos($clase,$fecha){
	global $conexion;
	$array = array();
	try {
		$obtenerInfo = $conexion->prepare("
			SELECT 
				Persona.id, 
				Persona.Nombre, 
				Persona.apellido1, 
				Persona.apellido2 
			FROM 
				Persona 
				INNER JOIN Clase ON Persona.clase_id = Clase.id
				INNER JOIN Asistencia ON Persona.id = Asistencia.persona_id
			WHERE
				Clase.nombre = :clase
				AND Asistencia.anyo = YEAR(:fecha)
				AND Asistencia.mes = MONTH(:fecha)
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
		$obtenerInfo->bindParam(':fecha', $fecha);
		$obtenerInfo->execute();
		$informacion=$obtenerInfo->fetchAll();
		foreach($informacion as $row){
			$nombre = [$row[1],$row[2],$row[3]];
			$array[$row[0]] = join(" ",$nombre);
		}
		return $array;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}
//FUNCION DONDE REALIZO UNA SENTENCIA DONDE RECOGE EL VALOR DE LAS ASISTENCIAS DE LA CLASE
function obtenerAsistencias($clase,$fecha){
	global $conexion;
	$fecha .= "-01";
	$array = array();
	try {
		$obtenerInfo = $conexion->prepare("
			SELECT DISTINCT 
				Persona.id,
				Registro.fecha
			FROM
			    Etapa
			    INNER JOIN Clase ON Etapa.id = Clase.etapa_id
			    INNER JOIN Persona ON Persona.clase_id = Clase.id
			    INNER JOIN Registro ON Persona.id = Registro.persona_id
			    INNER JOIN Asistencia ON Persona.id = Asistencia.persona_id
			WHERE 
			    YEAR(Registro.fecha) = YEAR(:fecha)
			    AND MONTH(Registro.fecha) = MONTH(:fecha)
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
		$obtenerInfo->bindParam(':fecha', $fecha);
		$obtenerInfo->execute();
		$informacion=$obtenerInfo->fetchAll();
		foreach($informacion as $row){
			$array[] = [$row[0],$row[1]];
		}
		return $array;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}
//FUNCION DONDE REALIZO UNA SENTENCIA DONDE RECOGE LOS DIAS DONDE HAN ASISTIDO ALGUN ALUMNO
function obtenerDiasEntreSemana($mes, $anio) {
	global $conexion;
	$array = array();
	try {
		$obtenerInfo = $conexion->prepare("
			SELECT DISTINCT
				fecha
			FROM 
				Registro 
			WHERE
				YEAR(fecha) = :anio
				AND MONTH(fecha) = :mes
		");
		$obtenerInfo->bindParam(':anio', $anio);
		$obtenerInfo->bindParam(':mes', $mes);
		$obtenerInfo->execute();
		$informacion=$obtenerInfo->fetchAll();
		foreach($informacion as $row){
			$array[$row[0]] = $row[0];
		}
		return $array;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}
//FUNCION DONDE MUESTRO LA TABLA DEL LISTADO MENSUAL
function mostrarTabla($arrayListado){
	$tabla = "";
	if(!empty($arrayListado) && count($arrayListado) == 3){
		$registroAsistencias = array();

		foreach ($arrayListado[0] as $id => $nombre) {
		    $registroAsistencias[$id] = array(
		        'nombre' => $nombre,
		        'asistencia' => array_fill_keys(array_keys($arrayListado[2]), 'A'),
		        'totalAsistido' => 0,
		    );
		}
		foreach ($arrayListado[1] as $asistencia) {
		    $fecha = strtotime($asistencia[1]);
		    $fechaFormateada = date('Y-m-d', $fecha);
		    if (array_key_exists($fechaFormateada, $arrayListado[2])) {
		        $registroAsistencias[$asistencia[0]]['asistencia'][$fechaFormateada] = 'X';
		    }
		}
		$tabla .= "<table class='table table-striped' id='tablaClases'>";
		$tabla .= "<thead><tr><th>Nombre del Alumno</th>";
		foreach ($arrayListado[2] as $dia) {
			$fechaDia = explode("-", $dia);
		    $tabla.= "<th>".$fechaDia[2]."</th>";
		}
		$tabla .= "<th>Total Asistido</th></tr></thead><tbody>";

		// Contenido de la tabla
		foreach ($registroAsistencias as $alumno) {
		    $tabla .= "<tr><th>{$alumno['nombre']}</th>";
		    foreach ($alumno['asistencia'] as $asistencia) {
		        if ($asistencia === 'X') {
		        	$tabla .= "<td>$asistencia</td>";
		            $alumno['totalAsistido']++;
		        }else{
		        	$tabla .= "<td><span style='color: red;'>A</span></td>";
		        }
		    }
		    $tabla .= "<td><span style='font-weight: bold;'>{$alumno['totalAsistido']}</span></td></tr>";
		}
		$tabla .= "</tbody></table>";
	}
	return $tabla;
}
//FUNCION DONDE MUESTRO LA TABLA DEL RESUMEN MENSUAL
function mostrarTabla2($array){
	$tabla = "";
	if(!empty($array)){
		foreach($array as $arr => $elementos){
			if(!empty($elementos)){
				$tabla .= "<table class='table table-striped' id='tablaClases'><thead><tr>";
				$clases = array();
				$fechas = array();
				$sumas_filas = array();
				$sumas_columnas = array();
				$suma_total_columnas = 0;
				foreach($elementos as $elemento => $elemento2){
					$clases[] = $elemento2[2];
					$fechas[] = $elemento2[1];
				}
				$clasesSinRepetir = array_unique($clases);
				$clasesSinRepetir = array_values($clasesSinRepetir);
				$fechasSinRepetir = array_unique($fechas);
				$fechasSinRepetir = array_values($fechasSinRepetir);
				sort($fechasSinRepetir);

				$tabla .= "<th>FECHA</th>";
				foreach($clasesSinRepetir as $nombreClase){
					$tabla .= "<th>".$nombreClase."</th>";
					$sumas_columnas[$nombreClase] = 0;
				}
				$tabla .= "<th>TOTAL</th></tr></thead><tbody>";
				foreach($fechasSinRepetir as $fecha){
					$tabla .= "<tr>";
					$tabla .= "<th>".$fecha."</th>";
					$total = 0;
					$clases = 0;
					$columnas = 0;
					foreach($clasesSinRepetir as $nombreClase){
						$clases += 1;
						foreach($elementos as $elemento => $elemento2){
							if($elemento2[1] == $fecha && $elemento2[2] == $nombreClase){
								$tabla .= "<td>".$elemento2[0]."</td>";
								$total += $elemento2[0];
								$columnas += 1;
								$sumas_columnas[$nombreClase] += $elemento2[0];
							}
						}
						if($clases != $columnas){
							$tabla .= "<td> 0 </td>";
							$columnas += 1;
						}
					}
					$tabla .= "<td style='background-color:#D0FA58;'>".$total."</td>";
					$sumas_filas[] = $total;
					$tabla .= "</tr>";
				}
				$tabla .= "<th>TOTAL</th>";
				foreach($sumas_columnas as $suma){
					$tabla .= "<td>".$suma."</td>";
					$suma_total_columnas += $suma;
				}
				$suma_total_filas = array_sum($sumas_filas);
				$suma_total = $suma_total_filas + $suma_total_columnas;
				$tabla .= "<td style='background-color:#D0FA58;'>".$suma_total."</td>";
				$tabla .= '</tbody></table>';
			}
		}
	}
	return $tabla;
}
//FUNCION DONDE MUESTRO LA TABLA DEL LISTADO ASISTENCIAS
function mostrarTabla3($array){
	$tabla = "";
		$tablaEtapas = $array[0];
		$tablaClases = $array[1];
		$tabla .= "<table id='tablaClases' class='table table-striped'><tr><th>ETAPA</th><th>Lunes</th><th>Martes</th><th>Miercoles</th><th>Jueves</th><th>Viernes</th></tr>";
		foreach($tablaEtapas as $elemento){
			$nombre = $elemento[0];
		    $lunes = $elemento['Lunes'];
		    $martes = $elemento['Martes'];
		    $miercoles = $elemento['Miercoles'];
		    $jueves = $elemento['Jueves'];
		    $viernes = $elemento['Viernes'];
			$tabla .= "<tr><td>".$nombre."</td><td>".$lunes."</td><td>".$martes."</td><td>".$miercoles."</td><td>".$jueves."</td><td>".$viernes."</td></tr>";
		}
		$tabla .= "</table>";
		$tabla .= "<table id='tablaClases' class='table table-striped'><tr><th>ETAPA</th><th>CLASE</th><th>Lunes</th><th>Martes</th><th>Miercoles</th><th>Jueves</th><th>Viernes</th></tr>";
		foreach($tablaClases as $elemento){
			$nombreEtapa = $elemento['NombreEtapa'];
		    $nombreClase = $elemento['NombreClase'];
		    $lunesClase = $elemento['Lunes'];
		    $martesClase = $elemento['Martes'];
		    $miercolesClase = $elemento['Miercoles'];
		    $juevesClase = $elemento['Jueves'];
		    $viernesClase = $elemento['Viernes'];
			$tabla .= "<tr><td>$elemento[0]</td><td>$elemento[1]</td><td>$elemento[2]</td><td>$elemento[3]</td><td>$elemento[4]</td><td>$elemento[5]</td><td>$elemento[6]</td></tr>";
		}
		$tabla .= "</table>";
	return $tabla;
}
?>