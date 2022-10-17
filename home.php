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

            <?php if($_SESSION['SN_CONTABILIDADE'] == 'S' ) { ?>

                <div class="div_br"> </div>
                <div class="div_br"> </div>

                <h11><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Cadastros</h11>

                <div class="div_br"> </div>

                <a href="cadastro_setor.php" class="botao_home" type="submit"><i class="fa fa-building" aria-hidden="true"></i> Setor</a></td></tr>
                
                <span class="espaco_pequeno"></span>
                
                <a href="cadastro_grupo_orcado.php" class="botao_home" type="submit"><i class="fa fa-users" aria-hidden="true"></i> Grupo Orçado</a></td></tr>
                
                <span class="espaco_pequeno"></span>

                <a href="cadastro_result_prev.php" class="botao_home" type="submit"><i class="fa fa-line-chart" aria-hidden="true"></i> Resultado Previsto</a></td></tr>
                
                <span class="espaco_pequeno"></span>
                
                <a href="cadastro_conta_contabil.php" class="botao_home" type="submit"><i class="fa fa-archive" aria-hidden="true"></i> Conta Contábil</a></td></tr>

                <span class="espaco_pequeno"></span>
                
                <a href="duplicar_periodo.php" class="botao_home" type="submit"><i class="fas fa-copy" aria-hidden="true"></i> Duplicar Período</a></td></tr>

            <?php } ?>

            <div class="div_br"> </div>
            <div class="div_br"> </div>
            <div class="div_br"> </div>


            <h11><i class="fa fa-bar-chart" aria-hidden="true"></i> Resultados</h11>

            <div class="div_br"> </div>

            <a href="resultados.php" class="botao_home" type="submit"><i class="fa fa-bar-chart" aria-hidden="true"></i> Resultados</a></td></tr>

            <div class="div_br"> </div>                      

            
    
        <?php if(@$_SESSION['sn_admin'] == 'S'){ ?>


        <?php } ?>
            
<?php

    //RODAPE
    include 'rodape.php';
?>