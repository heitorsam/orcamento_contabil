<?php 

    include '../../conexao.php';

    $setor = $_GET['cd_setor'];

    $consulta = "SELECT us.cd_usuario, us.cd_setor, st.ds_setor
                        FROM orcamento_contabil.usuarios_setor us
                    INNER JOIN orcamento_contabil.Setor st
                        ON st.cd_setor = us.cd_setor
                    WHERE us.CD_SETOR = $setor
                    ";

    $resutado = oci_parse($conn_ora, $consulta);

    oci_execute($resutado);





?>


<div class="col-md-12">
    <table class="table table-fixed table-hover table-striped " cellspacing="0" cellpadding="0">

        <thead><tr>
            <!--COLUNAS-->
            <th class="align-middle" style="text-align: center !important;"><span>Setor</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>Usuário</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>Opções</span></th>

        </tr></thead>            

        <tbody>
            
                <?php     
                    while($row = oci_fetch_array($resutado)){    
                        echo '<tr>';
                            echo "<td class='align-middle' style='text-align: center;'>". $row['DS_SETOR']."</td>";
                            echo "<td class='align-middle' style='text-align: center;'>". $row['CD_USUARIO']."</td>";
                            ?>
                            <td class='align-middle' style='text-align: center;'>
                                <a class="btn btn-adm" onclick="ajax_apagar_usuario('<?php echo $row['CD_USUARIO'] ?>','<?php echo $row['CD_SETOR'] ?>')">
                                    <i class='fas fa-trash'></i>
                                </a>
                            </td>
                        </tr>
                <?php
                    }
                ?>
            
        </tbody>  
    </table>   
</div>