<?php 
    include "../conexao.php";

    $cons_tb_prev = "SELECT 
                     np.CD_NECESSIDADE_PREVISTA,
                     np.PERIODO,
                     np.VL_NECESSIDADE_PREVISTA,
                     np.CD_USUARIO_CADASTRO,
                     np.HR_CADASTRO,
                     np.CD_USUARIO_ULT_ALT,
                     np.HR_ULT_ALT
                     FROM orcamento_contabil.NECESSIDADE_PREVISTA np
                     ORDER BY SUBSTR(np.PERIODO,4,4) * 1 DESC, SUBSTR(np.PERIODO,0,2) * 1 DESC";

    $result_tb_prev = oci_parse($conn_ora, $cons_tb_prev);
    
    $valida_tb_prev = oci_execute($result_tb_prev);
    
    if(!$valida_tb_prev){
       
        $erro = oci_error($result_cad_duplica_periodo);																							
        echo htmlentities($erro['message']); 

    }else{

        while($row_prev = oci_fetch_array($result_tb_prev)){

            echo '<tr>';

                echo "<td class='align-middle' style='text-align: center;'>" . $row_prev['PERIODO'] . " </td>";
                //echo "<td class='align-middle' style='text-align: center;'>" . $row_prev['VL_NECESSIDADE_PREVISTA'] . " </td>";
?>

                <td id="VL_NECESSIDADE_PREVISTA<?php echo @$row_prev['CD_NECESSIDADE_PREVISTA']; ?>"
                ondblclick="fnc_editar_campo('portal_sesmt.NECESSIDADE_PREVISTA', 'VL_NECESSIDADE_PREVISTA', '<?php echo @$row_prev['VL_NECESSIDADE_PREVISTA']; ?>', 'CD_NECESSIDADE_PREVISTA', '<?php echo @$row_prev['CD_NECESSIDADE_PREVISTA']; ?>', '')"
                class='align-middle' style='text-align: center;'><?php echo @$row_prev['VL_NECESSIDADE_PREVISTA']; ?></td>

                <td class='align-middle' style='text-align: center;'><a class="btn btn-danger" onclick="del_vl_previsto('<?php echo $row_prev['CD_NECESSIDADE_PREVISTA'] ; ?>')"><i class="fas fa-trash"></i> </a></td>
<?php
                

            echo '</tr>';

        }

    }
 

?>

