SELECT *
FROM orcamento_contabil.VW_TOT_CALCULADO_SET_PERIODO ttc
WHERE ttc.PERIODO = '09/2022'
AND ttc.CD_SETOR = '5';

CREATE OR REPLACE VIEW orcamento_contabil.VW_TOT_CALCULADO_SET_PERIODO AS 

SELECT res.PERIODO, res.CD_SETOR, 
CASE 
  WHEN res.CLASSIFICACAO_CONTABIL = 'RESULTADO' THEN 1
  WHEN res.CLASSIFICACAO_CONTABIL = 'RECEITA' THEN 2
  WHEN res.CLASSIFICACAO_CONTABIL = 'DESPESA' THEN 3
END AS ORDEM,
CASE 
  WHEN res.CLASSIFICACAO_CONTABIL = 'RESULTADO' AND res.VL_REALIZADO >= res.VL_ORCADO THEN ' <i class="fas fa-arrow-up"></i>'
  WHEN res.CLASSIFICACAO_CONTABIL = 'RECEITA' AND res.VL_REALIZADO >= res.VL_ORCADO THEN ' <i class="fas fa-arrow-up"></i>'
  WHEN res.CLASSIFICACAO_CONTABIL = 'DESPESA' AND res.VL_REALIZADO <= res.VL_ORCADO THEN ' <i class="fas fa-arrow-up"></i>'
  ELSE ' <i class="fas fa-arrow-down"></i>'
END AS SETA,
res.CLASSIFICACAO_CONTABIL,
res.VL_ORCADO, res.VL_REALIZADO,
(res.VL_REALIZADO - res.VL_ORCADO)AS VARIACAO,
(ROUND(((res.VL_REALIZADO / NULLIF(res.VL_ORCADO,0)) - 1) * 100,2)) AS PORC_VARIACAO


FROM(

--TOTAL (DISTINCT NESSE CASO OK POR CONTA QUE ELE FAZ 2 CONTAS COM OS MESMOS RESULTADOS DEVIDO A REGRA)
SELECT DISTINCT tt.PERIODO, tt.CD_SETOR, 'RESULTADO' AS CLASSIFICACAO_CONTABIL, 

--ORCADO (RECEITA - DESPESA)
(SELECT aux.VL_ORCADO
 FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO aux 
 WHERE aux.PERIODO = tt.PERIODO AND aux.CD_SETOR = tt.CD_SETOR
 AND aux.CLASSIFICACAO_CONTABIL = 'RECEITA')
 
 -
 
 (SELECT aux.VL_ORCADO
 FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO aux 
 WHERE aux.PERIODO = tt.PERIODO AND aux.CD_SETOR = tt.CD_SETOR
 AND aux.CLASSIFICACAO_CONTABIL = 'DESPESA') AS VL_ORCADO,
 
--REALIZADO (RECEITA - DESPESA)
(SELECT aux.VL_REALIZADO
 FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO aux 
 WHERE aux.PERIODO = tt.PERIODO AND aux.CD_SETOR = tt.CD_SETOR
 AND aux.CLASSIFICACAO_CONTABIL = 'RECEITA')
 
 -
 
 (SELECT aux.VL_REALIZADO
 FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO aux 
 WHERE aux.PERIODO = tt.PERIODO AND aux.CD_SETOR = tt.CD_SETOR
 AND aux.CLASSIFICACAO_CONTABIL = 'DESPESA') AS VL_REALIZADO
 
--SUM(tt.VL_REALIZADO) AS VL_REALIZADO
FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO tt

UNION ALL

--CLASSIFICACAO
SELECT tt.PERIODO, tt.CD_SETOR, tt.CLASSIFICACAO_CONTABIL, tt.VL_ORCADO, tt.VL_REALIZADO
FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO tt

) res

--WHERE res.PERIODO = '09/2022'
--AND res.CD_SETOR = '5';