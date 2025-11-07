<?php
require_once $_SERVER['DOCUMENT_ROOT']."/cocinero/modelo/modelo_menus.php";
$mensaje = "";
if(isset($_FILES['fichero']) && isset($_POST['fecha']) && !isset($_POST['actualizar'])){
	$fecha = $_POST['fecha'];
	$tipo = $_POST['tipo'];
	$fecha = explode("-", $fecha);
	$year = $fecha[0];
	$month = $fecha[1];
	$directorio = $_SERVER['DOCUMENT_ROOT']."/pdf/";
	$fichero = $_FILES['fichero']['name'];
	$rutaServidor = "/pdf/".$fichero;
	$boolean = subirPdf($month,$year,$rutaServidor,$tipo);
	if($boolean){
		$ruta = $directorio . $fichero;
		move_uploaded_file($_FILES['fichero']['tmp_name'], $ruta);
		$mensaje = "<div class='alert alert-success d-flex align-items-center' role='alert' id='alerta'><svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg><h3 id='mensaje'>SE HA SUBIDO CORRECTAMENTE EL FICHERO</h3></div>";
	}else{
		$mensaje = obtenerTabla();
	}
	echo $mensaje;
}elseif(isset($_FILES['fichero']) && isset($_POST['fecha']) && isset($_POST['actualizar'])){
	$fecha = $_POST['fecha'];
	$tipo = $_POST['tipo'];
	$fecha = explode("-", $fecha);
	$year = $fecha[0];
	$month = $fecha[1];
	$directorio = $_SERVER['DOCUMENT_ROOT']."/pdf/";
	$fichero = $_FILES['fichero']['name'];
	$rutaServidor = "/pdf/".$fichero;
	$actualizar = actualizarPdf($month,$year,$rutaServidor,$tipo);
	if($actualizar){
		$ruta = $directorio . $fichero;
		move_uploaded_file($_FILES['fichero']['tmp_name'], $ruta);
		$mensaje = "<div class='alert alert-success d-flex align-items-center' role='alert' id='alerta'><svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Success:'><use xlink:href='#check-circle-fill'/></svg><h3 id='mensaje'>SE HA SUBIDO CORRECTAMENTE EL FICHERO</h3></div>";
	}else{
		$mensaje = "<div class='alert alert-danger d-flex align-items-center' role='alert'><svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg><h3 id='mensaje'>NO SE HA PODIDO SUBIR EL FICHERO A LA BASE DE DATOS</h3></div>";
	}
	echo $mensaje;
}
?>