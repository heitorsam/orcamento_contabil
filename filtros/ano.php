<?php 

    $var_ano = @$_POST["filtro_ano"];  
    
    $consulta_ano = "SELECT DISTINCT SUBSTR(cc.PERIODO, 4, 4) AS ANO
                     FROM orcamento_contabil.conta_contabil cc
                     WHERE SUBSTR(cc.PERIODO, 4, 4) <> NVL('$var_ano', 0)
                     AND SUBSTR(cc.PERIODO, 4, 4) >= 2022
                     ORDER BY SUBSTR(cc.PERIODO, 4, 4) DESC";

    $result_ano = oci_parse($conn_ora, $consulta_ano);    
    oci_execute($result_ano);

    echo 'Ano: ';

    echo '<select class="form-control" name="filtro_ano" id="jv_filtro_ano" required>';      
    

    if($var_ano > 1){

        echo '<option value="' . $var_ano . '">' . $var_ano .  '</option>';
    }
    
    while($row_ano = oci_fetch_array($result_ano)){	

        echo '<option value="' . $row_ano['ANO'] . '">' . $row_ano['ANO'] .  '</option>';

    }

    echo '</select>'; 

?>

       
