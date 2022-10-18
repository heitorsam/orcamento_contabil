<?php

    //LISTAR RESULTADO DESVIO
    $var_setor = @$_POST["filtro_setor"];

    /*$lista_resultado_desvio = "SELECT total.ANO,
                                total.MES,
                                total.MES_ABV,
                                SUM(total.VL_ORCADO) AS VL_ORCADO,
                                SUM(total.VL_REALIZADO) AS VL_REALIZADO,
                                SUM(total.VL_REALIZADO_ROUND) AS VL_REALIZADO_ROUND,
                                SUM(total.VL_ORCADO_ROUND) AS VL_ORCADO_ROUND
                            FROM (SELECT 'RECEITA/DESPESA' AS RESULTADO,
                               vrd.ANO, vrd.MES, vrd.MES_ABV, 
                               vrd.VL_ORCADO, vrd.VL_REALIZADO,
                               ROUND(NVL(vrd.VL_REALIZADO,0),0) AS VL_REALIZADO_ROUND,
                               ROUND(NVL(vrd.VL_ORCADO,0),0) AS VL_ORCADO_ROUND
                               FROM orcamento_contabil.VW_RESULTADO_DESVIO_SETOR vrd
                               WHERE vrd.ANO = $var_ano
                               AND vrd.CLASSIFICACAO_CONTABIL = UPPER('$var_visao')";

                               if($var_setor != 'Todos'){
<<<<<<< HEAD
=======
                                   $lista_resultado_desvio .= " AND vrd.CD_SETOR = $var_setor";
                                }
                                
                                $lista_resultado_desvio .=" UNION ALL
                                
                                SELECT 'RESULTADO' AS RESULTADO,
                                rec.ANO, rec.MES, rec.MES_ABV, 
                                NVL(rec.VL_ORCADO - dep.VL_ORCADO,0) AS VL_ORCADO,
                                NVL(rec.VL_REALIZADO - dep.VL_REALIZADO,0) AS VL_REALIZADO,
                                ROUND(NVL(rec.VL_REALIZADO - dep.VL_REALIZADO,0),0) AS VL_REALIZADO_ROUND,
                                ROUND(NVL(rec.VL_ORCADO - dep.VL_ORCADO,0),0) AS VL_ORCADO_ROUND
                                FROM orcamento_contabil.VW_RESULTADO_DESVIO_TESTE rec
                                LEFT JOIN(SELECT rec.*
                                FROM orcamento_contabil.VW_RESULTADO_DESVIO_TESTE rec
                                WHERE rec.ANO = $var_ano
                                AND 'RESULTADO' = UPPER('$var_visao')
                                AND rec.CLASSIFICACAO_CONTABIL = UPPER('DESPESA')
                                AND rec.CD_SETOR = $var_setor
                                ) dep
                                ON rec.ANO = dep.ANO
                                AND rec.MES = dep.MES
                                WHERE rec.ANO = $var_ano
                                AND 'RESULTADO' = UPPER('$var_visao')
                                AND rec.CLASSIFICACAO_CONTABIL = UPPER('RECEITA')
                                AND rec.CD_SETOR = $var_setor
                                ORDER BY 3 ASC) total
                                GROUP BY total.ANO, total.MES, total.MES_ABV
                                ORDER BY 2";*/
                                $lista_resultado_desvio = "SELECT total.ANO,
                                total.MES,
                                total.MES_ABV,
                                SUM(total.VL_ORCADO) AS VL_ORCADO,
                                SUM(total.VL_REALIZADO) AS VL_REALIZADO,
                                SUM(total.VL_REALIZADO_ROUND) AS VL_REALIZADO_ROUND,
                                SUM(total.VL_ORCADO_ROUND) AS VL_ORCADO_ROUND
                            FROM (SELECT 'RECEITA/DESPESA' AS RESULTADO,
                               vrd.ANO, vrd.MES, vrd.MES_ABV, 
                               vrd.VL_ORCADO, vrd.VL_REALIZADO,
                               ROUND(NVL(vrd.VL_REALIZADO,0),0) AS VL_REALIZADO_ROUND,
                               ROUND(NVL(vrd.VL_ORCADO,0),0) AS VL_ORCADO_ROUND
                               FROM orcamento_contabil.VW_RESULTADO_DESVIO_TESTE vrd
                               WHERE vrd.ANO = $var_ano
                               AND vrd.CLASSIFICACAO_CONTABIL = UPPER('$var_visao')";
                               if($var_setor != 'Todos'){
>>>>>>> parent of da73053 (Merge branch 'main' of https://github.com/heitorsam/orcamento_contabil)
                                $lista_resultado_desvio .= " AND vrd.CD_SETOR = $var_setor";
                               }
                               
                               $lista_resultado_desvio .=" UNION ALL
                            
                               SELECT 'RESULTADO' AS RESULTADO,
                               rec.ANO, rec.MES, rec.MES_ABV, 
                               NVL(rec.VL_ORCADO - dep.VL_ORCADO,0) AS VL_ORCADO,
                               NVL(rec.VL_REALIZADO - dep.VL_REALIZADO,0) AS VL_REALIZADO,
                               ROUND(NVL(rec.VL_REALIZADO - dep.VL_REALIZADO,0),0) AS VL_REALIZADO_ROUND,
                               ROUND(NVL(rec.VL_ORCADO - dep.VL_ORCADO,0),0) AS VL_ORCADO_ROUND
<<<<<<< HEAD
                               FROM orcamento_contabil.VW_RESULTADO_DESVIO_SETOR rec
                               LEFT JOIN(SELECT aux.*
                                   FROM orcamento_contabil.VW_RESULTADO_DESVIO_SETOR aux
                                   WHERE aux.ANO = $var_ano
                                   AND 'RESULTADO' = UPPER('$var_visao')
                                   AND aux.CLASSIFICACAO_CONTABIL = UPPER('DESPESA')";

                                if($var_setor != 'Todos'){
                                    $lista_resultado_desvio .= " AND aux.CD_SETOR = $var_setor";
                                }
                               
                                $lista_resultado_desvio .=" ) dep
                                ON rec.ANO = dep.ANO
                                AND rec.MES = dep.MES
                                AND rec.CD_SETOR = dep.CD_SETOR
                                WHERE rec.ANO = $var_ano
                                AND 'RESULTADO' = UPPER('$var_visao')";

                                if($var_setor != 'Todos'){
                                    $lista_resultado_desvio .= " AND rec.CD_SETOR = $var_setor";
                                }

                                $lista_resultado_desvio .= " AND rec.CLASSIFICACAO_CONTABIL = UPPER('RECEITA')
                                ORDER BY 3 ASC) total
                                GROUP BY total.ANO, total.MES, total.MES_ABV
                                ORDER BY 2
                                ) res
                                LEFT JOIN orcamento_contabil.NECESSIDADE_PREVISTA np 
                                ON np.PERIODO = res.MES || '/' || res.ANO
                                ORDER BY res.ANO ASC, res.MES ASC";

    //echo $lista_resultado_desvio;
    
    $result_resultado_desvio = oci_parse($conn_ora, $lista_resultado_desvio);

?>
=======
                               FROM orcamento_contabil.VW_RESULTADO_DESVIO rec
                               LEFT JOIN(SELECT rec.*
                                       FROM orcamento_contabil.VW_RESULTADO_DESVIO rec
                                       WHERE rec.ANO = $var_ano
                                       AND 'RESULTADO' = UPPER('$var_visao')
                                       AND rec.CLASSIFICACAO_CONTABIL = UPPER('DESPESA')
                                       ) dep
                               ON rec.ANO = dep.ANO
                               AND rec.MES = dep.MES
                               WHERE rec.ANO = $var_ano
                               AND 'RESULTADO' = UPPER('$var_visao')
                               AND rec.CLASSIFICACAO_CONTABIL = UPPER('RECEITA')
                                ORDER BY 3 ASC) total
                                GROUP BY total.ANO, total.MES, total.MES_ABV
                                ORDER BY 2";
                                
                                $result_resultado_desvio = oci_parse($conn_ora, $lista_resultado_desvio);
                                
                                ?>
>>>>>>> parent of da73053 (Merge branch 'main' of https://github.com/heitorsam/orcamento_contabil)

<div class="fnd_azul" id="fnd_azul">     
    <i class="fas fa-chart-line"></i> Desvio <?php echo $var_visao . ' ' . $var_ano; ?>
</div>

<div class="div_br"> </div>   
<div class="div_br"> </div>  

<?php
    include 'resultados/desvio/grafico_desvio_orc_x_rel.php';

    echo '<div class="div_br"> </div>'; 

    include 'resultados/desvio/resultado_desvio.php';
?>  