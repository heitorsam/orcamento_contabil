<?php     

    //RECEBENDO POST
    $var_periodo = $_POST["frm_cad_periodo"]; 
    $var_setor = $_POST["filtro_setor"];
    $reduzido = $_POST['jv_reduzido'];

    $var_periodo = @substr($var_periodo,5,2) . '/' . substr($var_periodo,0,4);

    //echo $var_periodo; echo '</br>';
    //echo $_SESSION['periodo']; echo '</br>';

    if(isset($_POST["frm_cad_periodo"])){

        @$_SESSION['periodo'] = @$var_periodo;
        @$_SESSION['cd_setor'] = @$_POST["jv_setor"];
        @$var_periodo_filtro = @substr($var_periodo,3,4) . '-' . @substr($var_periodo,0,2);
    
    }else{

        @$var_periodo = @$_SESSION['periodo'];
        @$_POST["jv_filtro_setor"] = @$_SESSION['cd_setor'];
        @$var_periodo_filtro = @substr($_SESSION['periodo'],3,4) . '-' . @substr($_SESSION['periodo'],0,2);

    }

    
    //RECEBENDO SESSAO
    @$var_cd_usuario = @$_SESSION['usuarioLogin'];

?>

   
    <?php
        include 'js/mensagens.php';
        include 'consultas/conta_contabil/sql_listar_setor.php'; 
    ?>

  
    <div class="div_br"> </div>  

    <?php 

        //////////////////////////////////////////
        ////LISTAR TOTALIZADOR CONTA CONTABIL/////
        //////////////////////////////////////////
 
       $lista_totalizador = "SELECT res.PERIODO,
                             CASE 
                                 WHEN res.CLASSIFICACAO_CONTABIL = 'RESULTADO' THEN 1
                                 WHEN res.CLASSIFICACAO_CONTABIL = 'RECEITA' THEN 2
                                 WHEN res.CLASSIFICACAO_CONTABIL = 'DESPESA' THEN 3
                             END AS ORDEM,
                             CASE 
                                 WHEN res.CLASSIFICACAO_CONTABIL = 'RESULTADO' AND res.VL_REALIZADO >= res.VL_ORCADO THEN ' <i class=||fas fa-arrow-up||></i>'
                                 WHEN res.CLASSIFICACAO_CONTABIL = 'RECEITA' AND res.VL_REALIZADO >= res.VL_ORCADO THEN ' <i class=||fas fa-arrow-up||></i>'
                                 WHEN res.CLASSIFICACAO_CONTABIL = 'DESPESA' AND res.VL_REALIZADO <= res.VL_ORCADO THEN ' <i class=||fas fa-arrow-up||></i>'
                                 ELSE ' <i class=||fas fa-arrow-down||></i>'
                             END AS SETA,
                             res.CLASSIFICACAO_CONTABIL,
                             res.VL_ORCADO, res.VL_REALIZADO,
                             (res.VL_REALIZADO - res.VL_ORCADO)AS VARIACAO,
                             (ROUND(((res.VL_REALIZADO / NULLIF(res.VL_ORCADO,0)) - 1) * 100,2)) AS PORC_VARIACAO
                             
                             
                             FROM(

                             SELECT DISTINCT tt.PERIODO, 'RESULTADO' AS CLASSIFICACAO_CONTABIL, 
                             

                             (SELECT SUM(aux.VL_ORCADO) AS VL_ORCADO
                                 FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO aux 
                                 WHERE aux.PERIODO = tt.PERIODO";

                                if($var_setor <> 'Todos'){
                                    
                                    $lista_totalizador .= " AND aux.CD_SETOR = '$var_setor'";
                                }

                                 
                                $lista_totalizador .= " AND aux.CLASSIFICACAO_CONTABIL = 'RECEITA')
                                 
                                 -
                                 
                                 (SELECT SUM(aux.VL_ORCADO) AS VL_ORCADO
                                 FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO aux 
                                 WHERE aux.PERIODO = tt.PERIODO";

                                 if($var_setor <> 'Todos'){
                                    
                                    $lista_totalizador .= " AND aux.CD_SETOR = '$var_setor'";
                                }

                                 
                                $lista_totalizador .= " AND aux.CLASSIFICACAO_CONTABIL = 'DESPESA') AS VL_ORCADO,
                                 

                             (SELECT SUM(aux.VL_REALIZADO) AS VL_REALIZADO
                                 FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO aux 
                                 WHERE aux.PERIODO = tt.PERIODO";

                                if($var_setor <> 'Todos'){
                                                                    
                                    $lista_totalizador .= " AND aux.CD_SETOR = '$var_setor'";
                                }

 
                                $lista_totalizador .= " AND aux.CLASSIFICACAO_CONTABIL = 'RECEITA')
                                 
                                 -
                                 
                                 (SELECT SUM(aux.VL_REALIZADO) AS VL_REALIZADO
                                 FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO aux 
                                 WHERE aux.PERIODO = tt.PERIODO";

                                if($var_setor <> 'Todos'){
                                                                    
                                    $lista_totalizador .= " AND aux.CD_SETOR = '$var_setor'";
                                }

                                
                                $lista_totalizador .= " AND aux.CLASSIFICACAO_CONTABIL = 'DESPESA') AS VL_REALIZADO
                                 

                             FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO tt";

                            if($var_setor <> 'Todos'){
                                $lista_totalizador .= " WHERE tt.CD_SETOR = '$var_setor'";
                            }

                            $lista_totalizador .= " UNION ALL
                            
                             SELECT tt.PERIODO, tt.CLASSIFICACAO_CONTABIL, SUM(tt.VL_ORCADO) AS VL_ORCADO, SUM(tt.VL_REALIZADO) AS VL_REALIZADO
                             FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO tt";

                             if($var_setor <> 'Todos'){
    
                                $lista_totalizador .= " WHERE tt.CD_SETOR = '$var_setor'";
                            }

                            $lista_totalizador .= "
                             GROUP BY tt.PERIODO, tt.CLASSIFICACAO_CONTABIL
                             
                             ) res
                             
                             WHERE res.PERIODO = '$var_periodo'
                             AND res.CLASSIFICACAO_CONTABIL <> 'DESPESA'
                            ORDER BY 2 ASC";


        //echo $lista_totalizador;
       

        $result_totalizador = oci_parse($conn_ora, $lista_totalizador);

        @oci_execute($result_totalizador);

        //////////////////////////////////////////////////
        ////LISTAR TOTALIZADOR CONTA CONTABIL DESPESA/////
        //////////////////////////////////////////////////

        $lista_totalizador_desp = "SELECT res.PERIODO,
                                   CASE 
                                       WHEN res.CLASSIFICACAO_CONTABIL = 'RESULTADO' THEN 1
                                       WHEN res.CLASSIFICACAO_CONTABIL = 'RECEITA' THEN 2
                                       WHEN res.CLASSIFICACAO_CONTABIL = 'DESPESA' THEN 3
                                   END AS ORDEM,
                                   CASE 
                                       WHEN res.CLASSIFICACAO_CONTABIL = 'RESULTADO' AND res.VL_REALIZADO >= res.VL_ORCADO THEN ' <i class=||fas fa-arrow-up||></i>'
                                       WHEN res.CLASSIFICACAO_CONTABIL = 'RECEITA' AND res.VL_REALIZADO >= res.VL_ORCADO THEN ' <i class=||fas fa-arrow-up||></i>'
                                       WHEN res.CLASSIFICACAO_CONTABIL = 'DESPESA' AND res.VL_REALIZADO <= res.VL_ORCADO THEN ' <i class=||fas fa-arrow-up||></i>'
                                       ELSE ' <i class=||fas fa-arrow-down||></i>'
                                   END AS SETA,
                                   res.CLASSIFICACAO_CONTABIL,
                                   res.VL_ORCADO, res.VL_REALIZADO,
                                   (res.VL_REALIZADO - res.VL_ORCADO)AS VARIACAO,
                                   (ROUND(((res.VL_REALIZADO / NULLIF(res.VL_ORCADO,0)) - 1) * 100,2)) AS PORC_VARIACAO
                                   
                                   
                                   FROM(

                                   SELECT DISTINCT tt.PERIODO, 'RESULTADO' AS CLASSIFICACAO_CONTABIL, 
                                   

                                   (SELECT SUM(aux.VL_ORCADO) AS VL_ORCADO
                                       FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO aux 
                                       WHERE aux.PERIODO = tt.PERIODO
                                       AND aux.CLASSIFICACAO_CONTABIL = 'RECEITA')
                                       
                                       -
                                       
                                       (SELECT SUM(aux.VL_ORCADO) AS VL_ORCADO
                                       FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO aux 
                                       WHERE aux.PERIODO = tt.PERIODO
                                       AND aux.CLASSIFICACAO_CONTABIL = 'DESPESA') AS VL_ORCADO,
                                       

                                   (SELECT SUM(aux.VL_REALIZADO) AS VL_REALIZADO
                                       FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO aux 
                                       WHERE aux.PERIODO = tt.PERIODO
                                       AND aux.CLASSIFICACAO_CONTABIL = 'RECEITA')
                                       
                                       -
                                       
                                       (SELECT SUM(aux.VL_REALIZADO) AS VL_REALIZADO
                                       FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO aux 
                                       WHERE aux.PERIODO = tt.PERIODO
                                       AND aux.CLASSIFICACAO_CONTABIL = 'DESPESA') AS VL_REALIZADO
                                       

                                   FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO tt";

                                  if($var_setor <> 'Todos'){
                                      $lista_totalizador_desp .= " WHERE tt.CD_SETOR = '$var_setor'";
                                  }

                                  $lista_totalizador_desp .= " UNION ALL
                                  
                                   SELECT tt.PERIODO, tt.CLASSIFICACAO_CONTABIL, SUM(tt.VL_ORCADO) AS VL_ORCADO, SUM(tt.VL_REALIZADO) AS VL_REALIZADO
                                   FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO tt";

                                   if($var_setor <> 'Todos'){
                                      $lista_totalizador_desp .= " WHERE tt.CD_SETOR = '$var_setor'";
                                  }

                                  $lista_totalizador_desp .= "
                                   GROUP BY tt.PERIODO, tt.CLASSIFICACAO_CONTABIL
                                   
                                   ) res
                                   
                                   WHERE res.PERIODO = '$var_periodo'
                                   AND res.CLASSIFICACAO_CONTABIL = 'DESPESA'
                                  ORDER BY 2 ASC";

            $result_totalizador_desp = oci_parse($conn_ora, $lista_totalizador_desp);

            @oci_execute($result_totalizador_desp);

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

                        END COR_VARIACAO , 
                        st.cd_usuario AS USUARIO
                        FROM orcamento_contabil.VW_RESULTADOS_CONSOLIDADOS vrc
                        LEFT JOIN orcamento_contabil.setor st
                                                ON vrc.CD_SETOR = st.cd_setor                               
                                 
                                 WHERE vrc.PERIODO = '$var_periodo'";

                                 if($var_setor <> 'Todos'){
                                    $lista_conta_contabil .= " AND vrc.CD_SETOR = '$var_setor'";
                                 }
                                 if($reduzido != ''){
                                    $lista_conta_contabil .= " AND vrc.CD_CONTA_MV = $reduzido";
                                 }

                                 $lista_conta_contabil .= " ORDER BY vrc.CLASSIFICACAO_CONTABIL DESC, vrc.DS_SETOR ASC, vrc.ORDEM ASC, vrc.CD_CONTA_MV ASC";

        
        //echo $lista_conta_contabil;
        
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
            <th class="align-middle" style="text-align: center !important;"><span>   Justificativa   </span></th>


        </tr></thead>            

        <tbody>
            <?php
            $var_group_orcado = 'Inicio';
            $var_cont_while_cc = 0;
            $var_class_cc = 'RECEITA';
            $var_count_desp = 0;

            while($row_conta_contabil = @oci_fetch_array($result_conta_contabil)){
           
                if($var_cont_while_cc == 0 && $reduzido == ''){

                    while($row_tt = @oci_fetch_array($result_totalizador)){

            ?>

                        <!-- TOTALIZADOR RESULTADO E RECEITA-->

                        
                        <?php 
                            if($row_tt['CLASSIFICACAO_CONTABIL'] == 'RESULTADO'){

                                echo '<tr style="background-color: #0271c1; color: #ffffff;">';

                            }else{

                                echo '<tr style="background-color: #3185c1; color: #ffffff;">';
                            }
                        
                        ?>

                      
                           <!--COLUNAS-->
                           <th class="align-middle" style="text-align: center !important;"><span></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span>TOTAL <?php echo @$row_tt['CLASSIFICACAO_CONTABIL']; ?></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$row_tt['VL_ORCADO'], 2, ',', '.' ); ?></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$row_tt['VL_REALIZADO'], 2, ',', '.' ); ?></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$row_tt['VARIACAO'], 2, ',', '.' ) . @str_replace('||','"',$row_tt['SETA']); ?></span></th></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$row_tt['PORC_VARIACAO'], 2, ',', '.' ) . '%' . @str_replace('||','"',$row_tt['SETA']); ?></span></th></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span></span></th>
                       </tr> 

            <?php
       

                    }


                }
                
                if($row_conta_contabil['CLASSIFICACAO_CONTABIL'] <> $var_class_cc AND $var_count_desp == 0){

                    while($row_tt_desp = @oci_fetch_array($result_totalizador_desp)){

            ?>

                        <!-- TOTALIZADOR RESULTADO DEPESA-->
                        <tr style="background-color: #3185c1; color: #ffffff;">

                           <!--COLUNAS-->
                           <th class="align-middle" style="text-align: center !important;"><span></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span>TOTAL <?php echo @$row_tt_desp['CLASSIFICACAO_CONTABIL']; ?></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$row_tt_desp['VL_ORCADO'], 2, ',', '.' ); ?></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$row_tt_desp['VL_REALIZADO'], 2, ',', '.' ); ?></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$row_tt_desp['VARIACAO'], 2, ',', '.' ) . @$row_tt_desp['SETA']; ?></span></th></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span><?php echo @number_format(@$row_tt_desp['PORC_VARIACAO'], 2, ',', '.' ) . '%' . @$row_tt_desp['SETA']; ?></span></th></span></th>
                           <th class="align-middle" style="text-align: center !important;"><span></span></th>
                        </tr> 

            <?php
       

                    }

                    $var_count_desp = 1;

                }








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

                    $var_group_orcado = @$row_conta_contabil['CD_GRUPO_ORCADO'];
                    
                } ?>

        </tbody>           
    </table>
    </div>