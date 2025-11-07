<?php
require_once $_SERVER['DOCUMENT_ROOT']."/db/db.php";
//FUNCION DONDE HACEMOS UNA SENTENCIA PARA OBTENER LAS ETAPAS QUE HAY
function subirPdf($month,$year,$ruta,$tipo) {
	global $conexion;
	$mensaje = false;
	try {
		$obtenerInfo = $conexion->prepare("SELECT * FROM Menus WHERE Mes = :mes AND Anyo = :year AND tipo_menu = :tipo;");
		$obtenerInfo->bindParam(':mes', $month);
		$obtenerInfo->bindParam(':year', $year);
		$obtenerInfo->bindParam(':tipo', $tipo);
		$obtenerInfo->execute();
		$informacion=$obtenerInfo->fetchColumn();
		if(empty($informacion)){
			$conexion->beginTransaction();
			$insert = $conexion->prepare("INSERT INTO Menus (Mes,Anyo,Menu,tipo_menu) VALUES (:mes,:year,:pdf,:tipo);");
			$insert->bindParam(':mes', $month);
			$insert->bindParam(':year', $year);
			$insert->bindParam(':pdf', $ruta);
			$insert->bindParam(':tipo', $tipo);
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
//FUNCION DONDE REALIZA UN UPDATE A LA BASE DE DATOS DEL PDF
function actualizarPdf($month,$year,$ruta,$tipo) {
	global $conexion;
	try {
		$conexion->beginTransaction();
		$insert = $conexion->prepare("UPDATE Menus SET Menu = :pdf WHERE Mes = :mes AND Anyo = :year AND tipo_menu = :tipo;");
		$insert->bindParam(':mes', $month);
		$insert->bindParam(':year', $year);
		$insert->bindParam(':pdf', $ruta);
		$insert->bindParam(':tipo', $tipo);
		$insert->execute();
		$conexion->commit();
		return true;
	} catch (PDOException $ex) {
		echo $ex->getMessage();
		return false;
	}
}
//FUNCION DONDE MUESTRO UN DIALOG
function obtenerTabla(){
	$tabla = "
			<div id = 'hijo'>
				<dialog id='dialogo' open>
					<div><h3>YA EXISTE UN FICHERO PARA LA FECHA INTRODUCIDA</h3></div>
					<div><p>¿Desea actualizar el fichero?</p></div>
					<div><input type='button' value='SI' onclick='javascript:cerrarDialog(); actualizar();' class='btn btn-warning'/><input type='button' value='NO' onclick='javascript:cerrarDialog();' class='btn btn-secondary'/></div>
				</dialog>
			</div>";
	return $tabla;
}
?>