<?php 

    //CONEXAO
    include '../conexao.php';

    $var_vl_reduzido = $_GET["vl_reduzido"];

    $consulta_plano = "SELECT pc.DS_CONTA 
                       FROM dbamv.plano_contas pc
                       WHERE pc.CD_REDUZIDO = '$var_vl_reduzido'
                       AND SUBSTR(pc.CD_CONTABIL,0,1) IN (3,4)";

    $result_plano= oci_parse($conn_ora, $consulta_plano);
    oci_execute($result_plano);

    $result_plano_contas = @oci_fetch_array($result_plano);

    if(isset($result_plano_contas['DS_CONTA'])){

        echo $result_plano_contas['DS_CONTA'];

    }else{

        echo '' ;

    }

?>

