<?php 
    

    //CABECALHO
    include 'cabecalho.php';

    //ACESSO RESTRITO
    include 'acesso_restrito.php';

    //ACESSO RESTRITO CONTABILIDADE
    include 'acesso_restrito_contabilidade.php';

    //CONEXAO
    include 'conexao.php';

    //RECEBENDO POST
    
    //RECEBENDO SESSAO
    //$var_cd_usuario = $_SESSION['usuarioLogin'];

    //SQL
    //include 'sql_listar_pendencias.php'; 
    
    $frm_cad_setor = @$_POST["frm_cad_setor"];
    $frm_cad_gerente = @$_POST["frm_cad_gerente"];
 
?>

    <!--TITULO-->
    <h11><i class="fa fa-building"></i> Cadastro Setor</h11>
    <span class="espaco_pequeno" style="width: 6px;" ></span>
    <h27> <a href="home.php" style="color: #444444; text-decoration: none;"> <i class="fa fa-reply" aria-hidden="true"></i> Voltar </a> </h27> 
    
    <div class="div_br"> </div>       

    <!--MENSAGENS-->
    <?php
        include 'js/mensagens.php';
    ?>

    <div class="div_br"> </div>

    <!--MODEL CADASTRAR-->
    <?php
        include 'modals/setor/modal_cadastro_setor.php';
    ?>        

    <div class="div_br"> </div>  
    <div class="div_br"> </div>  

    <?php 
        /////////////////////
        ////LISTAR SETOR/////
        /////////////////////
    
        
        $lista_setor = "SELECT setor.CD_SETOR, setor.DS_SETOR, setor.NM_GESTOR, setor.SN_ATIVO, setor.CD_USUARIO 
                        FROM orcamento_contabil.SETOR setor
                        ORDER BY setor.HR_CADASTRO DESC";

        $result_setor = oci_parse($conn_ora, $lista_setor);

        @oci_execute($result_setor);
    ?>

    <!--TABELA DE RESULTADOS -->
    <div class="table-responsive col-md-12">
    <table class="table table-fixed table-hover table-striped " cellspacing="0" cellpadding="0">

        <thead><tr>
            <!--COLUNAS-->
            <th class="align-middle" style="text-align: center !important;"><span>Nome</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>Gerente</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>Ativo</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>Usuario</span></th>

        </tr></thead>            

        <tbody>
            <?php
                while($row_setor = @oci_fetch_array($result_setor)){  ?>  
                    
                <tr>
                    
                    <td id="DS_SETOR<?php echo @$row_setor['CD_SETOR']; ?>"
                    ondblclick="fnc_editar_campo('orcamento_contabil.SETOR', 'DS_SETOR', '<?php echo @$row_setor['DS_SETOR']; ?>', 'CD_SETOR', '<?php echo @$row_setor['CD_SETOR']; ?>', '')"
                    class='align-middle' style='text-align: center;'><?php echo @$row_setor['DS_SETOR']; ?></td>

                    <td id="NM_GESTOR<?php echo @$row_setor['CD_SETOR']; ?>"
                    ondblclick="fnc_editar_campo('orcamento_contabil.SETOR', 'NM_GESTOR', '<?php echo @$row_setor['NM_GESTOR']; ?>', 'CD_SETOR', '<?php echo @$row_setor['CD_SETOR']; ?>', 'SELECT NM_GESTOR AS COLUNA_DS FROM orcamento_contabil.SETOR_TESTE')"
                    class='align-middle' style='text-align: center;'><?php echo @$row_setor['NM_GESTOR']; ?></td>
                    
                    <?php
                    if ($row_setor['SN_ATIVO'] == 'S') { 
                                echo "<td class='text-center align-middle'>" .
                                "<a class='botoes' style='color: #3c3c3c;' href='consultas/proc_alterar_sn_ativo.php?id=" . 
                                $row_setor['CD_SETOR']  . "&sn_ativo=N&tabela=SETOR&pagina=cadastro_setor" .
                                "' onclick=\"return confirm('Tem certeza que deseja desativar esse registro?');\">" . 
                                "<i class='fa fa-toggle-on' aria-hidden='true'></i>" . "</a>" . "</td>";
                            } 
                            else {
                                echo "<td class='text-center align-middle'>" . 
                                "<a class='botoes' style='color: #3c3c3c;' href='consultas/proc_alterar_sn_ativo.php?id=" .
                                $row_setor['CD_SETOR']  . "&sn_ativo=S&tabela=SETOR&pagina=cadastro_setor" . 
                                "' onclick=\"return confirm('Tem certeza que deseja ativar esse registro?');\">" . 
                                "<i class='fa fa-toggle-off' aria-hidden='true'></i>" . "</a>" . "</td>"; 
                            };?>
                    <td id="CD_USUARIO<?php echo @$row_setor['CD_SETOR']; ?>"
                    ondblclick="fnc_editar_campo('orcamento_contabil.SETOR', 'CD_USUARIO', '<?php echo @$row_setor['CD_USUARIO']; ?>', 'CD_SETOR', '<?php echo @$row_setor['CD_SETOR']; ?>', '')"
                    class='align-middle' style='text-align: center;'><?php echo @$row_setor['CD_USUARIO']; ?></td>

                </tr>
                <?php 

                } ?>
        </tbody>           
    </table>
    </div>

<?php

    //JAVASCRIPT EDITAR CAMPOS
    include 'funcoes/js_editar_campos.php';

    //RODAPE
    include 'rodape.php';
?>

