<?php
require_once $_SERVER['DOCUMENT_ROOT']."/db/db.php";
//FUNCION DONDE REALIZA UNA CONSULTA SEGUN LA COOKIE
function obtenerConsulta($cookie){
	global $conexion;
	$array;
	$query;
	if($cookie == "Etapas"){ //SI EL VALOR DE LA COOKIE ES ETAPAS
		$query = "SELECT * FROM Etapa;";
	}elseif($cookie == "Clases"){ //SI EL VALOR DE LA COOKIE ES CLASES
		$query = "SELECT * FROM Clase;";
	}elseif($cookie == "Alumnos"){ //SI EL VALOR DE LA COOKIE ES ALUMNOS
		$query = "SELECT * FROM Persona;";
	}elseif($cookie == "Profesores"){
		$query = "SELECT * FROM Profesores;";
	}
	try {
		$obtenerInfo = $conexion->prepare($query);
		$obtenerInfo->execute();
		$informacion=$obtenerInfo->fetchAll();
		foreach($informacion as $row){
			$clave = array_shift($row);
			array_shift($row);
			$array[$clave] = $row;
		}
		return $array;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}

//FUNCION DONDE DEVUELVE UNA TABLA SEGUN LA COOKIE Y EL ARRAY
function mostrarTabla($cookie,$array){
	$tabla = "";
	if($cookie == "Etapas"){
		$tabla .= "<h1>Listado de Etapas</h1><table class='table table-striped'><thead><tr><th>Id</th><th>Nombre</th><th>Acciones</th></tr></thead><tbody>";
		foreach($array as $id => $valor){
			$tabla .= "<tr><td>".$id."</td><td>".$valor[0]."</td><td><ul><li><button type='submit' name='show' value='".$id."' class='btn btn-link'>show</button></li><li><button type='submit' name='edit' value='".$id."' class='btn btn-link'>edit</button></li></ul></td></tr>";
		}
		$tabla .= "</tbody></table>";
	}elseif($cookie == "Clases"){
		$tabla .= "<h1>Listado de Clases</h1><table class='table table-striped'><thead><tr><th>Id</th><th>Nombre</th><th>Acciones</th></tr></thead><tbody>";
		foreach($array as $id => $valor){
			$tabla .= "<tr><td>".$id."</td><td>".$valor[1]."</td><td><ul><li><button type='submit' name='show' value='".$id."' class='btn btn-link'>show</button></li><li><button type='submit' name='edit' value='".$id."' class='btn btn-link'>edit</button></li></ul></td></tr>";
		}
		$tabla .= "</tbody></table>";
	}elseif($cookie == "Alumnos"){
		$tabla .= "<h1>Listado de Alumnos</h1><table class='table table-striped'><thead><tr><th>Id</th><th>Nombre</th><th>Apellido 1</th><th>Apellido 2</th><th>Acciones</th></tr></thead><tbody>";
		foreach($array as $id => $valor){
			$tabla .= "<tr><td>".$id."</td><td>".$valor[1]."</td><td>".$valor[2]."</td><td>".$valor[3]."</td><td><ul><li><button type='submit' name='show' value='".$id."' class='btn btn-link'>show</button></li><li><button type='submit' name='edit' value='".$id."' class='btn btn-link'>edit</button></li></ul></td></tr>";
		}
		$tabla .= "</tbody></table>";
	}elseif($cookie == "Profesores"){
		$tabla .= "<h1>Listado de Profesores</h1><table class='table table-striped'><thead><tr><th>Correo</th><th>Nombre</th><th>Apellido 1</th><th>Apellido 2</th><th>Acciones</th></tr></thead><tbody>";
		foreach($array as $id => $valor){
			$tabla .= "<tr><td>".$valor[0]."</td><td>".$valor[1]."</td><td>".$valor[2]."</td><td>".$valor[3]."</td><td><ul><li><button type='submit' name='show' value='".$id."' class='btn btn-link'>show</button></li><li><button type='submit' name='edit' value='".$id."' class='btn btn-link'>edit</button></li></ul></td></tr>";
		}
		$tabla .= "</tbody></table>";
	}
	
	return $tabla;
}
?>