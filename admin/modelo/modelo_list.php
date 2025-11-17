<?php
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/db/db.php";
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
					fecha, clase_nombre
				ORDER BY
					Clase.nombre ASC;
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
					Etapa.nombre
				ORDER BY
					Etapa.nombre ASC;
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
					Etapa.nombre, Clase.nombre
				ORDER BY
					Etapa.nombre, Clase.nombre ASC;
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
		$obtenerInfo = $conexion->prepare("SELECT * FROM Clase INNER JOIN Etapa ON Clase.etapa_id = Etapa.id WHERE Etapa.nombre = :id ORDER BY Clase.nombre ASC;");
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
			    Persona.Nombre,
			    Persona.apellido1, 
			    Persona.apellido2,
			    fechas.fecha,
			    CASE
			        WHEN Registro.id IS NOT NULL THEN
			            CASE
			                WHEN fechas.dia_semana = 2 AND Asistencia.lunes = 1 THEN 'X'
			                WHEN fechas.dia_semana = 3 AND Asistencia.martes = 1 THEN 'X'
			                WHEN fechas.dia_semana = 4 AND Asistencia.miercoles = 1 THEN 'X'
			                WHEN fechas.dia_semana = 5 AND Asistencia.jueves = 1 THEN 'X'
			                WHEN fechas.dia_semana = 6 AND Asistencia.viernes = 1 THEN 'X'
			                ELSE 'NO ASISTE'
			            END
			        ELSE
			            CASE
			                WHEN fechas.dia_semana = 2 AND Asistencia.lunes = 1 THEN 'A'
			                WHEN fechas.dia_semana = 3 AND Asistencia.martes = 1 THEN 'A'
			                WHEN fechas.dia_semana = 4 AND Asistencia.miercoles = 1 THEN 'A'
			                WHEN fechas.dia_semana = 5 AND Asistencia.jueves = 1 THEN 'A'
			                WHEN fechas.dia_semana = 6 AND Asistencia.viernes = 1 THEN 'A'
			                ELSE 'NO ASISTE'
			            END
			    END AS Asistido
			FROM
			    Persona
			    INNER JOIN Clase ON Persona.clase_id = Clase.id
			    CROSS JOIN (
			        SELECT DISTINCT 
			        	fecha, DAYOFWEEK(fecha) AS dia_semana
			        FROM 
			        	Registro
			        WHERE 
			        	YEAR(fecha) = YEAR(:fecha)
			        	AND MONTH(fecha) = MONTH(:fecha)
			    ) AS fechas
			    LEFT JOIN Registro ON Persona.id = Registro.persona_id
			        AND Registro.fecha = fechas.fecha
			    LEFT JOIN Asistencia ON Persona.id = Asistencia.persona_id
			        AND Asistencia.anyo = YEAR(fechas.fecha)
			        AND Asistencia.mes = MONTH(fechas.fecha)
			WHERE
			    Clase.nombre = :clase
			ORDER BY
				Persona.Apellido1 ASC;
		");
		$obtenerInfo->bindParam(':clase', $clase);
		$obtenerInfo->bindParam(':fecha', $fecha);
		$obtenerInfo->execute();
		$informacion=$obtenerInfo->fetchAll();
		foreach($informacion as $row){
			$arrayNombre = [$row[0],$row[1],$row[2]];
			$nombre = join(" ",$arrayNombre);
			$array[$nombre][$row[3]] = array($row[4]);
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
			$array[] = $row[0];
		}
		return $array;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}
