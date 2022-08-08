<?php     

    //RECEBENDO POST
    $var_periodo = @$_POST["frm_cad_periodo"]; 
    $var_setor = @$_POST["filtro_setor"];

    $var_periodo = @substr($var_periodo,5,2) . '/' . substr($var_periodo,0,4);

    //echo $var_periodo; echo '</br>';
    //echo $_SESSION['periodo']; echo '</br>';

    if(isset($_POST["frm_cad_periodo"])){

        @$_SESSION['periodo'] = @$var_periodo;
        @$var_periodo_filtro = @substr($var_periodo,3,4) . '-' . @substr($var_periodo,0,2);
    
    }else{

        @$var_periodo = @$_SESSION['periodo'];
        @$var_periodo_filtro = @substr($_SESSION['periodo'],3,4) . '-' . @substr($_SESSION['periodo'],0,2);

    }
    
    //RECEBENDO SESSAO
    @$var_cd_usuario = @$_SESSION['usuarioLogin'];

?>


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

                        END COR_VARIACAO,
                        orcamento_contabil.fnc_retorna_txt_limite_caract(DS_JUSTIFICATIVA_1,3999) AS JUSTIFICA_1,
                        orcamento_contabil.fnc_retorna_txt_limite_caract(DS_JUSTIFICATIVA_2,3999) AS JUSTIFICA_2,
                        orcamento_contabil.fnc_retorna_txt_limite_caract(DS_JUSTIFICATIVA_3,3999) AS JUSTIFICA_3,
                        vrc.CD_CONTA_CONTABIL,
                        st.cd_usuario as USUARIO
                        FROM VW_RESULTADOS_CONSOLIDADOS vrc
                        LEFT JOIN orcamento_contabil.justificativa_contabil_clob just
                        ON just.cd_conta_contabil = vrc.CD_CONTA_CONTABIL
                        LEFT JOIN orcamento_contabil.setor st
                        ON vrc.CD_SETOR = st.cd_setor
                        WHERE vrc.PERIODO = '$var_periodo'
                        ";

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
    <th class="align-middle" style="text-align: center !important;"><span>Reduzido</span></th>
    <th class="align-middle" style="text-align: center !important;"><span>Classificação</span></th>
    <th class="align-middle" style="text-align: center !important;"><span>Descrição</span></th>
    <th class="align-middle" style="text-align: center !important;"><span>Grupo Orçado</span></th>
    <th class="align-middle" style="text-align: center !important;"><span>Orçado</span></th>
    <th class="align-middle" style="text-align: center !important;"><span>Realizado</span></th>
    <th class="align-middle" style="text-align: center !important;"><span>       Variação       </span></th>
    <th class="align-middle" style="text-align: center !important;"><span>   %Variação   </span></th>
    <th class="align-middle" style="text-align: center !important;"><span>   Justificativa   </span></th>
                
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
                    <th class="align-middle" style="text-align: center !important;"><span>TOTAL DESPESA</span></th>
                    <th class="align-middle" style="text-align: center !important;"><span></span></th>
                    <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_orcado_despesa, 2, ',', '.' ); ?></span></th>
                    <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_realizado_despesa, 2, ',', '.' ); ?></span></th>
                    <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_realizado_despesa - @$var_total_orcado_despesa, 2, ',', '.' ) . $var_seta_totalizador; ?></span></th></span></th>
                    <th class="align-middle" style="text-align: center !important;"><?php echo @number_format(((@$var_total_realizado_despesa / @$var_total_orcado_despesa)-1)*100, 2, ',', '.' ) . '%' . $var_seta_totalizador; ?></span></th></span></th>
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
            <td class='align-middle' style='text-align: center; background-color:<?php echo $color ?>!important;'><?php echo @$row_conta_contabil['DS_SETOR']; ?></td>
            <td class='align-middle' style='text-align: center; background-color:<?php echo $color ?>!important;'><?php echo @$row_conta_contabil['CD_CONTA_MV']; ?>
            <?php if (@$row_conta_contabil['SN_REPETIDO'] == 'S'){ echo '<i class="fas fa-redo"></i>'; } ?>
            </td>
            <td class='align-middle' style='text-align: center; background-color:<?php echo $color ?>!important;'><?php echo @$row_conta_contabil['CLASSIFICACAO_CONTABIL']; ?></td>
            <td class='align-middle' style='text-align: center; background-color:<?php echo $color ?>!important;'><?php echo @$row_conta_contabil['DS_CONTA']; ?></td>

            <!-- GRUPO ORÇADO -->
            <?php
                if(!isset($row_conta_contabil['CD_GRUPO_ORCADO'])){
                    echo "<td class='align-middle' style='text-align: center; '> - </td>";

                }elseif($var_group_orcado == @$row_conta_contabil['CD_GRUPO_ORCADO']){
                    echo "<td class='align-middle' style='text-align: center; font-size: 9px; background-color:".$color." !important;'> <i class='fas fa-quote-left'></i> </td>";

                }else{
                    echo "<td class='align-middle' style='text-align: center; background-color:".$color." !important;'>" .@$row_conta_contabil['DS_GRUPO_ORCADO']. "</td>";
                }

            ?>

            <!-- ORÇADO -->
            <?php

                if(!isset($row_conta_contabil['CD_GRUPO_ORCADO'])){
                    echo "<td class='align-middle' style='text-align: center;'>" . @number_format($row_conta_contabil['VL_ORCADO'], 2, ',', '.' ) . "</td>";

                }elseif($var_group_orcado == @$row_conta_contabil['CD_GRUPO_ORCADO']){
                    echo "<td class='align-middle' style='text-align: center; font-size: 9px; background-color:".$color." !important;'> <i class='fas fa-quote-left'></i> </td>";

                }else{

                    echo "<td class='align-middle' style='text-align: center; background-color:".$color." !important;'>" . @number_format($row_conta_contabil['VL_ORCADO'], 2, ',', '.' ). "</td>";
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
                        echo @number_format($row_conta_contabil['VL_VARIACAO'], 2, ',', '.' ); 
                    }elseif($var_group_orcado == @$row_conta_contabil['CD_GRUPO_ORCADO']){
                        echo "<i class='fas fa-quote-left' style='text-align: center; font-size: 9px;'></i>";
                    }else{
                        echo @number_format($row_conta_contabil['VL_VARIACAO_GRUPO_ORCADO'], 2, ',', '.' ); 
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

            <!--  JUSTIFICATIVA  -->

            <td class='align-middle' style="text-align: center;" >
                <?php 
                    if(!isset($row_conta_contabil['JUSTIFICA_1'])){
                        if($_SESSION['usuarioLogin'] == $row_conta_contabil['USUARIO']){
                            $cor_just = '#E05757';
                            $liberado = '';
                        }else{
                            $cor_just = 'gray';
                            $liberado = 'disabled';
                        }
                    }else{
                        $cor_just = '#3185c1';
                        $liberado = '';
                    }
                    ?>
                <button id="btn_modal_just" style="background-color: <?php echo $cor_just ?>!important; color: #fff" class="btn btn" onclick="justi(<?php echo $row_conta_contabil['CD_CONTA_CONTABIL'] ?>)" <?php echo $liberado ?>><i class="fas fa-comment-alt"></i></button>
                    <script>

                        function justi(cd_conta, just){
                            if(just != ''){
                                $.ajax({
                                            url: "resultados/gerencia/ajax_just_1.php",
                                            type: "POST",
                                            data: {
                                                cd_conta: cd_conta
                                                },
                                            cache: false,
                                            success: function(dataResult){
                                                document.getElementById("justficativa_1").value = dataResult;
                                            }
                                        });
                                        $.ajax({
                                            url: "resultados/gerencia/ajax_just_2.php",
                                            type: "POST",
                                            data: {
                                                cd_conta: cd_conta
                                                },
                                            cache: false,
                                            success: function(dataResult){
                                                document.getElementById("justficativa_2").value = dataResult;
                                            }
                                        });
                                        $.ajax({
                                            url: "resultados/gerencia/ajax_just_3.php",
                                            type: "POST",
                                            data: {
                                                cd_conta: cd_conta
                                                },
                                            cache: false,
                                            success: function(dataResult){
                                                document.getElementById("justficativa_3").value = dataResult;
                                            }
                                        });
                            }
                            $("#modaljustificativa").modal({
                                show: true
                            });

                            document.getElementById('cd_conta').value = cd_conta;
                        }

                        function cad_just(){
                            var cd_conta = document.getElementById('cd_conta').value;
                            var ds_just_1 = document.getElementById('justficativa_1').value;
                            var ds_just_2 = document.getElementById('justficativa_2').value;
                            var ds_just_3 = document.getElementById('justficativa_3').value;
                            $.ajax({
                                url: "resultados/gerencia/ajax_cad_just.php",
                                type: "POST",
                                data: {
                                    cd_conta: cd_conta,
                                    ds_just_1: ds_just_1,
                                    ds_just_2: ds_just_2,
                                    ds_just_3: ds_just_3
                                    },
                                cache: false,
                                success: function(dataResult){
                                    //alert(dataResult);
                                    document.location.reload(true);
                                }
                            });
                        }
                    </script>
            
            </td>  
            <div class="modal fade" id="modaljustificativa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> Justificativa </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if($_SESSION['usuarioLogin'] == $row_conta_contabil['USUARIO']){ ?>
                                Contextualização:
                                <textarea class="form-control" id="justficativa_1" rows="5" maxlength="3900"></textarea>
                                <div class="div_br"></div>
                                Ações Planejadas ou Implementadas:
                                <textarea class="form-control" id="justficativa_2" rows="5"  maxlength="3900"></textarea>
                                <div class="div_br"></div>
                                Necessidade de atualização de Orçado:
                                <textarea class="form-control" id="justficativa_3" rows="5"  maxlength="3900"></textarea>
                            <?php }else{ ?>
                                Contextualização:
                                <textarea class="form-control" id="justficativa_1" rows="5" disabled></textarea>
                                <div class="div_br"></div>
                                Ações Planejadas ou Implementadas:
                                <textarea class="form-control" id="justficativa_2" rows="5" disabled></textarea>
                                <div class="div_br"></div>
                                Necessidade de atualização de Orçado:
                                <textarea class="form-control" id="justficativa_3" rows="5" disabled></textarea>
                            <?php } ?>
                            <input type="hidden" id="cd_conta">
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                    <?php if($_SESSION['usuarioLogin'] == $row_conta_contabil['USUARIO']){ ?>
                    <button type="button" class="btn btn-primary" onclick="cad_just()"><i class="fas fa-save"></i> Salvar</button>
                    <?php } ?>
                </div>
                </div>
            </div>
            </div>
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
                    <th class="align-middle" style="text-align: center !important;"><span>TOTAL DESPESA</span></th>
                    <th class="align-middle" style="text-align: center !important;"><span></span></th>
                    <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_orcado_despesa, 2, ',', '.' ); ?></span></th>
                    <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_realizado_despesa, 2, ',', '.' ); ?></span></th>
                    <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_realizado_despesa - @$var_total_orcado_despesa, 2, ',', '.' ) . $var_seta_totalizador; ?></span></th></span></th>
                    <th class="align-middle" style="text-align: center !important;"><?php echo @number_format(((@$var_total_realizado_despesa / @$var_total_orcado_despesa)-1)*100, 2, ',', '.' ) . '%' . $var_seta_totalizador; ?></span></th></span></th>
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
                <th class="align-middle" style="text-align: center !important;"><span>TOTAL RECEITA</span></th>
                <th class="align-middle" style="text-align: center !important;"><span></span></th>
                <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_orcado_receita, 2, ',', '.' ); ?></span></th>
                <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_realizado_receita, 2, ',', '.' ); ?></span></th>
                <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_realizado_receita - @$var_total_orcado_receita, 2, ',', '.' ) . $var_seta_totalizador; ?></span></th></span></th>
                <th class="align-middle" style="text-align: center !important;"><?php echo @number_format(((@$var_total_realizado_receita / @$var_total_orcado_receita)-1)*100, 2, ',', '.' ) . '%' . $var_seta_totalizador; ?></span></th></span></th>
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
            <th class="align-middle" style="text-align: center !important;"><span>TOTAL RESULTADO</span></th>
            <th class="align-middle" style="text-align: center !important;"><span></span></th>
            <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_orcado, 2, ',', '.' ); ?></span></th>
            <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_realizado, 2, ',', '.' ); ?></span></th>
            <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$var_total_realizado - @$var_total_orcado, 2, ',', '.' ) . $var_seta_totalizador; ?></span></th></span></th>
            <th class="align-middle" style="text-align: center !important;"><?php echo @number_format(((@$var_total_realizado / @$var_total_orcado)-1)*100, 2, ',', '.' ) . '%' . $var_seta_totalizador; ?></span></th></span></th>
            <th class="align-middle" style="text-align: center !important;"><span></span></th>
        </tr> 

</tbody>           
</table>
</div>