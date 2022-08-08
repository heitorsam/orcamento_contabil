<?php
    include '../../conexao.php';

    session_start();

    $cd_contabil = $_POST['cd_conta'];

    $cons_just = "SELECT orcamento_contabil.fnc_retorna_txt_limite_caract(DS_JUSTIFICATIVA_3,3999) AS DS_JUSTIFICATIVA_3 FROM orcamento_contabil.JUSTIFICATIVA_CONTABIL_CLOB just WHERE just.cd_conta_contabil = $cd_contabil";

    $result_just = oci_parse($conn_ora, $cons_just);

    oci_execute($result_just);

    $row_just = oci_fetch_array($result_just);

    echo @$row_just['DS_JUSTIFICATIVA_3'];

?>