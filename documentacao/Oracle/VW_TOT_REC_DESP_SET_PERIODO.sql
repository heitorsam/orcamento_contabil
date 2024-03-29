


SELECT res.PERIODO, res.CD_SETOR, res.CLASSIFICACAO_CONTABIL,
res.VL_ORCADO, res.VL_REALIZADO

FROM(

--TOTAL
SELECT tt.PERIODO, tt.CD_SETOR, 'RESULTADO' AS CLASSIFICACAO_CONTABIL, 
SUM(tt.VL_ORCADO) AS VL_ORCADO, SUM(tt.VL_REALIZADO) AS VL_REALIZADO
FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO tt
GROUP BY tt.PERIODO, tt.CD_SETOR

UNION ALL

--CLASSIFICACAO
SELECT tt.PERIODO, tt.CD_SETOR, tt.CLASSIFICACAO_CONTABIL, tt.VL_ORCADO, tt.VL_REALIZADO
FROM orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO tt

) res
WHERE res.PERIODO = '09/2022'
AND res.CD_SETOR = '5';




CREATE OR REPLACE VIEW orcamento_contabil.VW_TOT_REC_DESP_SET_PERIODO AS

SELECT 
cons.PERIODO,
cons.CD_SETOR,
cons.CLASSIFICACAO_CONTABIL, 
SUM(cons.VL_ORCADO) AS VL_ORCADO, 
SUM(VL_REALIZADO) AS VL_REALIZADO

