<?php
require_once $_SERVER['DOCUMENT_ROOT']."/comedorv2/db/db.php";
//FUNCION DONDE REALIZA UNA CONSULTA SEGUN LA COOKIE Y EL ID
function obtenerConsulta($cookie,$id){
	global $conexion;
	$array;
	$query;
	if($cookie == "Etapas"){
		$query = "SELECT * FROM Etapa WHERE id = :id;";
	}elseif($cookie == "Clases"){
		$query = "SELECT * FROM Clase WHERE id = :id;";
	}elseif($cookie == "Alumnos"){
		$query = "SELECT * FROM Persona WHERE id = :id;";
	}elseif($cookie == "Profesores"){
		$query = "SELECT * FROM Profesores WHERE id = :id;";
	}
	try {
		$obtenerInfo = $conexion->prepare($query);
		$obtenerInfo->bindParam(':id', $id);
		$obtenerInfo->execute();
		$informacion=$obtenerInfo->fetchAll();
		return $informacion;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}

//FUNCION DONDE REALIZA UNA SENTENCIA DELETE SEGUN LO QUE SE HA MOSTRADO Y LA COOKIE
function sentenciaDelete($show,$cookie){
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
		$obtenerInfo->bindParam(':id', $show);
		$obtenerInfo->execute();
		return true;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}

//FUNCION DONDE NOS DEVUELVE LA TABLA SEGUN EL ARRAY Y LA COOKIE
function mostrarTabla($cookie,$array){
	$tabla = "";
	if($cookie == "Etapas"){
		$tabla .= "<h1>Etapa</h1><table class='table table-striped'><thead><tr><th>Id</th><th>Nombre</th></tr></thead><tbody>";
		foreach($array as $valor){
			$tabla .= "<tr><td>".$valor[0]."</td><td>".$valor[1]."</td></tr>";
		}
		$tabla .= "</tbody></table>";
	}elseif($cookie == "Clases"){
		$tabla .= "<h1>Clase</h1><table class='table table-striped'><thead><tr><th>Id</th><th>Nombre</th></tr></thead><tbody>";
		foreach($array as $valor){
			$tabla .= "<tr><td>".$valor[0]."</td><td>".$valor[2]."</td></tr>";
		}
		$tabla .= "</tbody></table>";
	}elseif($cookie == "Alumnos"){
		$tabla .= "<h1>Persona</h1><table class='table table-striped'><thead><tr><th>Id</th><th>Nombre</th><th>Apellido 1</th><th>Apellido 2</th></tr></thead><tbody>";
		foreach($array as $valor){
			$tabla .= "<tr><td>".$valor[0]."</td><td>".$valor[2]."</td><td>".$valor[3]."</td><td>".$valor[4]."</td></tr>";
		}
		$tabla .= "</tbody></table>";
	}elseif($cookie == "Profesores"){
		$tabla .= "<h1>Profesor</h1><table class='table table-striped'><thead><tr><th>Correo</th><th>Nombre</th><th>Apellido 1</th><th>Apellido 2</th></tr></thead><tbody>";
		foreach($array as $valor){
			$tabla .= "<tr><td>".$valor[1]."</td><td>".$valor[2]."</td><td>".$valor[3]."</td><td>".$valor[4]."</td></tr>";
		}
		$tabla .= "</tbody></table>";
	}
	return $tabla;
}
?>