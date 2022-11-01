<?php 
    //CABECALHO
    include 'cabecalho.php';
?>

<div class="div_br"> </div>

    <!--MENSAGENS-->
    <?php
        include 'js/mensagens.php';
        include 'js/mensagens_usuario.php';
    ?>
        
    <h11><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Teste Anexo</h11>

    <div class="div_br"> </div>    
    
    <?php

        include 'conexao.php';

        $cons_anexo = "SELECT an.cd_anexo,
        an.LO_ARQUIVO_DOCUMENTO,
        an.cd_conta_contabil,
        an.Ds_Nome_Arquivo,
        an.TP_EXTENSAO
        FROM orcamento_contabil.ANEXOS an
        WHERE an.CD_CONTA_CONTABIL = 4840";

        
        $result_anexo = oci_parse($conn_ora, $cons_anexo);

        oci_execute($result_anexo);

        while($row_anexo = oci_fetch_array($result_anexo)){

            echo '</br>' . $row_anexo['DS_NOME_ARQUIVO'] . '</br>';

            $img = $row_anexo['LO_ARQUIVO_DOCUMENTO']->load();

            $img_64 = base64_encode($img); 
            
            ?>

            <object style="height: 20%; width: 20%;" data="data:application/pdf;base64,<?php echo $img_64; ?>" ></object>

            <object data="data:application/pdf;base64,<?php echo $img_64; ?>" type="application/pdf" style="height:20%;width:20%"></object>

    <?php 

        }

    ?>
            
<?php

    //RODAPE
    include 'rodape.php';
?>