FROM (

--REGRAS CONTA SEM GRUPO ORCADO

SELECT 
res.PERIODO,
res.CD_SETOR,
res.CLASSIFICACAO_CONTABIL,
SUM(res.VL_ORCADO) AS VL_ORCADO,
SUM(res.VL_REALIZADO) AS VL_REALIZADO

FROM(
SELECT vrc.*,
       (SELECT CASE
                 WHEN COUNT(*) > 1 THEN
                  'S'
                 ELSE
                  'N'
               END
          FROM VW_RESULTADOS_CONSOLIDADOS sub
         WHERE sub.CD_CONTA_MV = vrc.CD_CONTA_MV
           AND sub.PERIODO = vrc.PERIODO) AS SN_REPETIDO,
       (vrc.VL_REALIZADO - vrc.VL_ORCADO) AS VL_VARIACAO,
       (((vrc.VL_REALIZADO / NULLIF(vrc.VL_ORCADO, 0))) - 1) * 100 AS VL_PORC_VARIACAO,
       (SELECT SUM(aux.VL_REALIZADO)
          FROM VW_RESULTADOS_CONSOLIDADOS aux
         WHERE aux.CD_GRUPO_ORCADO = vrc.CD_GRUPO_ORCADO
           AND aux.PERIODO = vrc.PERIODO) - vrc.VL_ORCADO AS VL_VARIACAO_GRUPO_ORCADO,
       (((NULLIF((SELECT SUM(aux.VL_REALIZADO)
                   FROM VW_RESULTADOS_CONSOLIDADOS aux
                  WHERE aux.CD_GRUPO_ORCADO = vrc.CD_GRUPO_ORCADO
                    AND aux.PERIODO = vrc.PERIODO),
                 0)) / NULLIF(vrc.VL_ORCADO, 0) - 1) * 100) AS VL_PROC_VARIACAO_GRUPO_ORCADO,
       CASE
         WHEN vrc.VL_ORCADO -
              (SELECT SUM(aux.VL_REALIZADO)
                 FROM VW_RESULTADOS_CONSOLIDADOS aux
                WHERE aux.CD_GRUPO_ORCADO = vrc.CD_GRUPO_ORCADO
                  AND aux.PERIODO = vrc.PERIODO) > 0 AND
              vrc.CLASSIFICACAO_CONTABIL = 'DESPESA' AND
              vrc.CD_GRUPO_ORCADO > 0 THEN
          'green'
         WHEN vrc.VL_ORCADO -
              (SELECT SUM(aux.VL_REALIZADO)
                 FROM VW_RESULTADOS_CONSOLIDADOS aux
                WHERE aux.CD_GRUPO_ORCADO = vrc.CD_GRUPO_ORCADO
                  AND aux.PERIODO = vrc.PERIODO) < 0 AND
              vrc.CLASSIFICACAO_CONTABIL = 'DESPESA' AND
              vrc.CD_GRUPO_ORCADO > 0 THEN
          'red'
         WHEN vrc.VL_ORCADO -
              (SELECT SUM(aux.VL_REALIZADO)
                 FROM VW_RESULTADOS_CONSOLIDADOS aux
                WHERE aux.CD_GRUPO_ORCADO = vrc.CD_GRUPO_ORCADO
                  AND aux.PERIODO = vrc.PERIODO) < 0 AND
              vrc.CLASSIFICACAO_CONTABIL = 'RECEITA' AND
              vrc.CD_GRUPO_ORCADO > 0 THEN
          'green'
         WHEN vrc.VL_ORCADO -
              (SELECT SUM(aux.VL_REALIZADO)
                 FROM VW_RESULTADOS_CONSOLIDADOS aux
                WHERE aux.CD_GRUPO_ORCADO = vrc.CD_GRUPO_ORCADO
                  AND aux.PERIODO = vrc.PERIODO) > 0 AND
              vrc.CLASSIFICACAO_CONTABIL = 'RECEITA' AND
              vrc.CD_GRUPO_ORCADO > 0 THEN
          'red'
         WHEN (vrc.VL_REALIZADO - vrc.VL_ORCADO) < 0 AND
              vrc.CLASSIFICACAO_CONTABIL = 'DESPESA' THEN
          'green'
         WHEN (vrc.VL_REALIZADO - vrc.VL_ORCADO) > 0 AND
              vrc.CLASSIFICACAO_CONTABIL = 'DESPESA' THEN
          'red'
         WHEN (vrc.VL_REALIZADO - vrc.VL_ORCADO) < 0 AND
              vrc.CLASSIFICACAO_CONTABIL = 'RECEITA' THEN
          'red'
         WHEN (vrc.VL_REALIZADO - vrc.VL_ORCADO) > 0 AND
              vrc.CLASSIFICACAO_CONTABIL = 'RECEITA' THEN
          'green'
         ELSE
          'grey'
       END COR_VARIACAO
  FROM orcamento_contabil.VW_RESULTADOS_CONSOLIDADOS vrc
 WHERE vrc.CD_GRUPO_ORCADO IS NULL
) res
GROUP BY 
res.PERIODO,
res.CD_SETOR,
res.CLASSIFICACAO_CONTABIL

UNION ALL

--REGRAS CONTA COM GRUPO ORCADO

SELECT 
res.PERIODO,
res.CD_SETOR,
res.CLASSIFICACAO_CONTABIL,
MAX(res.VL_ORCADO) AS VL_ORCADO,
SUM(res.VL_REALIZADO) AS VL_REALIZADO

FROM(
SELECT vrc.*,
       (SELECT CASE
                 WHEN COUNT(*) > 1 THEN
                  'S'
                 ELSE
                  'N'
               END
          FROM VW_RESULTADOS_CONSOLIDADOS sub
         WHERE sub.CD_CONTA_MV = vrc.CD_CONTA_MV
           AND sub.PERIODO = vrc.PERIODO) AS SN_REPETIDO,
       (vrc.VL_REALIZADO - vrc.VL_ORCADO) AS VL_VARIACAO,
       (((vrc.VL_REALIZADO / NULLIF(vrc.VL_ORCADO, 0))) - 1) * 100 AS VL_PORC_VARIACAO,
       (SELECT SUM(aux.VL_REALIZADO)
          FROM VW_RESULTADOS_CONSOLIDADOS aux
         WHERE aux.CD_GRUPO_ORCADO = vrc.CD_GRUPO_ORCADO
           AND aux.PERIODO = vrc.PERIODO) - vrc.VL_ORCADO AS VL_VARIACAO_GRUPO_ORCADO,
       (((NULLIF((SELECT SUM(aux.VL_REALIZADO)
                   FROM VW_RESULTADOS_CONSOLIDADOS aux
                  WHERE aux.CD_GRUPO_ORCADO = vrc.CD_GRUPO_ORCADO
                    AND aux.PERIODO = vrc.PERIODO),
                 0)) / NULLIF(vrc.VL_ORCADO, 0) - 1) * 100) AS VL_PROC_VARIACAO_GRUPO_ORCADO,
       CASE
         WHEN vrc.VL_ORCADO -
              (SELECT SUM(aux.VL_REALIZADO)
                 FROM VW_RESULTADOS_CONSOLIDADOS aux
                WHERE aux.CD_GRUPO_ORCADO = vrc.CD_GRUPO_ORCADO
                  AND aux.PERIODO = vrc.PERIODO) > 0 AND
              vrc.CLASSIFICACAO_CONTABIL = 'DESPESA' AND
              vrc.CD_GRUPO_ORCADO > 0 THEN
          'green'
         WHEN vrc.VL_ORCADO -
              (SELECT SUM(aux.VL_REALIZADO)
                 FROM VW_RESULTADOS_CONSOLIDADOS aux
                WHERE aux.CD_GRUPO_ORCADO = vrc.CD_GRUPO_ORCADO
                  AND aux.PERIODO = vrc.PERIODO) < 0 AND
              vrc.CLASSIFICACAO_CONTABIL = 'DESPESA' AND
              vrc.CD_GRUPO_ORCADO > 0 THEN
          'red'
         WHEN vrc.VL_ORCADO -
              (SELECT SUM(aux.VL_REALIZADO)
                 FROM VW_RESULTADOS_CONSOLIDADOS aux
                WHERE aux.CD_GRUPO_ORCADO = vrc.CD_GRUPO_ORCADO
                  AND aux.PERIODO = vrc.PERIODO) < 0 AND
              vrc.CLASSIFICACAO_CONTABIL = 'RECEITA' AND
              vrc.CD_GRUPO_ORCADO > 0 THEN
          'green'
         WHEN vrc.VL_ORCADO -
              (SELECT SUM(aux.VL_REALIZADO)
                 FROM VW_RESULTADOS_CONSOLIDADOS aux
                WHERE aux.CD_GRUPO_ORCADO = vrc.CD_GRUPO_ORCADO
                  AND aux.PERIODO = vrc.PERIODO) > 0 AND
              vrc.CLASSIFICACAO_CONTABIL = 'RECEITA' AND
              vrc.CD_GRUPO_ORCADO > 0 THEN
          'red'
         WHEN (vrc.VL_REALIZADO - vrc.VL_ORCADO) < 0 AND
              vrc.CLASSIFICACAO_CONTABIL = 'DESPESA' THEN
          'green'
         WHEN (vrc.VL_REALIZADO - vrc.VL_ORCADO) > 0 AND
              vrc.CLASSIFICACAO_CONTABIL = 'DESPESA' THEN
          'red'
         WHEN (vrc.VL_REALIZADO - vrc.VL_ORCADO) < 0 AND
              vrc.CLASSIFICACAO_CONTABIL = 'RECEITA' THEN
          'red'
         WHEN (vrc.VL_REALIZADO - vrc.VL_ORCADO) > 0 AND
              vrc.CLASSIFICACAO_CONTABIL = 'RECEITA' THEN
          'green'
         ELSE
          'grey'
       END COR_VARIACAO
  FROM orcamento_contabil.VW_RESULTADOS_CONSOLIDADOS vrc
 WHERE vrc.CD_GRUPO_ORCADO IS NOT NULL
) res
GROUP BY 
res.PERIODO,
res.CD_SETOR,
res.CLASSIFICACAO_CONTABIL) cons
--WHERE EXTRACT(YEAR FROM cons.PERIODO) >= 2022
--WHERE cons.PERIODO = '09/2022'
--AND cons.CD_SETOR = '5'
GROUP BY 
cons.PERIODO,
cons.CD_SETOR,
cons.CLASSIFICACAO_CONTABIL
