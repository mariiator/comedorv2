<?php
require_once $_SERVER['DOCUMENT_ROOT']."/db/db.php";
function mostrarTabla($array, $fecha){
    $nombreMeses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $arrayFecha = explode("-", $fecha);
    $year = $arrayFecha[0];
    $month = $arrayFecha[1];
    $day = $arrayFecha[2];
    $totalAsistencia = 0;
    $totalMenusAlumnos = 0;
    $totalMenusProfesores = 0;
	$tabla = "<table class='table table-striped' id='listado'><thead><tr><td colspan='5'><h3>Dia ".$day." de ".$nombreMeses[$month - 1]." de ".$year."</h3></td></tr><tr><th>Nombre</th><th>Apellido1</th><th>Apellido2</th><th data-type='string'>Tipo Menu</th><th>Alergias</th></tr></thead><tbody>";
    if(!empty($array)){
        foreach($array as $elementos){
            $tabla .= "<tr><td>".$elementos[1]."</td><td>".$elementos[2]."</td><td>".$elementos[3]."</td>";
            if($elementos[0] == "alumno"){
                $tabla .= "<td class='tipoMenu' id='alumno'>";
                $totalMenusAlumnos += 1;
            }elseif($elementos[0] == "profesor"){
                $tabla .= "<td class='tipoMenu' id='profesor'>";
                $totalMenusProfesores += 1;
            }
            $tabla .= $elementos[0]."</td>";
            if($elementos[4] != "No tiene alergias"){
                $tabla .= "<td style='background-color:red;'><label style='color:white;'>";
            }else{
                $tabla .= "<td><label>";
            }
            $tabla .= $elementos[4]."</label></td></tr>";
            $totalAsistencia += 1;
        }
        $tabla .= "</tbody></table><table class='table table-striped' id='asistencias'><thead><tr><td colspan='3'><h3>TOTAL</h3></td></tr><tr><th>Total Asistencia Profesores</th><th>Menus Alumnos</th><th>Menus Profesores</th></tr></thead><tbody><tr><td>".$totalAsistencia."</td><td id='alumno'>".$totalMenusAlumnos."</td><td id='profesor'>".$totalMenusProfesores."</td></tr></tbody></table>";
    }else{
        $tabla .= "";
    }
	return $tabla;
}

function obtenerProfesores($fecha){
	global $conexion;
	$array = array();
    try {
        $obtenerInfo = $conexion->prepare("SELECT Asistencias_profe.Tipo_menu, Profesores.Nombre, Profesores.Apellido1, Profesores.Apellido2, Profesores.id FROM Asistencias_profe INNER JOIN Profesores ON Asistencias_profe.Id_Profesor = Profesores.Id WHERE Asistencias_profe.Dia = :fecha;");
        $obtenerInfo->bindParam(':fecha', $fecha);
        $obtenerInfo->execute();
        $informacion = $obtenerInfo->fetchAll();
        foreach($informacion as $row){
            $resultado = "";
            $obtenerAlergias = $conexion->prepare("SELECT alergias FROM Alergias WHERE id_profesor = :id;");
            $obtenerAlergias->bindParam(':id', $row[4]);
            $obtenerAlergias->execute();
            $alergias = $obtenerAlergias->fetchColumn();
            if(!empty($alergias)){
                $resultado = $alergias;
            }else{
                $resultado = "No tiene alergias";
            }
        	$array[] = [$row[0],$row[1],$row[2],$row[3],$resultado];
        }
        function compararPorTipoMenu($a, $b){
            return strcmp($a[0], $b[0]);
        }
        if(!empty($array)){
            usort($array, 'compararPorTipoMenu');
        }
        return $array;
    } catch (PDOException $ex) {
        echo "Error en la consulta: " . $ex->getMessage();
        return false;
    }
}
?>