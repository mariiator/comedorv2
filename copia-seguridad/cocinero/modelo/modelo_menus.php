<?php
require_once $_SERVER['DOCUMENT_ROOT']."/db/db.php";
//FUNCION DONDE HACEMOS UNA SENTENCIA PARA OBTENER LAS ETAPAS QUE HAY
function subirPdf($month,$year,$ruta) {
	global $conexion;
	$mensaje = false;
	try {
		$obtenerInfo = $conexion->prepare("SELECT * FROM Menus WHERE Mes = :mes AND Anyo = :year;");
		$obtenerInfo->bindParam(':mes', $month);
		$obtenerInfo->bindParam(':year', $year);
		$obtenerInfo->execute();
		$informacion=$obtenerInfo->fetchColumn();
		if(empty($informacion)){
			$conexion->beginTransaction();
			$insert = $conexion->prepare("INSERT INTO Menus (Mes,Anyo,Menu) VALUES (:mes,:year,:pdf);");
			$insert->bindParam(':mes', $month);
			$insert->bindParam(':year', $year);
			$insert->bindParam(':pdf', $ruta);
			$insert->execute();
			$conexion->commit();
			$mensaje = true;
		}
		return $mensaje;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}

function actualizarPdf($month,$year,$ruta) {
	global $conexion;
	try {
		$conexion->beginTransaction();
		$insert = $conexion->prepare("UPDATE Menus SET Menu = :pdf WHERE Mes = :mes AND Anyo = :year;");
		$insert->bindParam(':mes', $month);
		$insert->bindParam(':year', $year);
		$insert->bindParam(':pdf', $ruta);
		$insert->execute();
		$conexion->commit();
		return true;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}

function obtenerTabla(){
	$tabla = "
			<div id = 'hijo'>
				<dialog id='dialogo' open>
					<div><h3>YA EXISTE UN FICHERO PARA LA FECHA INTRODUCIDA</h3></div>
					<div><p>¿Desea actualizar el fichero?</p></div>
					<div><input type='button' value='SI' onclick='javascript:cerrarDialog(); actualizar();'/><input type='button' value='NO' onclick='javascript:cerrarDialog();'/></div>
				</dialog>
			</div>";
	return $tabla;
}
?>