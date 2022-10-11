<?php

    //LISTAR RESULTADO DESVIO

    $lista_resultado_acumulado = "SELECT res.RESULTADO, res.ANO, res.MES, res.MES_ABV,
                                  SUM(res.VL_ORCADO_RECEITA) AS VL_ORCADO_RECEITA, SUM(res.VL_REALIZADO_RECEITA) AS VL_REALIZADO_RECEITA,
                                  SUM(res.VL_ORCADO_DESPESA) AS VL_ORCADO_DESPESA, SUM(res.VL_REALIZADO_DESPESA) AS VL_REALIZADO_DESPESA,
                                  SUM(res.VL_ORCADO_RESULTADO) AS VL_ORCADO_RESULTADO, SUM(res.VL_REALIZADO_RESULTADO) AS VL_REALIZADO_RESULTADO
                                  FROM(
                                  
                                  SELECT 'TODOS' AS RESULTADO,
                                  vrd.ANO, vrd.MES, vrd.MES_ABV, 
                                  vrd.VL_ORCADO AS VL_ORCADO_RECEITA, vrd.VL_REALIZADO AS VL_REALIZADO_RECEITA,
                                  NULL AS VL_ORCADO_DESPESA, NULL AS VL_REALIZADO_DESPESA,
                                  NULL AS VL_ORCADO_RESULTADO, NULL AS VL_REALIZADO_RESULTADO
                                  FROM orcamento_contabil.VW_RESULTADO_DESVIO vrd
                                  WHERE vrd.ANO = $var_ano
                                  AND vrd.CLASSIFICACAO_CONTABIL = 'RECEITA'
                                  
                                  UNION ALL
                                  
                                  SELECT 'TODOS' AS RESULTADO,
                                  vrd.ANO, vrd.MES, vrd.MES_ABV, 
                                  NULL AS VL_ORCADO_RECEITA, NULL AS VL_REALIZADO_RECEITA,
                                  vrd.VL_ORCADO AS VL_ORCADO_DESPESA, vrd.VL_REALIZADO AS VL_REALIZADO_DESPESA,
                                  NULL AS VL_ORCADO_RESULTADO, NULL AS VL_REALIZADO_RESULTADO
                                  FROM orcamento_contabil.VW_RESULTADO_DESVIO vrd
                                  WHERE vrd.ANO = $var_ano
                                  AND vrd.CLASSIFICACAO_CONTABIL = 'DESPESA'
                                                              
                                  UNION ALL
                                                              
                                  SELECT 'TODOS' AS RESULTADO,
                                  rec.ANO, rec.MES, rec.MES_ABV, 
                                  NULL AS VL_ORCADO_RECEITA, NULL AS VL_REALIZADO_RECEITA,
                                  NULL AS VL_ORCADO_DESPESA, NULL AS VL_REALIZADO_DESPESA,
                                  NVL(rec.VL_ORCADO - dep.VL_ORCADO,0) AS VL_ORCADO_RESULTADO, NVL(rec.VL_REALIZADO - dep.VL_REALIZADO,0) AS VL_REALIZADO_RESULTADO
                                  FROM orcamento_contabil.VW_RESULTADO_DESVIO rec
                                  LEFT JOIN(SELECT rec.*
                                      FROM orcamento_contabil.VW_RESULTADO_DESVIO rec
                                      WHERE rec.ANO = $var_ano
                                      AND rec.CLASSIFICACAO_CONTABIL = UPPER('DESPESA')) dep
                                  ON rec.ANO = dep.ANO
                                  AND rec.MES = dep.MES
                                  WHERE rec.ANO = $var_ano
                                  AND rec.CLASSIFICACAO_CONTABIL = UPPER('RECEITA')
                                  )res
                                  GROUP BY res.RESULTADO, res.ANO, res.MES, res.MES_ABV
                                  ORDER BY res.MES ASC";

    $result_resultado_acumulado = oci_parse($conn_ora, $lista_resultado_acumulado);

?>

<div class="fnd_azul" id="fnd_azul">     
    <i class="fas fa-chart-line"></i> Acumulado <?php echo $var_ano; ?>
</div>

<div class="div_br"> </div>   
<div class="div_br"> </div>  

<?php

    include 'resultados/acumulado/resultado_acumulado.php';

?>  