<?php
require_once $_SERVER['DOCUMENT_ROOT']."/db/db.php";
function insertarValoracion($valoracion,$plato){
    global $conexion;
    try {
        $obtenerInfo = $conexion->prepare("SELECT * FROM valoraciones WHERE dia = CURRENT_DATE() AND plato = '$plato'");
        $obtenerInfo->execute();
        $informacion = $obtenerInfo->fetchAll();
        if(!empty($informacion)){
            $update = $conexion->prepare("UPDATE valoraciones SET $valoracion = $valoracion + 1 WHERE dia = CURRENT_DATE() AND plato = '$plato'");
            $update->execute();
        }else{
            $insert = $conexion->prepare("INSERT INTO valoraciones (dia,valor_1,valor_2,valor_3,valor_4,valor_5,plato) VALUES (CURRENT_DATE(),0,0,0,0,0,'$plato')");
            $insert->execute();
            $update = $conexion->prepare("UPDATE valoraciones SET $valoracion = $valoracion + 1 WHERE dia = CURRENT_DATE() AND plato = '$plato'");
            $update->execute();
        }
    } catch (PDOException $ex) {
        echo "Error en la consulta: " . $ex->getMessage();
        return false;
    }
}
?>