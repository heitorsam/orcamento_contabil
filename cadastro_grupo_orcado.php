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
    $var_periodo = @$_POST["frm_periodo"];  
    $var_periodo = @SUBSTR($var_periodo,5,2) . '/' . SUBSTR($var_periodo,0,4);

    //echo $var_periodo; echo '</br>';
    //echo $_SESSION['periodo']; echo '</br>';

    if(isset($_POST["frm_periodo"])){

        @$_SESSION['periodo'] = @$var_periodo;
        @$var_periodo_filtro = @substr($var_periodo,3,4) . '-' . @substr($var_periodo,0,2);
    
    }else{

        @$var_periodo = @$_SESSION['periodo'];
        @$var_periodo_filtro = @substr($_SESSION['periodo'],3,4) . '-' . @substr($_SESSION['periodo'],0,2);

    }
    
    //RECEBENDO SESSAO
    @$var_cd_usuario = @$_SESSION['usuarioLogin'];

    //SQL
    //include 'sql_listar_pendencias.php'; 
    
    $frm_cad_nome = @$_POST["frm_cad_nome"];
    $frm_cad_periodo = @$_POST["frm_cad_periodo"];
    $frm_cad_meta = @$_POST["frm_cad_meta"];    
  
?>
    <!--TITULO-->
    <h11><i class="fa fa-users"></i> Cadastro Grupo Orçado</h11>
    <span class="espaco_pequeno" style="width: 6px;" ></span>
    <h27> <a href="home.php" style="color: #444444; text-decoration: none;"> <i class="fa fa-reply" aria-hidden="true"></i> Voltar </a> </h27> 
    
    <div class="div_br"> </div>       

    <!--MENSAGENS-->
    <?php
        include 'js/mensagens.php';
    ?>

    <div class="row">

        <div class="col-3" style="text-align: left; background-color: #f9f9f9 !important;">

            Período: </br>

            <form action="cadastro_grupo_orcado.php" method="POST">
            
                <div class="input-group mb-3">                    
                        
                        <input type="month" class="form-control" name="frm_periodo"
                        value="<?php echo $var_periodo_filtro; ?>"
                        placeholder="Digite o período" required>
                    
                        <!-- BOTAO PESQUISAR -->
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>                    

                </div>

            </form>

        </div>  

        <div class="col-3" style="text-align: left; background-color: #f9f9f9 !important;">

            </br>
            <!--MODEL CADASTRAR-->
            <?php
                include 'modals/grupo_orcado/modal_cadastro_grupo_orcado.php';
            ?>           

        </div>

    </div>  

    <div class="div_br"> </div>  

    <?php 

        ////////////////////////////
        ////LISTAR GRUPO ORCADO/////
        //////////////////////////// 

        $lista_grupo_orcado = "SELECT gpo.CD_GRUPO_ORCADO, gpo.DS_GRUPO_ORCADO, gpo.PERIODO, 
                               TRIM(TO_CHAR(gpo.VL_ORCADO, '999G999G999G999D99')) AS VL_ORCADO,
                               gpo.VL_ORCADO AS VL_ORCADO_FLOAT,
                               gpo.SN_ATIVO 
                               FROM orcamento_contabil.GRUPO_ORCADO gpo
                               WHERE gpo.PERIODO = '$var_periodo'
                               ORDER BY gpo.CD_GRUPO_ORCADO DESC";

        $result_grupo_orcado = oci_parse($conn_ora, $lista_grupo_orcado);

        @oci_execute($result_grupo_orcado);
    ?>

    <!--TABELA DE RESULTADOS -->
    <div class="table-responsive col-md-12">
    <table class="table table-fixed table-hover table-striped " cellspacing="0" cellpadding="0">

        <thead><tr>
            <!--COLUNAS-->
            <th class="align-middle" style="text-align: center !important;"><span>Nome</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>Periodo</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>Orçado</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>Ativo</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>Opções</span></th>

        </tr></thead>            

        <tbody>
            <?php
            $var_modal_editar = 1;
            $var_modal_excluir = 1;

                while($row_grupo_orcado = @oci_fetch_array($result_grupo_orcado)){  ?>  
                    
                <tr>
                    <td class='align-middle' style='text-align: center;'><?php echo @$row_grupo_orcado['DS_GRUPO_ORCADO']; ?></td>
                    <td class='align-middle' style='text-align: center;'><?php echo @$row_grupo_orcado['PERIODO']; ?></td>

                    <td class='align-middle' style='text-align: center;'>
                        <?php
                            echo str_replace('p', '.', str_replace('.', ',', str_replace(',','p',$row_grupo_orcado['VL_ORCADO']))); 
                        ?>                  
                    </td>
                    

                    <?php
                    if ($row_grupo_orcado['SN_ATIVO'] == 'S') { 
                                echo "<td class='text-center align-middle'>" .
                                "<a class='botoes' style='color: #3c3c3c;' href='consultas/proc_alterar_sn_ativo.php?id=" . 
                                $row_grupo_orcado['CD_GRUPO_ORCADO']  . "&sn_ativo=N&tabela=GRUPO_ORCADO&pagina=cadastro_grupo_orcado" .
                                 "' onclick=\"return confirm('Tem certeza que deseja desativar esse registro?');\">" . 
                                "<i class='fa fa-toggle-on' aria-hidden='true'></i>" . "</a>" . "</td>";
                            } 
                            else {
                                echo "<td class='text-center align-middle'>" . 
                                "<a class='botoes' style='color: #3c3c3c;' href='consultas/proc_alterar_sn_ativo.php?id=" .
                                $row_grupo_orcado['CD_GRUPO_ORCADO']  . "&sn_ativo=S&tabela=GRUPO_ORCADO&pagina=cadastro_grupo_orcado" . 
                                "' onclick=\"return confirm('Tem certeza que deseja ativar esse registro?');\">" . 
                                "<i class='fa fa-toggle-off' aria-hidden='true'></i>" . "</a>" . "</td>"; 
                            };?>
                        

                    <!--MODAL OPÇOES-->
                    <td class="align-middle" style="text-align: center !important;">
                        <?php include 'modals/grupo_orcado/modal_edit_grupo_orcado.php' ?>
                        <?php /* include 'model_excluir_setor.php'*/?>
                    </td>
                </tr>
                
                <?php 
                    $var_modal_editar = $var_modal_editar + 1;
                    $var_modal_excluir = $var_modal_excluir + 1;
                } ?>
        </tbody>           
    </table>
    </div>


<?php
    //RODAPE
    include 'rodape.php';
?>