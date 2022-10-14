<?php 

    include '../conexao.php';

    $cd_anexo = $_POST['cd_anexo'];

    $consulta = "DELETE FROM orcamento_contabil.ANEXOS WHERE CD_ANEXO = $cd_anexo";

    $resutado = oci_parse($conn_ora, $consulta);

    oci_execute($resutado);




?>