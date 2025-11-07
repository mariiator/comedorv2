<?php
require_once $_SERVER['DOCUMENT_ROOT']."/valoracion/modelo/modelo_valoracion.php";
if(isset($_POST['VALOR']) && isset($_POST['PLATO'])){
    var_dump("ENTRA AQUI");
    $valor = $_POST['VALOR'];
    $plato = $_POST['PLATO'];
    insertarValoracion($valor,$plato);
}
?>