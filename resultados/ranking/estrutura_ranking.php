<?php 

    echo $lista_resultado_ranking = "SELECT * FROM (SELECT res.VL_REALIZADO,st.ds_setor
                                    FROM orcamento_contabil.vw_resultado_desvio_teste res
                                    INNER JOIN orcamento_contabil.setor st
                                    ON st.cd_setor = res.cd_setor
                                    WHERE res.ANO || '-' || res.MES = '$var_periodo_filtro'
                                    AND res.CLASSIFICACAO_CONTABIL = UPPER('$var_visao')
                                    UNION ALL

                                    SELECT ROUND(NVL(rec.VL_REALIZADO - dep.VL_REALIZADO, 0), 0) AS VL_REALIZADO,
                                        st.ds_setor

                                    FROM orcamento_contabil.VW_RESULTADO_DESVIO_teste rec
                                    LEFT JOIN (SELECT rec.*
                                                FROM orcamento_contabil.VW_RESULTADO_DESVIO_teste rec
                                                WHERE rec.ANO || '-' || rec.MES = '$var_periodo_filtro'
                                                    AND 'RESULTADO' = UPPER('$var_visao')
                                                    AND rec.CLASSIFICACAO_CONTABIL = UPPER('DESPESA')) dep
                                        ON rec.CD_SETOR = dep.CD_SETOR
                                    INNER JOIN orcamento_contabil.setor st
                                        ON st.cd_setor = rec.cd_setor
                                    WHERE rec.ANO || '-' || rec.MES = '$var_periodo_filtro'
                                    AND 'RESULTADO' = UPPER('$var_visao')
                                    AND rec.CLASSIFICACAO_CONTABIL = UPPER('RECEITA'))
                                    ORDER BY 1 ";
                                    if($var_visao == 'Despesa'){
                                        $lista_resultado_ranking .= " ASC";
                                    }else{
                                        $lista_resultado_ranking .= " DESC";
                                    }
                       
                                

    $result_resultado_ranking = oci_parse($conn_ora, $lista_resultado_ranking);

    ?>

    <div class="fnd_azul" id="fnd_azul">     
    <i class="fas fa-chart-line"></i> Ranking <?php echo substr($var_periodo_filtro, -2) .'/'. substr($var_periodo_filtro, 0, 4); ?>
    </div>

    <div class="div_br"> </div>   
    <div class="div_br"> </div>  

    <?php
    include 'resultados/ranking/grafico_ranking.php';

    echo '<div class="div_br"> </div>'; 

?>  


