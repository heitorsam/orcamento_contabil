<?php 

    //CONEXAO
    include '../conexao.php';

    $var_vl_periodo = $_GET["vl_periodo"];  
    $var_vl_periodo = SUBSTR($var_vl_periodo,5,2) . '/' . SUBSTR($var_vl_periodo,0,4);

    $consulta_grupo = "SELECT gpo.CD_GRUPO_ORCADO, gpo.DS_GRUPO_ORCADO
                       FROM orcamento_contabil.GRUPO_ORCADO gpo
                       WHERE gpo.PERIODO = '$var_vl_periodo'";

    $result_grupo = oci_parse($conn_ora, $consulta_grupo);    
    oci_execute($result_grupo);

    echo '<label>Selecione o Grupo Orçado:</label>';

    echo '<select id="id_setor" name="frm_cd_grupo_orcado" class="form-control" >';                                   
    
    while($row_gpo = oci_fetch_array($result_grupo)){	

        echo '<option value="' . $row_gpo['CD_GRUPO_ORCADO'] . '">' . $row_gpo['DS_GRUPO_ORCADO'] .  '</option>';

    }

    echo '</select>';

?>

