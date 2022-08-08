
<?php 
//POPULANDO O SELECT
$consulta_filtro_setor = "SELECT st.CD_SETOR, st.DS_SETOR
                          FROM orcamento_contabil.SETOR st
                          WHERE st.SN_ATIVO = 'S'
                          ORDER BY st.DS_SETOR ";

$result_filtro_setor = oci_parse($conn_ora, $consulta_filtro_setor);																									

//EXECUTANDO A CONSULTA SQL (ORACLE)
@oci_execute($result_filtro_setor);

?>

Setor:
<select class="form-control" name="filtro_setor" id="jv_filtro_setor" required>
    <?php
        echo $var_setor = @$_POST["filtro_setor"];  

        if(strlen($var_setor) < 1){ 

            echo "<option value=''> Selecione </option>";

        }else{

            $consulta_selecionado = "SELECT st.DS_SETOR
                                     FROM orcamento_contabil.SETOR st
                                     WHERE st.SN_ATIVO = 'S'
                                     AND st.CD_SETOR = $var_setor                          
                                     ORDER BY st.DS_SETOR ASC";

            $result_selecionado = oci_parse($conn_ora, $consulta_selecionado);																									

            //EXECUTANDO A CONSULTA SQL (ORACLE)
            @oci_execute($result_selecionado);
            $row_selecionadoo = @oci_fetch_array($result_selecionado);

            if($var_setor == 'Todos'){
                echo "<option value='Todos'>Todos</option>";
            }else{
                echo "<option value='" . $var_setor . "'>". $row_selecionadoo['DS_SETOR'] ."</option>";
            }
        }

        if($var_setor <> 'Todos'){
            echo "<option value='Todos'>Todos</option>";
        }

        while($row_filtro_setor = oci_fetch_array($result_filtro_setor)){	            

            if($var_setor <> $row_filtro_setor['CD_SETOR']) { echo "<option value='". $row_filtro_setor['CD_SETOR'] ."'> ". $row_filtro_setor['DS_SETOR'] ."  </option>"; }
        }
    ?>
</select>