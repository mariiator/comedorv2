<?php
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/db/db.php";
//FUNCION DONDE REALIZO UNA SENTENCIA QUE ME DEVUELVE EL VALOR DE LAS ETAPAS
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
//FUNCION DONDE REALIZO UN UPDATE DONDE DOY DE ALTA LA ASISTENCIA DE UN ALUMNO Y DE ESE MES
function darAltaAsistencia($letras){
    global $conexion;
    if (!preg_match('/^(\d+)([a-z]+)(\d{1,2})$/', $letras, $matches)) {
        echo "Formato incorrecto de letras:" . print_r($matches) ;
        return false;
    }
    $id_persona = $matches[1];
    $diaSemana = $matches[2];
    $mes = $matches[3];
    try {
        $query = "UPDATE Asistencia
                  SET $diaSemana = 1
                  WHERE persona_id = :id_persona
                  AND anyo = YEAR(CURRENT_DATE())
                  AND mes = :mes";
        $obtenerInfo = $conexion->prepare($query);
        $obtenerInfo->bindParam(':id_persona', $id_persona);
        $obtenerInfo->bindParam(':mes', $mes);
        $obtenerInfo->execute();
        return true;
    } catch (PDOException $ex) {
        echo "Error en la consulta: " . $ex->getMessage();
        return false;
    }
}
//FUNCION DONDE REALIZO UN UPDATE DONDE DOY DE BAJA LA ASISTENCIA DE UN ALUMNO Y DE ESE MES
function darBajaAsistencia($letras){
    //echo "-". $letras. "-";
	global $conexion; 
    if (!preg_match('/^(\d+)([a-z]+)(\d{1,2})$/', $letras, $matches)) {
        echo "Formato incorrecto de letras:" . print_r($matches) ;
        return false;
    }
    $id_persona = $matches[1];
    $diaSemana = $matches[2];
    $mes = $matches[3];
    try {
        $query = "UPDATE Asistencia
                  SET $diaSemana = 0
                  WHERE persona_id = :id_persona
                  AND anyo = YEAR(CURRENT_DATE())
                  AND mes = :mes";
        $obtenerInfo = $conexion->prepare($query);
        $obtenerInfo->bindParam(':id_persona', $id_persona);
        $obtenerInfo->bindParam(':mes', $mes);
		//alert($query);
        $obtenerInfo->execute();
        return true;
    } catch (PDOException $ex) {
        echo "Error en la consulta: " . $ex->getMessage();
        return false;
    }
}
//FUNCION DONDE OBTENGO SI HAY VALORES EN LA TABLA ASISTENCIA EN ESE MES
function obtenerMes($mes) {
	global $conexion;
	$date = new DateTime();
	$mesActual = $date->format("m");
	$anyo = $date->format('Y');
	if ($mes == $mesActual || ($mes != 1 && $mes != $mesActual)) {
		$query = "SELECT * FROM Asistencia WHERE mes = :mes AND anyo = :anyo";
	} elseif ($mes == 1 && $mes != $mesActual) {
		$anyo += 1;
		$query = "SELECT * FROM Asistencia WHERE mes = :mes AND anyo = :anyo";
	}
	try {
		$obtenerInfo = $conexion->prepare($query);
		$obtenerInfo->bindParam(':mes', $mes);
		$obtenerInfo->bindParam(':anyo', $anyo);
		$obtenerInfo->execute();
		$informacion = $obtenerInfo->fetchAll();
		return !empty($informacion);
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}

//FUNCION DONDE REALIZO UNA SENTENCIA DONDE RECOGE LAS CLASES DE ESA ETAPA
function obtenerClases($etapa) {
	global $conexion;
	$array;
	$id_clase;
	$nom_clase;
	try {
		$obtenerInfo = $conexion->prepare("
			SELECT 
				* 
			FROM 
				Clase 
				INNER JOIN Etapa ON Clase.etapa_id = Etapa.id 
			WHERE 
				Etapa.nombre = :nombre
			ORDER BY
				Clase.nombre ASC;
		");
		$obtenerInfo->bindParam(':nombre', $etapa);
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
//FUNCION DONDE REALIZO UNA SENTENCIA QUE ME DEVUELVE LOS ALUMNOS DE ESA CLASE
function obtenerAlumnos($clase, $cookieMes) {
    global $conexion;
    $array = array();
    $date = new DateTime();
    $mesActual = $date->format('m');

    if ($cookieMes != $mesActual) {
        $date->modify('+1 month');
    }

    $fecha = $date->format('Y-m-d');

    try {
        $query = "
            SELECT 
            	Persona.id,
                CONCAT(Persona.Nombre, ' ', Persona.apellido1, ' ', Persona.apellido2) AS nombre_completo,
                Foto.foto,
                Asistencia.lunes,
                Asistencia.martes,
                Asistencia.miercoles,
                Asistencia.jueves,
                Asistencia.viernes,
                Asistencia.lunes + Asistencia.martes + Asistencia.miercoles + Asistencia.jueves + Asistencia.viernes AS Total,
                (
                    SELECT SUM(lunes + martes + miercoles + jueves + viernes)
                    FROM Asistencia
                    WHERE Asistencia.anyo = YEAR(DATE_SUB(:fecha, INTERVAL 1 MONTH))
                    AND Asistencia.mes = MONTH(DATE_SUB(:fecha, INTERVAL 1 MONTH))
                    AND Asistencia.persona_id = Persona.id
                ) AS TotalMesAnterior
            FROM
                Etapa
                INNER JOIN Clase ON Etapa.id = Clase.etapa_id
                INNER JOIN Persona ON Persona.clase_id = Clase.id
                INNER JOIN Asistencia ON Persona.id = Asistencia.persona_id
                LEFT JOIN Foto ON Foto.persona_id = Persona.id
            WHERE 
                Asistencia.anyo = YEAR(:fecha)
                AND Asistencia.mes = MONTH(:fecha)
                AND Clase.nombre = :clase
            ORDER BY
            	Persona.apellido1, Persona.apellido2 ASC;
        ";

        $obtenerInfo = $conexion->prepare($query);
        $obtenerInfo->bindParam(':clase', $clase);
        $obtenerInfo->bindParam(':fecha', $fecha);
        $obtenerInfo->execute();
        $informacion = $obtenerInfo->fetchAll();

        foreach ($informacion as $row) {
            $id_persona = $row[0];
            $nom_persona = $row[1];
            $foto = $row[2];
            $checkbox = array($row['lunes'], $row['martes'], $row['miercoles'], $row['jueves'], $row['viernes']);
            $array[$id_persona] = array($foto, $nom_persona, $checkbox, $row['Total'], $row['TotalMesAnterior']);
        }
        return $array;
    } catch (PDOException $ex) {
        echo $ex->getMessage();
        return false;
    }
}
//FUNCION DONDE REALIZO UN INSERT DEL MES ANTERIOR AL MES ACTUAL
function copiarMes(){
    global $conexion;
    $insert = "INSERT INTO Asistencia (persona_id, anyo, mes, lunes, martes, miercoles, jueves, viernes) VALUES ";
    try {
   		$conexion->beginTransaction();
		$consulta = $conexion->prepare("
            SELECT DISTINCT persona_id,
                anyo,
                mes,
                lunes,
                martes,
                miercoles,
                jueves,
                viernes
            FROM
                Asistencia
            WHERE
                anyo = YEAR(CURRENT_DATE())
                AND mes = MONTH(CURRENT_DATE())
        ");
		
        $consulta->execute();
        $informacion = $consulta->fetchAll();
		if (count($informacion) == 0) {
				$consulta = $conexion->prepare("
					SELECT DISTINCT persona_id,
						anyo,
						mes,
						lunes,
						martes,
						miercoles,
						jueves,
						viernes
					FROM
						Asistencia
					WHERE
						anyo = YEAR(CURRENT_DATE())
						AND mes = MONTH(CURRENT_DATE())-1
				");
				
				$consulta->execute();
				$informacion = $consulta->fetchAll();	
		}
		var_dump($informacion);
		
		foreach($informacion as $row){
            $id_persona = $row[0];
            $anyo = ($row[2] == 12) ? $row[1] + 1 : $row[1];
            $mes = ($row[2] == 12) ? 1 : $row[2] + 1;
            $insert .= "('$id_persona','$anyo','$mes','$row[3]','$row[4]','$row[5]','$row[6]','$row[7]'),";
        }
        $query = substr($insert, 0,-1);
        var_dump($query);
		$hacerinsert = $conexion->prepare($query);
        $hacerinsert->execute();
		$conexion->commit();
		
		echo ($query);
        return true;
    }catch (PDOException $ex) {
   		$conexion->rollBack();
        echo $ex->getMessage();
        return false;
    }
}


//FUNCION DONDE OBTENGO EL VALOR DE LA TABLA
function mostrarTabla($array,$mes){
	$tabla = "<div id='listadoA'>";
	$tabla2 = "";
	$diaSemana = array("lunes","martes","miercoles","jueves","viernes");
	foreach($array as $id_persona => $valores){
		$numero = 0;
		$tabla .= '<div id="'.$id_persona.'" class="card"><a class="thumbnail" ><div class="image"><div class="image-overlap"><img class="alumno" id="'.$id_persona.'" src="data:image/png;base64,'.$valores[0].'" height="100%" onerror="imgError(this);"></div><div class="bottom-overlap transparent-back"><small>'.$valores[1].'</small></div></div><div class="caption"><table class="noline" width="100%"><thead><tr><th></th><th>L</th><th>M</th><th>X</th><th>J</th><th>V</th><th>Todos</th></tr></thead><tbody><tr><td>A</td>';
		$id = $id_persona;
		foreach($valores[2] as $valor){
			if($valor == 1){
				$tabla .= "<td><input type='checkbox' id='".$id.$diaSemana[$numero].$mes."' class='form-check-input' onclick='javascript:saveChange(\"/admin/controlador/checkboxes.php\",\"$id$diaSemana[$numero]$mes\");' checked /></td>";
			}else{
				$tabla .= "<td><input type='checkbox' id='".$id.$diaSemana[$numero].$mes."' class='form-check-input' onclick='javascript:saveChange(\"/admin/controlador/checkboxes.php\",\"$id$diaSemana[$numero]$mes\");'/></td>";
			}
			$numero += 1;
		}
		$tabla .= "<td><input type='checkbox' class='glyphicon glyphicon-ok-circle' onclick='javascript:changeAll(\"$id\", \"$mes\");' /></td>";
		$tabla .= '</tr></tbody></table></div></a></div>';
	}
	$tabla .= "</div>";
	return $tabla;
}
function mostrarTablaAsistencias($array,$mes){
	//DECLARO UN ARRAY CON LOS NOMBRES DE LOS MESES EN ORDEN PARA QUE LUEGO SE VEA EN LA VISTA
	$nombreMeses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	$nombremes = $nombreMeses[$mes-1];
	$tabla = "<table class='table table-striped' id='asistenciasTabla'><thead><tr><th>Nombre</th><th>Lunes</th><th>Martes</th><th>Mi&eacute;rcoles</th><th>Jueves</th><th>Viernes</th><th>Total ($nombremes)</th><th>Total del Mes Anterior</th></tr></thead><tbody>";
	foreach($array as $id_persona => $valores){
		$numero = 0;
		$nombre_persona = $valores[1];
		$total = $valores[3];
		$totalMesAnterior = $valores[4];
		$tabla .= '<tr><td>'.$nombre_persona.'</td>';
		$id = $id_persona;
		foreach($valores[2] as $valor){
			if($valor == 1){
				$tabla .= "<td>X</td>";
			}else{
				$tabla .= "<td> </td>";
			}
		}
		$tabla .= '<td>'.$total.'</td>';
		if($total != $totalMesAnterior){
			$tabla .= '<td><div style="color:red;">'.$totalMesAnterior.'</div></td></tr>';
		}else{
			$tabla .= '<td>'.$totalMesAnterior.'</td></tr>';
		}
	}
	$tabla .= "</tbody></table>";
	return $tabla;
}
?>