//FUNCION DONDE MUESTRO LA TABLA DEL LISTADO MENSUAL
/*function mostrarTabla($alumnos,$diasSemana,$clase){
    $tabla = "";
    if(!empty($diasSemana)){
    	$tabla .= "<table class='table table-striped' id='tablaClases'><thead><tr><th>$clase</th>";
        foreach($diasSemana as $dia){
        	$arrayDia = explode("-", $dia);
        	$soloDia = $arrayDia[2];
        	$tabla .= "<th>".$soloDia."</th>";
        }
        $tabla .= "<th>TOTAL ASISTENCIAS</th><th>TOTAL FALTAS</th></tr></thead><tbody>";
        foreach($alumnos as $nombrePersona => $fechas){
        	$totalAsistencias = 0;
        	$totalFaltas = 0;
        	$tabla .= "<tr><th>".$nombrePersona."</th>";
        	foreach($fechas as $fecha => $elementos){
        		if($elementos[0] == "NO ASISTE"){
        			$tabla .= "<td> </td>";
        		}elseif($elementos[0] == "X"){
        			$tabla .= "<td>".$elementos[0]."</td>";
        			$totalAsistencias += 1;
        		}else{
        			$tabla .= "<td><label style='color:red;'>".$elementos[0]."</label></td>";
        			$totalFaltas += 1;
        		}
        	}
        	$tabla .= "<td>".$totalAsistencias."</td>";
        	if($totalFaltas == 0){
        		$tabla .= "<td>".$totalFaltas."</td></tr>";
        	}else{
        		$tabla .= "<td><label style='color:red;'>".$totalFaltas."</label></td></tr>";
        	}
        }
        $tabla .= "</tbody></table>";
    }
    return $tabla;
}*/

function mostrarTabla($alumnos, $mes, $anio, $clase){
  $tabla = "<h3>$clase</h3><div class='cards-container'>";
  $numDias = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
  $hoy = date('Y-m-d');

  foreach($alumnos as $nombreAlumno => $fechas) {
    $totalAsistencias = 0;
    $totalFaltas = 0;
    
    $tabla .= "<div class='month-card card mb-3'>";
    $tabla .= "<div class='card-header'>".htmlspecialchars($nombreAlumno)."</div>";
    $tabla .= "<div class='card-body'>";
    
    // Cabecera de dias de la semana
    $tabla .= "<div class='weekday-grid'>
      <div class='weekday-header'>L</div>
      <div class='weekday-header'>M</div>
      <div class='weekday-header'>X</div>
      <div class='weekday-header'>J</div>
      <div class='weekday-header'>V</div>
      <div class='weekday-header'>S</div>
      <div class='weekday-header'>D</div>
    </div>";

    // Grid de días
    $tabla .= "<div class='week-grid'>";
    $firstW = (int)date('N', strtotime("$anio-$mes-01")); // 1=Lunes .. 7=Domingo
    $emptyBefore = $firstW-1;

    // Celdas vacías antes del primer día
    for ($i = 0; $i < $emptyBefore; $i++) {
      $tabla .= "<div class='day-cell day-empty'></div>";
    }

    // Días del mes
    for ($d = 1; $d <= $numDias; $d++) {
      $fechaStr = sprintf('%04d-%02d-%02d', $anio, $mes, $d);
      $dow = (int)date('N', strtotime($fechaStr));
      $isWeekend = ($dow>=6);
      $isFuture = ($fechaStr > $hoy);

      // Si el día aún no ha llegado ? vacío
      if ($isFuture) {
        $tabla .= "<div class='day-cell day-empty'>$d</div>";
        continue;
      }
      
      // Valor almacenado
      $estado = $fechas[$fechaStr][0] ?? '';
      
      // Fines de semana y Festivps: siempre gris
      if ($isWeekend || $estado == "") {
        $cls = "day-wk";
      }
      // Días laborales
      else {
        if ($estado == "X") {
          $cls = "day-ok";
          $totalAsistencias++;
        }
        elseif ($estado == "A") {
          $cls = "day-ko";
          $totalFaltas++;
        }
        elseif ($estado == "NO ASISTE") {
          $cls = "day-empty";
        }
        else {
          $cls = "day-ko";
          $totalFaltas++;
        }
      }

      $tabla .= "<div class='day-cell $cls'>$d</div>";
    }

    $tabla .= "</div>"; // week-grid
    
    // Totales
    $tabla .= "<div class='mt-2 text-end'>";
    $tabla .= "<span class='badge bg-success me-1'>Asistencias: $totalAsistencias</span>";
    $tabla .= "<span class='badge bg-danger'>Faltas: $totalFaltas</span>";
    $tabla .= "</div>";
    $tabla .= "</div></div>"; // card-body + card
  }
  $tabla .= "</div>"; // cards-container
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