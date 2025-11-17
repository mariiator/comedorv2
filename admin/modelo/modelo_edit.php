<?php
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/db/db.php";
//FUNCION DONDE REALIZA UNA CONSULTA SEGUN LA COOKIE
function obtenerConsulta($cookie,$datos){
	global $conexion;
	$array;
	$query;
	if($cookie == "Etapas"){ //SI EL VALOR DE LA COOKIE ES ETAPAS
		$query = "SELECT * FROM Etapa WHERE id = :datos;";
	}elseif($cookie == "Clases"){ //SI EL VALOR DE LA COOKIE ES CLASES
		$query = "SELECT * FROM Clase WHERE id = :datos;";
	}elseif($cookie == "Alumnos"){ //SI EL VALOR DE LA COOKIE ES ALUMNOS
		$query = "SELECT * FROM Persona WHERE id = :datos;";
	}elseif($cookie == "Profesores"){ //SI EL VALOR DE LA COOKIE ES ALUMNOS
		$query = "SELECT * FROM Profesores WHERE id = :datos;";
	}
	try {
		$obtenerInfo = $conexion->prepare($query);
		$obtenerInfo->bindParam(':datos', $datos);
		$obtenerInfo->execute();
		$informacion=$obtenerInfo->fetchAll();
		return $informacion;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}
//FUNCION DONDE OBTENGO EL VALOR DE LOS SELECT QUE SE TENDRAN QUE MOSTRAR
function obtenerSelect($tabla){
	global $conexion;
	$array;
	$query;
	if($tabla == "Clase"){
		$query = "SELECT Id,Nombre FROM Etapa";
	}else{
		$query = "SELECT Id,Nombre FROM Clase";
	}
	try {
		$obtenerInfo = $conexion->prepare($query);
		$obtenerInfo->execute();
		$informacion=$obtenerInfo->fetchAll();
		foreach($informacion as $row){
			$array[$row[0]] = $row[1];
		}
		asort($array);
		return $array;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}
//FUNCION DONDE REALIZO LA SENTENCIA UPDATE DEPENDIENDO DE LA COOKIE
function sentenciaUpdate($array,$cookie,$edit){
	global $conexion;
    $query; 
    try {
        if($cookie == "Etapas") {
            $query = "UPDATE Etapa SET nombre = :nombre WHERE id = :edit";
        }elseif($cookie == "Clases") {
            $query = "UPDATE Clase SET nombre = :nombre, etapa_id = :etapa WHERE id = :edit";
        }elseif($cookie == "Alumnos") {
            $query = "UPDATE Persona SET nombre = :nombre, apellido1 = :apellido1, apellido2 = :apellido2, clase_id = :cuarta WHERE id = :edit";
        }elseif($cookie == "Profesores") {
            $query = "UPDATE Profesores SET nombre = :nombre, apellido1 = :apellido1, apellido2 = :apellido2, correo = :cuarta WHERE id = :edit";
        }
        $obtenerInfo = $conexion->prepare($query);
        $obtenerInfo->bindParam(':edit', $edit);
        $obtenerInfo->bindParam(':nombre', $array[0]);
        if($cookie == "Clases" || $cookie == "Alumnos") {
            $obtenerInfo->bindParam(':etapa', $array[1]);
        }
        if($cookie == "Alumnos" || $cookie == "Profesores") {
            $obtenerInfo->bindParam(':apellido1', $array[1]);
            $obtenerInfo->bindParam(':apellido2', $array[2]);
            $obtenerInfo->bindParam(':cuarta', $array[3]);
        }
		$resultado = $obtenerInfo->execute();
		setcookie("edit",$array[0],time() + 3600,"/");
        return true;
    } catch (PDOException $ex) {
        echo $ex->getMessage();
        return false;
    }
}
//FUNCION DONDE REALIZO LA SENTENCIA INSERT DEPENDIENDO DE LA COOKIE
function sentenciaInsert($cookie, $array) {
	global $conexion;
    $query; 
    try {
        if($cookie == "Etapas") {
            $query = "INSERT INTO Etapa (Nombre) VALUES (:nombre)";
        }elseif($cookie == "Clases") {
            $query = "INSERT INTO Clase (Nombre, id_etapa) VALUES (:nombre, :etapa)";
        }elseif($cookie == "Alumnos") {
            $query = "INSERT INTO Persona (Nombre, apellido1, apellido2, clase_id) VALUES (:nombre, :apellido1, :apellido2, :cuarta)";
        }elseif($cookie == "Profesores") {
            $query = "INSERT INTO Profesores (Correo,Nombre, apellido1, apellido2) VALUES (:cuarta, :nombre, :apellido1, :apellido2)";
        }
        $obtenerInfo = $conexion->prepare($query);
        $obtenerInfo->bindParam(':nombre', $array[0]);
        if($cookie == "Clases") {
            $obtenerInfo->bindParam(':etapa', $array[1]);
        }elseif($cookie == "Alumnos" || $cookie == "Profesores") {
            $obtenerInfo->bindParam(':apellido1', $array[1]);
            $obtenerInfo->bindParam(':apellido2', $array[2]);
            $obtenerInfo->bindParam(':cuarta', $array[3]);
        }
		$resultado = $obtenerInfo->execute();
        return true;
    } catch (PDOException $ex) {
        echo $ex->getMessage();
        return false;
    }
}

//FUNCION DONDE REALIZO LA SENTENCIA DELETE SEGUN LA COOKIE QUE SE HAYA PULSADO
function sentenciaDelete($edit,$cookie){
	global $conexion;
	$query;
	if($cookie == "Etapas"){
		$query = "DELETE FROM Etapa WHERE id = :id;";
	}elseif($cookie == "Clases"){
		$query = "DELETE FROM Clase WHERE id = :id;";
	}elseif($cookie == "Alumnos"){
		$query = "DELETE FROM Persona WHERE id = :id;";
	}elseif($cookie == "Profesores"){
		$query = "DELETE FROM Profesores WHERE id = :id;";
	}
	try {
		$obtenerInfo = $conexion->prepare($query);
		$obtenerInfo->bindParam(':id', $edit);
		$obtenerInfo->execute();
		return true;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}

//FUNCION PARA MOSTRAR LA TABLA (FUNCION QUE DEVUELVE UNA VARIABLE TABLA)
function mostrarTabla($cookie,$array){
	$tabla = "";
	if(!empty($array)){
		if($cookie == "Etapas"){ //SI LA COOKIE ES IGUAL A ETAPAS MOSTRARA LA SIGUIENTE TABLA
			$tabla .= "<h1>Etapa</h1><table class='table table-striped'><thead><tr><th>Nombre</th></tr></thead><tbody>";
			foreach($array as $row){
				$tabla .= "<tr><td><input type='text' name='nombre' value='".$row[1]."' /></td></tr>";
			}
			$tabla .= "</tbody></table><input type='submit' value='Actualizar' name='Update' class='btn btn-warning btn-lg' /><input type='submit' value='Borrar' name='Delete' class='btn btn-danger btn-lg' />";
		}elseif($cookie == "Clases"){ //SI LA COOKIE ES IGUAL A CLASES MOSTRARA LA SIGUIENTE TABLA
			$tabla .= "<h1>Clase</h1><table class='table table-striped'><thead><tr><th>Nombre</th><th>Etapa</th></tr></thead><tbody>";
			foreach($array as $row){
				$tabla .= "<tr><td><input type='text' name='nombre' value='".$row[2]."' /></td><td><select name='clase'>";
				$arraySelect = obtenerSelect("Clase");
				foreach($arraySelect as $id => $opcion){
					if($id == $row[1]){
						$tabla .= "<option value='".$id."' selected>".$opcion."</option>";
					}else{
						$tabla .= "<option value='".$id."' >".$opcion."</option>";
					}
				}
			}
			$tabla .= "</select></td></tr></tbody></table><input type='submit' value='Actualizar' name='Update' class='btn btn-warning btn-lg' /><input type='submit' value='Borrar' name='Delete' class='btn btn-danger btn-lg' />";
		}elseif($cookie == "Alumnos"){ //SI LA COOKIE ES IGUAL A ALUMNOS MOSTRARA LA SIGUIENTE TABLA
			$tabla .= "<h1>Persona</h1><table class='table table-striped'><thead><tr><th>Nombre</th><th>Apellido 1</th><th>Apellido 2</th><th>Clase</th></tr></thead><tbody>";
			foreach($array as $row){
				$tabla .= "<tr><td><input type='text' name='nombre' value='".$row[2]."' /></td><td><input type='text' name='apellido1' value='".$row[3]."' /></td><td><input type='text' name='apellido2' value='".$row[4]."' /></td><td><select name='clase'>";
				$arraySelect = obtenerSelect("Persona");
				foreach($arraySelect as $id => $opcion){
					if($id == $row[1]){
						$tabla .= "<option value='".$id."' selected>".$opcion."</option>";
					}else{
						$tabla .= "<option value='".$id."' >".$opcion."</option>";
					}
				}
			}
			$tabla .= "</select></td></tr></tbody></table><input type='submit' value='Actualizar' name='Update' class='btn btn-warning btn-lg' /><input type='submit' value='Borrar' name='Delete' class='btn btn-danger btn-lg' />";
		}elseif($cookie == "Profesores"){ //SI LA COOKIE ES IGUAL A ETAPAS MOSTRARA LA SIGUIENTE TABLA
			$tabla .= "<h1>Profesor</h1><table class='table table-striped'><thead><tr><th>Correo</th><th>Nombre</th><th>Apellido 1</th><th>Apellido 2</th></tr></thead><tbody>";
			foreach($array as $row){
				$tabla .= "<tr><td><input type='text' name='correo' value='".$row[1]."' /></td><td><input type='text' name='nombre' value='".$row[2]."' /></td><td><input type='text' name='apellido1' value='".$row[3]."' /></td><td><input type='text' name='apellido2' value='".$row[4]."' /></td></tr>";
			}
			$tabla .= "</tbody></table><input type='submit' value='Actualizar' name='Update' class='btn btn-warning btn-lg' /><input type='submit' value='Borrar' name='Delete' class='btn btn-danger btn-lg' />";
		}
	}else{
		if($cookie == "Etapas"){ //SI LA COOKIE ES IGUAL A ETAPAS MOSTRARA LA SIGUIENTE TABLA
			$tabla .= 	"<h1>Etapa</h1><table class='table table-striped'><thead><tr><th>Nombre</th></tr></thead><tbody><tr><td><input type='text' name='nombre' value='' /></td></tr></tbody></table><input type='submit' value='Crear' name='Crear' class='btn btn-warning btn-lg' />";
		}elseif($cookie == "Clases"){ //SI LA COOKIE ES IGUAL A CLASES MOSTRARA LA SIGUIENTE TABLA
			$tabla .=  "<h1>Clase</h1><table class='table table-striped'><thead><tr><th>Nombre</th><th>Etapa</th></tr></thead><tbody><tr><td><input type='text' name='nombre' value='' /></td><td><select name='etapa'>";
			$arraySelect = obtenerSelect("Clase");
			foreach($arraySelect as $id => $opcion){
				$tabla .= "<option value='".$id."' >".$opcion."</option>";
			}
			$tabla .= "</select></td></tr></tbody></table><input type='submit' value='Crear' name='Crear' class='btn btn-warning btn-lg' />";
		}elseif($cookie == "Alumnos"){ //SI LA COOKIE ES IGUAL A ALUMNOS MOSTRARA LA SIGUIENTE TABLA
			$tabla .=  "<h1>Alumno</h1><table class='table table-striped'><thead><tr><th>Nombre</th><th>Apellido 1</th><th>Apellido 2</th><th>Clase</th></tr></thead><tbody><tr><td><input type='text' name='nombre' value='' /></td><td><input type='text' name='apellido1' value='' /></td><td><input type='text' name='apellido2' value='' /></td><td><select name='clase'>";
			$arraySelect = obtenerSelect("Persona");
			foreach($arraySelect as $id => $opcion){
				$tabla .= "<option value='".$id."' >".$opcion."</option>";
			}
			$tabla .= "</select></td></tr></tbody></table><input type='submit' value='Crear' name='Crear' class='btn btn-warning btn-lg' />";
		}elseif($cookie == "Profesores"){ //SI LA COOKIE ES IGUAL A PROFESORES MOSTRARA LA SIGUIENTE TABLA
			$tabla .=  "<h1>Profesores</h1><table class='table table-striped'><thead><tr><th>Correo</th><th>Nombre</th><th>Apellido 1</th><th>Apellido 2</th></tr></thead><tbody><tr><td><input type='text' name='correo' value='' /></td><td><input type='text' name='nombre' value='' /></td><td><input type='text' name='apellido1' value='' /></td><td><input type='text' name='apellido2' value='' /></td></tr></tbody></table><input type='submit' value='Crear' name='Crear' class='btn btn-warning btn-lg' />";
		}
	}
	return $tabla;
}
?>