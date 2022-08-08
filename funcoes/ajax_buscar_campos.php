<?php 
    include "../conexao.php";

    $update_final = $_POST["texto_select"];

    $result_editar= oci_parse($conn_ora, $update_final);
    $valida_editar = oci_execute($result_editar);


?>