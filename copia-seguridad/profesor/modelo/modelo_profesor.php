<?php
require_once $_SERVER['DOCUMENT_ROOT']."/db/db.php";
function obtenerCalendario($id,$mes){
	global $conexion;
	$array = array();
	try {
		$obtenerInfo = $conexion->prepare("SELECT Id_Profesor,Dia,Tipo_menu FROM Asistencias_profe WHERE Correo = :id AND MONTH(Dia) = MONTH(:mes);");
		$obtenerInfo->bindParam(':id', $id);
		$obtenerInfo->bindParam(':mes', $mes);
		$obtenerInfo->execute();
		$informacion=$obtenerInfo->fetchAll();
		if(!empty($informacion)){
			foreach($informacion as $row){
				$profesor = $row[0];
				$dia = $row[1];
				$menu = $row[2];
				$array[] = [$profesor,$dia,$menu];
			}
		}else{
			$array = $id;
		}
		return $array;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}
//FUNCION DONDE OBTENGO LOS DIAS ENTRE SEMANA DEL MES SELECCIONADO
function obtenerDiasEntreSemana($mes, $anio) {
	$primerDia = mktime(0, 0, 0, $mes, 1, $anio);
	$ultimoDia = mktime(0, 0, 0, $mes + 1, 0, $anio);
	$diasEntreSemana = array();
	for ($i = $primerDia; $i <= $ultimoDia; $i = strtotime('+1 day', $i)) {
		if (date('N', $i) <= 5) {
			$diasEntreSemana[] = date('Y-m-d', $i);
		}
	}
	return $diasEntreSemana;
}
//FUNCION DONDE REALIZO UN UPDATE DONDE DOY DE ALTA LA ASISTENCIA DE UN ALUMNO Y DE ESE MES
function darAltaAsistencia($letras){
    global $conexion;
	$array = explode("/", $letras);
	$id = $array[0];
	$fecha = $array[1];
	$rol = $array[2];
    try {
    	$query = "SELECT * FROM Asistencias_profe WHERE dia = :fecha AND Id_Profesor = :id";
        $obtenerInfo = $conexion->prepare($query);
        $obtenerInfo->bindParam(':fecha', $fecha);
        $obtenerInfo->bindParam(':id', $id);
        $obtenerInfo->execute();
        $informacion=$obtenerInfo->fetchAll();
    	if(empty($informacion)){
        	$sql = "INSERT INTO Asistencias_profe (Dia,Tipo_menu,Id_Profesor) VALUES(:fecha,:rol,:id)";
	        $insert = $conexion->prepare($sql);
	        $insert->bindParam(':fecha', $fecha);
	        $insert->bindParam(':rol', $rol);
	        $insert->bindParam(':id', $id);
	        $insert->execute();
        }else{
        	$sql = "UPDATE Asistencias_profe SET Tipo_menu = :rol WHERE Id_Profesor = :id AND Dia = :fecha";
	        $update = $conexion->prepare($sql);
	        $update->bindParam(':fecha', $fecha);
	        $update->bindParam(':rol', $rol);
	        $update->bindParam(':id', $id);
	        $update->execute();
        }
        return true;
    } catch (PDOException $ex) {
        echo "Error en la consulta: " . $ex->getMessage();
        return false;
    }
}
//FUNCION DONDE REALIZO UN UPDATE DONDE DOY DE BAJA LA ASISTENCIA DE UN ALUMNO Y DE ESE MES
function darBajaAsistencia($letras){
    global $conexion;
    $array = explode("/", $letras);
	$id = $array[0];
	$fecha = $array[1];
	$rol = $array[2];
    try {
    	$query = "SELECT * FROM Asistencias_profe WHERE dia = :fecha AND Id_Profesor = :id";
        $obtenerInfo = $conexion->prepare($query);
        $obtenerInfo->bindParam(':fecha', $fecha);
        $obtenerInfo->bindParam(':id', $id);
        $obtenerInfo->execute();
        $informacion=$obtenerInfo->fetchAll();
        if(!empty($informacion)){
        	$sql = "DELETE FROM Asistencias_profe WHERE Id_Profesor = :id AND Dia = :fecha AND Tipo_menu = :rol";
	        $delete = $conexion->prepare($sql);
	        $delete->bindParam(':fecha', $fecha);
	        $delete->bindParam(':rol', $rol);
	        $delete->bindParam(':id', $id);
	        $delete->execute();
        }
        return true;
    } catch (PDOException $ex) {
        echo "Error en la consulta: " . $ex->getMessage();
        return false;
    }
}
//FUNCION DONDE REALIZO UNA SENTENCIA PARA RECOGER EL PDF DEL MENU DEL MES
function obtenerPdf($mes,$anyo){
	global $conexion;
    try {
        $obtenerInfo = $conexion->prepare("SELECT Menu FROM Menus WHERE Mes = :mes AND Anyo = :anyo;");
        $obtenerInfo->bindParam(':mes', $mes);
        $obtenerInfo->bindParam(':anyo', $anyo);
        $obtenerInfo->execute();
        $pdf = $obtenerInfo->fetchColumn();
        return $pdf;
    } catch (PDOException $ex) {
        echo "Error en la consulta: " . $ex->getMessage();
        return false;
    }
}
//FUNCION DONDE GENERO LA TABLA DONDE SE MUESTRA EL CALENDARIO
function mostrarCalendario($calendario, $diasEntreSemana, $mes, $fecha) {
    $nombreMeses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $arrayCalendar = explode("-",$diasEntreSemana[0]);
    $yearCalendar = $arrayCalendar[0];
    $tabla = "<table id='calendario'><thead><tr><td colspan='5'><h2>".$nombreMeses[$mes - 1]." - ".$yearCalendar."</h2></td></tr><tr><th><h3>Lunes</h3></th><th><h3>Martes</h3></th><th><h3>Miercoles</h3></th><th><h3>Jueves</h3></th><th><h3>Viernes</h3></th></tr></thead><tbody>";
    $semana = 0;
    foreach ($diasEntreSemana as $dia) {
    	$explodeFecha = explode("-",$dia);
    	$year = $explodeFecha[0];
    	$month = $explodeFecha[1];
    	$day = $explodeFecha[2];
        $encontrado = false;
        if(is_array($calendario)){
        	foreach ($calendario as $elemento) {
	            $idProfesor = $elemento[0];
	            $diaProfesor = $elemento[1];
	            $tipoMenu = $elemento[2];
	            if ($diaProfesor == $dia) {
	                $encontrado = true;
	                if ($fecha >= $dia) {
	                    $tabla .= "<td><div id='diaCalendario'><h4>".$day."</h4><p style='color:green; text-transform: uppercase;'>".$tipoMenu."</p></div></td>";
	                } else {
	                	if($tipoMenu == "alumno"){
	                		$tabla .= "
	                				<td>
	                					<div id='diaCalendario'>
	                						<h4>".$day."</h4>
	                						<input type='checkbox' class='form-check-input' name='tipoMenu".$dia."' value='alumno' onclick='javascript:deseleccionarRadio(this); saveChange(\"/profesor/controlador/checkbox.php\",\"".$idProfesor."/".$dia."/alumno\");' id='".$idProfesor."/".$dia."/alumno' checked/>
	                						<label for='".$idProfesor."/".$dia."/alumno'>Alumno</label>
	                						<br/>
	                						<input type='checkbox' class='form-check-input' name='tipoMenu".$dia."' value='profesor' onclick='javascript:deseleccionarRadio(this); saveChange(\"/profesor/controlador/checkbox.php\",\"".$idProfesor."/".$dia."/profesor\");' id='".$idProfesor."/".$dia."/profesor' />
	                						<label for='".$idProfesor."/".$dia."/profesor'>Profesor</label>
	                					</div>
	                				</td>";
	                	}else{
	                		$tabla .= "
	                				<td>
	                					<div id='diaCalendario'>
	                						<h4>".$day."</h4>
	                						<input type='checkbox' class='form-check-input' name='tipoMenu".$dia."' value='alumno' onclick='javascript:deseleccionarRadio(this); saveChange(\"/profesor/controlador/checkbox.php\",\"".$idProfesor."/".$dia."/alumno\");' id='".$idProfesor."/".$dia."/alumno'/>
	                						<label for='".$idProfesor."/".$dia."/alumno'>Alumno</label>
	                						<br/>
	                						<input type='checkbox' class='form-check-input' name='tipoMenu".$dia."' value='profesor' onclick='javascript:deseleccionarRadio(this); saveChange(\"/profesor/controlador/checkbox.php\",\"".$idProfesor."/".$dia."/profesor\");' id='".$idProfesor."/".$dia."/profesor' checked/>
	                						<label for='".$idProfesor."/".$dia."/profesor'>Profesor</label>
	                					</div>
	                				</td>";
	                	}
	                }
	                break;
	            }
	       	}
	    }else{
			$idProfesor = $calendario;
			if ($fecha >= $dia) {
				$tabla .= "<td><div id='diaCalendario'><h4>".$day."</h4><p style='color:red;'>No Modificable</p></div></td>";
			} else {
				$tabla .= "
						<td>
        					<div id='diaCalendario'>
        						<h4>".$day."</h4>
        						<input type='checkbox' class='form-check-input' name='tipoMenu".$dia."' value='alumno' onclick='javascript:deseleccionarRadio(this); saveChange(\"/profesor/controlador/checkbox.php\",\"".$idProfesor."/".$dia."/alumno\");' id='".$idProfesor."/".$dia."/alumno'/>
        						<label for='".$idProfesor."/".$dia."/alumno'>Alumno</label>
        						<br/>
        						<input type='checkbox' class='form-check-input' name='tipoMenu".$dia."' value='profesor' onclick='javascript:deseleccionarRadio(this); saveChange(\"/profesor/controlador/checkbox.php\",\"".$idProfesor."/".$dia."/profesor\");' id='".$idProfesor."/".$dia."/profesor' />
        						<label for='".$idProfesor."/".$dia."/profesor'>Profesor</label>
        					</div>
        				</td>";
			}
	    }
	    if (!$encontrado && is_array($calendario)) {
			if ($fecha >= $dia) {
				$tabla .= "<td><div id='diaCalendario'><h4>".$day."</h4><p style='color:red;'>No Modificable</p></div></td>";
			} else {
				$tabla .= "
						<td>
        					<div id='diaCalendario'>
        						<h4>".$day."</h4>
        						<input type='checkbox' class='form-check-input' name='tipoMenu".$dia."' value='alumno' onclick='javascript:deseleccionarRadio(this); saveChange(\"/profesor/controlador/checkbox.php\",\"".$idProfesor."/".$dia."/alumno\");' id='".$idProfesor."/".$dia."/alumno'/>
        						<label for='".$idProfesor."/".$dia."/alumno'>Alumno</label>
        						<br/>
        						<input type='checkbox' class='form-check-input' name='tipoMenu".$dia."' value='profesor' onclick='javascript:deseleccionarRadio(this); saveChange(\"/profesor/controlador/checkbox.php\",\"".$idProfesor."/".$dia."/profesor\");' id='".$idProfesor."/".$dia."/profesor' />
        						<label for='".$idProfesor."/".$dia."/profesor'>Profesor</label>
        					</div>
        				</td>";
			}
		}
		if ($semana == 4) {
            $tabla .= "</tr>";
            $semana = 0;
        } else {
            $semana += 1;
        }
    }
    $tabla .= "</tbody></table>";
    return $tabla;
}
?>