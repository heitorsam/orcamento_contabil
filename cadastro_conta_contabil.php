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
    $var_setor = @$_POST["filtro_setor"];

    $var_periodo = @substr($var_periodo,5,2) . '/' . substr($var_periodo,0,4);

    //echo $var_periodo; echo '</br>';
    //echo $_SESSION['periodo']; echo '</br>';

    if(isset($_POST["frm_periodo"])){

        @$_SESSION['periodo'] = @$var_periodo;
        @$_SESSION['cd_setor'] = @$_POST["filtro_setor"];
        @$var_periodo_filtro = @substr($var_periodo,3,4) . '-' . @substr($var_periodo,0,2);
    
    }else{

        @$var_periodo = @$_SESSION['periodo'];
        @$_POST["filtro_setor"] = @$_SESSION['cd_setor'];
        @$var_periodo_filtro = @substr($_SESSION['periodo'],3,4) . '-' . @substr($_SESSION['periodo'],0,2);

    }
    
    //RECEBENDO SESSAO
    @$var_cd_usuario = @$_SESSION['usuarioLogin'];

?>

    <!--TITULO-->
    <h11><i class="fa fa-archive"></i> Cadastro Conta Contábil</h11>
    <span class="espaco_pequeno" style="width: 6px;" ></span>
    <h27> <a href="home.php" style="color: #444444; text-decoration: none;"> <i class="fa fa-reply" aria-hidden="true"></i> Voltar </a> </h27> 
    
    <div class="div_br"> </div>       

    <!--MENSAGENS-->
    <?php
        include 'js/mensagens.php';
        include 'consultas/conta_contabil/sql_listar_setor.php'; 
    ?>

    <form action="cadastro_conta_contabil.php" method="POST">   
        <div class="row">
            <div class="col-md-3" > 
                Periodo:
                <input type="month" class="form-control" name="frm_periodo" value="<?php echo $var_periodo_filtro; ?>"placeholder="Digite o período" required>
            </div>

            <div class="col-md-3" style="padding: 0px !important;"> 
                <?php include 'filtros/setor.php'; ?>
            </div>

            <div class="col-md-1" style="padding-right: 0px !important;"> 
                <br>
                <!-- BOTAO PESQUISAR -->
                <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button> 
            </div>
    </form>
            <div class="col-md-4" style="padding: 0px !important;"> 
                <br>
                <?php 
                
                    include 'modals/conta_contabil/modal_cadastro_conta_contabil.php';
                ?>
                <?php 
                    include 'modals/conta_contabil/modal_pendencias.php';
                ?>
            </div>
        </div>
  
    <div class="div_br"> </div>  

    <?php 

        //////////////////////////////
        ////LISTAR CONTA CONTABIL/////
        //////////////////////////////    
 
        $lista_conta_contabil = "SELECT vrc.*, 
                                    (SELECT CASE
                                WHEN COUNT(*) > 1 THEN 'S'
                                ELSE 'N'
                            END 
                        FROM VW_RESULTADOS_CONSOLIDADOS sub
                        WHERE sub.CD_CONTA_MV = vrc.CD_CONTA_MV
                        AND sub.PERIODO = vrc.PERIODO       
                        ) AS SN_REPETIDO,
                        (vrc.VL_REALIZADO - vrc.VL_ORCADO) AS VL_VARIACAO,


                        (((vrc.VL_REALIZADO / NULLIF(vrc.VL_ORCADO,0)))-1)*100 AS VL_PORC_VARIACAO,



                        (SELECT SUM(aux.VL_REALIZADO)
                        FROM VW_RESULTADOS_CONSOLIDADOS aux
                        WHERE aux.CD_GRUPO_ORCADO = vrc.CD_GRUPO_ORCADO
                        AND aux.PERIODO = '$var_periodo'
                        ) - vrc.VL_ORCADO AS VL_VARIACAO_GRUPO_ORCADO,

                        (((NULLIF((SELECT SUM(aux.VL_REALIZADO)
                        FROM VW_RESULTADOS_CONSOLIDADOS aux
                        WHERE aux.CD_GRUPO_ORCADO = vrc.CD_GRUPO_ORCADO
                        AND aux.PERIODO = '$var_periodo'),0)) / NULLIF(vrc.VL_ORCADO,0) - 1) * 100) AS VL_PROC_VARIACAO_GRUPO_ORCADO,

                        CASE

                            WHEN vrc.VL_ORCADO - (SELECT SUM(aux.VL_REALIZADO)
                                FROM VW_RESULTADOS_CONSOLIDADOS aux
                                WHERE aux.CD_GRUPO_ORCADO = vrc.CD_GRUPO_ORCADO
                                AND aux.PERIODO = '$var_periodo'
                                ) > 0
                            AND vrc.CLASSIFICACAO_CONTABIL = 'DESPESA'
                            AND vrc.CD_GRUPO_ORCADO > 0
                            THEN 'green'  

                            WHEN vrc.VL_ORCADO - (SELECT SUM(aux.VL_REALIZADO)
                                FROM VW_RESULTADOS_CONSOLIDADOS aux
                                WHERE aux.CD_GRUPO_ORCADO = vrc.CD_GRUPO_ORCADO
                                AND aux.PERIODO = '$var_periodo'
                                ) < 0
                            AND vrc.CLASSIFICACAO_CONTABIL = 'DESPESA'
                            AND vrc.CD_GRUPO_ORCADO > 0
                            THEN 'red' 

                            WHEN vrc.VL_ORCADO  - (SELECT SUM(aux.VL_REALIZADO)
                                FROM VW_RESULTADOS_CONSOLIDADOS aux
                                WHERE aux.CD_GRUPO_ORCADO = vrc.CD_GRUPO_ORCADO
                                AND aux.PERIODO = '$var_periodo'
                                ) < 0
                            AND vrc.CLASSIFICACAO_CONTABIL = 'RECEITA'
                            AND vrc.CD_GRUPO_ORCADO > 0
                            THEN 'green' 


                            WHEN vrc.VL_ORCADO  - (SELECT SUM(aux.VL_REALIZADO)
                                FROM VW_RESULTADOS_CONSOLIDADOS aux
                                WHERE aux.CD_GRUPO_ORCADO = vrc.CD_GRUPO_ORCADO
                                AND aux.PERIODO = '$var_periodo'
                                ) > 0
                            AND vrc.CLASSIFICACAO_CONTABIL = 'RECEITA'
                            AND vrc.CD_GRUPO_ORCADO > 0
                            THEN 'red'

                            WHEN (vrc.VL_REALIZADO - vrc.VL_ORCADO) < 0 AND vrc.CLASSIFICACAO_CONTABIL = 'DESPESA' THEN 'green'
                            WHEN (vrc.VL_REALIZADO - vrc.VL_ORCADO) > 0 AND vrc.CLASSIFICACAO_CONTABIL = 'DESPESA' THEN 'red'
                            WHEN (vrc.VL_REALIZADO - vrc.VL_ORCADO) < 0 AND vrc.CLASSIFICACAO_CONTABIL = 'RECEITA' THEN 'red'
                            WHEN (vrc.VL_REALIZADO - vrc.VL_ORCADO) > 0 AND vrc.CLASSIFICACAO_CONTABIL = 'RECEITA' THEN 'green'
                            ELSE 'grey'

                        END COR_VARIACAO                                 
                                 FROM orcamento_contabil.VW_RESULTADOS_CONSOLIDADOS vrc
                                 WHERE vrc.PERIODO = '$var_periodo'";

                                 if($var_setor <> 'Todos'){
                                    $lista_conta_contabil .= " AND vrc.CD_SETOR = '$var_setor'";
                                 }

                                 $lista_conta_contabil .= " ORDER BY vrc.CLASSIFICACAO_CONTABIL ASC, vrc.DS_SETOR ASC, vrc.ORDEM ASC, vrc.CD_CONTA_MV ASC";

        $result_conta_contabil = oci_parse($conn_ora, $lista_conta_contabil);

        @oci_execute($result_conta_contabil);
  
    ?>

    <!--TABELA DE RESULTADOS -->
    <div class="table-responsive col-md-12">
    <table class="table table-fixed table-hover table-striped " cellspacing="0" cellpadding="0">

        <thead><tr>
            <!--COLUNAS-->
            <th class="align-middle" style="text-align: center !important;"><span>Setor</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>Ordem</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>Reduzido</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>Classificação</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>Descrição</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>Grupo Orçado</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>Orçado</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>Realizado</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>       Variação       </span></th>
            <th class="align-middle" style="text-align: center !important;"><span>   %Variação   </span></th>
            <th class="align-middle" style="text-align: center !important;"><span>  Editar Realizado  </span></th>
            <th class="align-middle" style="text-align: center !important;"><span>    Editar Conta    </span></th>

        </tr></thead>            

        <tbody>
            <?php
            $var_modal_editar = 1;
            $var_modal_excluir = 1;
            $var_group_orcado = 'Inicio';
            $var_desc_mv = 'Inicio';
            $var_ult_classificacao = 'Desconsiderar';
            $var_ult_grupo_orcado = 0;
            $var_total_orcado_despesa = 0;
            $var_total_realizado_despesa = 0;
            $var_total_orcado_receita = 0;
            $var_total_realizado_receita = 0;
            $var_cont_despesa = 0;
            $var_cont_receita = 0;
            $var_final_orcado_despesa = 0;
            $var_final_realizado_despesa = 0;
            $var_final_orcado_receita = 0;
            $var_final_realizado_receita = 0;  
            $var_ativa_despesa = 'S';           

            while($row_conta_contabil = @oci_fetch_array($result_conta_contabil)){
                
                if(isset($row_conta_contabil['CD_GRUPO_ORCADO']) AND $var_ult_grupo_orcado == $row_conta_contabil['CD_GRUPO_ORCADO']){
                      
                    //APENAS SOMA REALIZADO
                    if($row_conta_contabil['CLASSIFICACAO_CONTABIL'] == 'DESPESA'){
                        
                        @$var_total_realizado_despesa += @$row_conta_contabil['VL_REALIZADO'];
                        
                    }

                    if($row_conta_contabil['CLASSIFICACAO_CONTABIL'] == 'RECEITA'){
                         
                        @$var_total_realizado_receita += @$row_conta_contabil['VL_REALIZADO'];
                    }

                }else{
                    
                    //SOMA ORCADO E REALIZADO
                    if($row_conta_contabil['CLASSIFICACAO_CONTABIL'] == 'DESPESA'){
                         $var_cont_despesa = $var_cont_despesa + 1;
                        @$var_total_orcado_despesa += @$row_conta_contabil['VL_ORCADO'];
                        @$var_total_realizado_despesa += @$row_conta_contabil['VL_REALIZADO'];

                    }

                    if($row_conta_contabil['CLASSIFICACAO_CONTABIL'] == 'RECEITA'){
                         $var_cont_receita = $var_cont_receita + 1;
                        @$var_total_orcado_receita += @$row_conta_contabil['VL_ORCADO'];
                        @$var_total_realizado_receita += @$row_conta_contabil['VL_REALIZADO'];

                    }

                }

                //CALCULANDO DADOS     
                $var_seta_totalizador = ' <i class="fas fa-arrow-down"></i>';

                if($var_ult_classificacao == 'DESPESA'){
        
                    @$var_total_orcado = @$var_total_orcado_despesa; 
                    @$var_total_realizado = @$var_total_realizado_despesa;

                    if(@$var_total_realizado < @$var_total_orcado){

                        $var_seta_totalizador = ' <i class="fas fa-arrow-up"></i>';

                    }

                }else{

                    //RECEITA
                    @$var_total_orcado_receita = @$var_total_orcado_receita; 
                    @$var_total_realizado_receita = @$var_total_realizado_receita;

                    if(@$var_total_realizado > @$var_total_orcado){

                        $var_seta_totalizador = ' <i class="fas fa-arrow-up"></i>';

                    }

                }   

                if($var_ult_classificacao <> $row_conta_contabil['CLASSIFICACAO_CONTABIL']
                        AND $var_ult_classificacao <> 'Desconsiderar'){                                  
                        
                ?>
                        <!-- TOTALIZADOR DESPESA -->
                        <tr style="background-color: #3185c1; color: #ffffff;">
                            <!--COLUNAS-->
                            <th class="align-middle" style="text-align: center !important;"><span></span></th>
                            <th class="align-middle" style="text-align: center !important;"><span></span></th>
                            <th class="align-middle" style="text-align: center !important;"><span></span></th>
                            <th class="align-middle" style="text-align: center !important;"><span></span></th>
                            <th class="align-middle" style="text-align: center !important;"><span>TOTAL DESPESA</span></th>
                            <th class="align-middle" style="text-align: center !important;"><span></span></th>
                            <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_orcado_despesa, 2, ',', '.' ); ?></span></th>
                            <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_realizado_despesa, 2, ',', '.' ); ?></span></th>
                            <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format((@$var_total_realizado_despesa - @$var_total_orcado_despesa), 2, ',', '.' ) . $var_seta_totalizador; ?></span></th></span></th>
                            <th class="align-middle" style="text-align: center !important;"><?php echo @number_format((((@$var_total_realizado_despesa / @$var_total_orcado_despesa)-1)*100), 2, ',', '.' ) . '%' . $var_seta_totalizador; ?></span></th></span></th>
                            <th class="align-middle" style="text-align: center !important;"><span></span></th>
                            <th class="align-middle" style="text-align: center !important;"><span></span></th>
                        </tr> 
                        
                    <?php 


                        $var_ativa_despesa = 'N';  
                    
                    }
                                    
                    $var_final_orcado_despesa = $var_total_orcado_despesa;
                    $var_final_realizado_despesa = $var_total_realizado_despesa;

                    $var_ult_classificacao = $row_conta_contabil['CLASSIFICACAO_CONTABIL']; 

                    $var_ult_grupo_orcado = $row_conta_contabil['CD_GRUPO_ORCADO']; 

                ?>

                <?php

                    if(!isset($row_conta_contabil['CD_GRUPO_ORCADO'])){
                        $color = 'rgba(0,0,0,0)';
                    }else{
                        $color = '#e0edfa'; 
                    }
                    
                ?>

                <tr>
                    <td class='align-middle' id="CD_SETOR<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>" style='text-align: center; background-color:<?php echo $color ?>!important;' ondblclick="fnc_editar_campo('orcamento_contabil.conta_contabil', 'CD_SETOR', '<?php echo @$row_conta_contabil['DS_SETOR']; ?>', 'CD_CONTA_CONTABIL', '<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>', 'SELECT DS_SETOR AS COLUNA_DS,CD_SETOR AS COLUNA_VL FROM orcamento_contabil.SETOR')" ><?php echo @$row_conta_contabil['DS_SETOR']; ?></td>
                    <td class='align-middle' id="ORDEM<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>" style='text-align: center; background-color:<?php echo $color ?>!important;' ondblclick="fnc_editar_campo('orcamento_contabil.conta_contabil', 'ORDEM', '<?php echo @$row_conta_contabil['ORDEM']; ?>', 'CD_CONTA_CONTABIL', '<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>', '')" ><?php echo @$row_conta_contabil['ORDEM']; ?></td>
                    <td class='align-middle' style='text-align: center; background-color:<?php echo $color ?>!important;'><?php echo @$row_conta_contabil['CD_CONTA_MV']; ?>
                    <?php if (@$row_conta_contabil['SN_REPETIDO'] == 'S'){ echo '<i class="fas fa-redo"></i>'; } ?>
                    </td>
                    <td class='align-middle' style='text-align: center; background-color:<?php echo $color ?>!important;'><?php echo @$row_conta_contabil['CLASSIFICACAO_CONTABIL']; ?></td>
                    <td class='align-middle' style='text-align: center; background-color:<?php echo $color ?>!important;'><?php echo @$row_conta_contabil['DS_CONTA']; ?></td>

                    <!-- GRUPO ORÇADO -->
                    <?php
                        if(!isset($row_conta_contabil['CD_GRUPO_ORCADO'])){ ?>
                            <td class='align-middle' id="CD_GRUPO_ORCADO<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>" style='text-align: center; ' ondblclick="fnc_editar_campo('orcamento_contabil.conta_contabil', 'CD_GRUPO_ORCADO', '<?php echo @$row_conta_contabil['CD_GRUPO_ORCADO']; ?>', 'CD_CONTA_CONTABIL', '<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>','SELECT DS_GRUPO_ORCADO AS COLUNA_DS,CD_GRUPO_ORCADO AS COLUNA_VL FROM orcamento_contabil.grupo_orcado WHERE PERIODO = ~<?php echo @$row_conta_contabil['PERIODO']; ?>~')" > - </td>
                    <?php
                        }elseif($var_group_orcado == @$row_conta_contabil['CD_GRUPO_ORCADO']){?>
                            <td class='align-middle' id="CD_GRUPO_ORCADO<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>" style='text-align: center; font-size: 9px; background-color:<?php echo $color; ?> !important;' ondblclick="fnc_editar_campo('orcamento_contabil.conta_contabil', 'CD_GRUPO_ORCADO', '<?php echo @$row_conta_contabil['CD_GRUPO_ORCADO']; ?>', 'CD_CONTA_CONTABIL', '<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>','SELECT DS_GRUPO_ORCADO AS COLUNA_DS,CD_GRUPO_ORCADO AS COLUNA_VL FROM orcamento_contabil.grupo_orcado WHERE PERIODO = ~<?php echo @$row_conta_contabil['PERIODO']; ?>~')" > <i class='fas fa-quote-left'></i> </td>
                    <?php
                        }else{                                                      
                    ?>                            
                            <td class='align-middle' id="CD_GRUPO_ORCADO<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>" style='text-align: center; background-color: <?php echo $color; ?> !important;' ondblclick="fnc_editar_campo('orcamento_contabil.conta_contabil', 'CD_GRUPO_ORCADO', '<?php echo @$row_conta_contabil['CD_GRUPO_ORCADO']; ?>', 'CD_CONTA_CONTABIL', '<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>','SELECT DS_GRUPO_ORCADO AS COLUNA_DS,CD_GRUPO_ORCADO AS COLUNA_VL FROM orcamento_contabil.grupo_orcado WHERE PERIODO = ~<?php echo @$row_conta_contabil['PERIODO']; ?>~')" ><?php echo @$row_conta_contabil['DS_GRUPO_ORCADO']; ?></td>
                    <?php    
                        }

                    ?>

                    <!-- ORÇADO -->
                    <?php
        
                        if(!isset($row_conta_contabil['CD_GRUPO_ORCADO'])){?>
                            <td class='align-middle' id="VL_ORCADO<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>" style='text-align: center;' ondblclick="fnc_editar_campo('orcamento_contabil.conta_contabil', 'VL_ORCADO', '<?php echo @$row_conta_contabil['VL_ORCADO']; ?>', 'CD_CONTA_CONTABIL', '<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>','','1')"><?php echo @number_format($row_conta_contabil['VL_ORCADO'], 2, ',', '.' ); ?></td>
                    <?php        
                        }elseif($var_group_orcado == @$row_conta_contabil['CD_GRUPO_ORCADO']){?>
                            <td class='align-middle' id="VL_ORCADO<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>" style='text-align: center; font-size: 9px; background-color: <?PHP echo $color; ?> !important;' ondblclick="fnc_editar_campo('orcamento_contabil.conta_contabil', 'VL_ORCADO', '<?php echo @$row_conta_contabil['VL_ORCADO']; ?>', 'CD_CONTA_CONTABIL', '<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>','','1')"> <i class='fas fa-quote-left'></i> </td>
                    <?php
                        }else{?>

                            <td class='align-middle' id="VL_ORCADO<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>" style='text-align: center; font-size: 9px; background-color: <?PHP echo $color; ?> !important;' ondblclick="fnc_editar_campo('orcamento_contabil.conta_contabil', 'VL_ORCADO', '<?php echo @$row_conta_contabil['VL_ORCADO']; ?>', 'CD_CONTA_CONTABIL', '<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>','','1')"> <?php echo @number_format($row_conta_contabil['VL_ORCADO'], 2, ',', '.' ); ?></td>
                    <?php
                        }

                        
                    ?>

                    <!-- REALIZADO -->
                    <td class='align-middle' style='text-align: center; background-color:<?php echo $color ?>!important;'><?php echo @number_format($row_conta_contabil['VL_REALIZADO'], 2, ',', '.' ); ?> 
                        <?php if (@$row_conta_contabil['SN_MANUAL'] == 'S'){ echo '<i class="far fa-keyboard"></i>' ; } ?> 
                    </td>  

                    <!-- VARIAÇÃO -->
                  
                    <td class='align-middle' style='text-align: center; background-color:<?php echo $color ?>!important;'> 
                        <?php 
                            if(!isset($row_conta_contabil['CD_GRUPO_ORCADO'])){                                
                                echo @number_format(($row_conta_contabil['VL_VARIACAO']), 2, ',', '.' ); 
                            }elseif($var_group_orcado == @$row_conta_contabil['CD_GRUPO_ORCADO']){
                                echo "<i class='fas fa-quote-left' style='text-align: center; font-size: 9px;'></i>";
                            }else{
                                echo @number_format(($row_conta_contabil['VL_VARIACAO_GRUPO_ORCADO']), 2, ',', '.' ); 
                            }
                        ?>
                    </td>  

                    <!-- % VARIAÇÃO -->

                    <td class='align-middle'style="text-align: center; background-color:<?php echo $color ?>!important; color:<?php echo $row_conta_contabil['COR_VARIACAO'] ?>!important;" >
                        <?php 
                            if(!isset($row_conta_contabil['CD_GRUPO_ORCADO'])){
                                echo @number_format($row_conta_contabil['VL_PORC_VARIACAO'], 2, ',', '.' ) . '%'; 
                            }elseif($var_group_orcado == @$row_conta_contabil['CD_GRUPO_ORCADO']){
                                echo "<i class='fas fa-quote-left' style='text-align: center; font-size: 9px; color: black;'></i>";
                            }else{
                                echo @number_format($row_conta_contabil['VL_PROC_VARIACAO_GRUPO_ORCADO'], 2, ',', '.' ) . '%'; 
                            }   
                        ?>

                    </td>  

                                        
                    <!-- MODAL EDITAR/EXCLUIR REALIZADO -->
                    <td class="align-middle" style="text-align: center !important; background-color:<?php echo $color ?>!important;">

                        <!-- MODAL EDITAR REALIZADO -->
                        <?php include 'modals/conta_contabil/modal_edit_realizado.php'?>


                        <!-- EXCLUIR REALIZADO -->
                        <?php if (@$row_conta_contabil['SN_MANUAL'] == 'S'){ ?>

                            <a class='btn btn-danger btn-xs' onclick="return confirm('Tem certeza que deseja excluir o realizado?')"
                            href='consultas/conta_contabil/sql_exclui_realizado.php?cd_conta_contabil=<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>'>
                                <i class='fas fa-trash'></i>
                            </a>

                        <?php } else { ?> 

                            <a class='btn btn-danger btn-xs' style='background-color: #d7d7d7 !important; border-color: #d7d7d7 !important;'>
                                <i class='fas fa-trash'></i>
                            </a>
                                                    
                        <?php } ?>                        

                    </td>

                    <!--MODAL EDITAR/EXCLUIR CONTA-->
                    <td class="align-middle" style="text-align: center !important; background-color:<?php echo $color ?>!important;">
                        
                        <!--MODAL EDITAR CONTA-->
                        <?php //include 'modals/conta_contabil/modal_edit_conta_contabil.php'?>

                        <!-- EXCLUIR REALIZADO -->
                        <a class='btn btn-danger btn-xs' onclick="return confirm('Tem certeza que deseja excluir o realizado?')"
                            href='consultas/conta_contabil/sql_exclui_conta_contabil.php?cd_conta_contabil=<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>'>
                                <i class='fas fa-trash'></i>
                        </a>

                    </td>

                </tr>
                
                <?php 

                    $var_modal_editar = $var_modal_editar + 1;
                    $var_modal_excluir = $var_modal_excluir + 1;
                    $var_group_orcado = @$row_conta_contabil['CD_GRUPO_ORCADO'];
                    
                } ?>

                <?php

                    $var_seta_totalizador = ' <i class="fas fa-arrow-down"></i>';

                    @$var_total_orcado = @$var_total_orcado_receita; 
                    @$var_total_realizado = @$var_total_realizado_receita;

                    if(@$var_total_realizado > @$var_total_orcado){

                        $var_seta_totalizador = ' <i class="fas fa-arrow-up"></i>';

                    }

                    if($var_ativa_despesa == 'S' AND $var_cont_despesa >= 1){ 
                        
                        @$var_total_orcado = @$var_final_orcado_despesa; 
                        @$var_total_realizado = @$var_total_realizado_receita;

                        if(@$var_total_realizado < @$var_total_orcado){

                            $var_seta_totalizador = ' <i class="fas fa-arrow-up"></i>';


                        }
                        
                ?>
                        <!-- TOTALIZADOR DESPESA -->
                        <tr style="background-color: #3185c1; color: #ffffff;">
                            <!--COLUNAS-->
                            <th class="align-middle" style="text-align: center !important;"><span></span></th>
                            <th class="align-middle" style="text-align: center !important;"><span></span></th>
                            <th class="align-middle" style="text-align: center !important;"><span></span></th>
                            <th class="align-middle" style="text-align: center !important;"><span></span></th>
                            <th class="align-middle" style="text-align: center !important;"><span>TOTAL DESPESA</span></th>
                            <th class="align-middle" style="text-align: center !important;"><span></span></th>
                            <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_orcado_despesa, 2, ',', '.' ); ?></span></th>
                            <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_realizado_despesa, 2, ',', '.' ); ?></span></th>
                            <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_realizado_despesa - @$var_total_orcado_despesa, 2, ',', '.' ) . $var_seta_totalizador; ?></span></th></span></th>
                            <th class="align-middle" style="text-align: center !important;"><?php echo @number_format(((@$var_total_realizado_despesa / @$var_total_orcado_despesa)-1)*100, 2, ',', '.' ) . '%' . $var_seta_totalizador; ?></span></th></span></th>
                            <th class="align-middle" style="text-align: center !important;"><span></span></th>
                            <th class="align-middle" style="text-align: center !important;"><span></span></th>
                        </tr> 
                        
                    <?php 

                            $var_final_orcado_despesa = $var_total_orcado;
                            $var_final_realizado_despesa = $var_total_realizado;

                    ?>

                <?php }
                    
                ?>

                    <!-- TOTALIZADOR RECEITA -->
                    <tr style="background-color: #3185c1; color: #ffffff;">
                        <!--COLUNAS-->
                        <th class="align-middle" style="text-align: center !important;"><span></span></th>
                        <th class="align-middle" style="text-align: center !important;"><span></span></th>
                        <th class="align-middle" style="text-align: center !important;"><span></span></th>
                        <th class="align-middle" style="text-align: center !important;"><span></span></th>
                        <th class="align-middle" style="text-align: center !important;"><span>TOTAL RECEITA</span></th>
                        <th class="align-middle" style="text-align: center !important;"><span></span></th>
                        <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_orcado_receita, 2, ',', '.' ); ?></span></th>
                        <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_realizado_receita, 2, ',', '.' ); ?></span></th>
                        <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format((@$var_total_realizado_receita - @$var_total_orcado_receita), 2, ',', '.' ) . $var_seta_totalizador; ?></span></th></span></th>
                        <th class="align-middle" style="text-align: center !important;"><?php echo @number_format((((@$var_total_realizado_receita / @$var_total_orcado_receita)-1)*100), 2, ',', '.' ) . '%' . $var_seta_totalizador; ?></span></th></span></th>
                        <th class="align-middle" style="text-align: center !important;"><span></span></th>
                        <th class="align-middle" style="text-align: center !important;"><span></span></th>
                    </tr> 
                
                <?php 

                        $var_final_orcado_receita = $var_total_orcado_receita;
                        $var_final_realizado_receita = $var_total_realizado_receita; 

                        $var_seta_totalizador = ' <i class="fas fa-arrow-down"></i>';

                        @$var_total_orcado = @$var_final_orcado_receita - @$var_final_orcado_despesa; 
                        @$var_total_realizado = @$var_final_realizado_receita - @$var_final_realizado_despesa;

                        if(@$var_total_realizado > @$var_total_orcado){

                            $var_seta_totalizador = ' <i class="fas fa-arrow-up"></i>';

                        }

                ?>

                 <!-- TOTALIZADOR RESULTADO -->

                 <tr style="background-color: #0271c1; color: #ffffff;">
                    <!--COLUNAS-->
                    <th class="align-middle" style="text-align: center !important;"><span></span></th>
                    <th class="align-middle" style="text-align: center !important;"><span></span></th>
                    <th class="align-middle" style="text-align: center !important;"><span></span></th>
                    <th class="align-middle" style="text-align: center !important;"><span></span></th>
                    <th class="align-middle" style="text-align: center !important;"><span>TOTAL RESULTADO</span></th>
                    <th class="align-middle" style="text-align: center !important;"><span></span></th>
                    <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_orcado, 2, ',', '.' ); ?></span></th>
                    <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_realizado, 2, ',', '.' ); ?></span></th>
                    <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format((@$var_total_realizado - @$var_total_orcado), 2, ',', '.' ) . $var_seta_totalizador; ?></span></th></span></th>
                    <th class="align-middle" style="text-align: center !important;"><?php echo @number_format((((@$var_total_realizado / @$var_total_orcado)-1)*100), 2, ',', '.' ) . '%' . $var_seta_totalizador; ?></span></th></span></th>
                    <th class="align-middle" style="text-align: center !important;"><span></span></th>
                    <th class="align-middle" style="text-align: center !important;"><span></span></th>
                </tr> 

        </tbody>           
    </table>
    </div>

<?php

    //JAVASCRIPT EDITAR CAMPOS
    include 'funcoes/js_editar_campos.php';

    //RODAPE
    include 'rodape.php';
?>