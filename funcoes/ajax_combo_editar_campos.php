<?php

    //CONEXAO
    include '../conexao.php';    

    $var_vl_select = str_replace("%", " ", $_GET["vl_select"]); 
    $var_vl_select = str_replace("'", "", $var_vl_select);
    $var_vl_select = str_replace("~", "'", $var_vl_select);
    
    
    $result_select  = oci_parse($conn_ora, $var_vl_select);
    oci_execute($result_select);

    echo '<select class="form-control" style="width=30%;!important" id="editar_combo" onblur="alterar_valor(0)">
        
        <option value="">Selecione</option>';

        while($row_select = @oci_fetch_array($result_select)){   
            if($row_select['COLUNA_DS'] == ''){
                echo '<option value="'. $row_select['COLUNA_VL'] . '">'. $row_select['COLUNA_VL'] . '</option>';
            }else if($row_select['COLUNA_VL'] == ''){
                echo '<option value="'. $row_select['COLUNA_DS'] . '">'. $row_select['COLUNA_DS'] . '</option>';
            }else{
                echo '<option value="'. $row_select['COLUNA_VL'] . '">'. $row_select['COLUNA_DS'] . '</option>';
            }
        }  

    echo '</select>'; 

?>