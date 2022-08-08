--APENAS RECEITA / DESPESA
SELECT vrd.ANO, vrd.MES, vrd.MES_ABV, 
CASE
    WHEN SUBSTR(TRIM((TO_CHAR(vrd.VL_ORCADO, '999G999G999G999D99'))),0,1) = ',' THEN '0'
    ELSE TRIM(TO_CHAR(vrd.VL_ORCADO, '999G999G999G999D99'))
END AS VL_ORCADO,
CASE
    WHEN SUBSTR(TRIM((TO_CHAR(vrd.VL_REALIZADO, '999G999G999G999D99'))),0,1) = ',' THEN '0'
    ELSE TRIM(TO_CHAR(vrd.VL_REALIZADO, '999G999G999G999D99'))
END AS VL_REALIZADO,
ROUND(vrd.VL_REALIZADO,0) AS VL_REALIZADO_ROUND,
ROUND(vrd.VL_ORCADO,0) AS VL_ORCADO_ROUND
FROM orcamento_contabil.VW_RESULTADO_DESVIO vrd
WHERE vrd.ANO = 2022
AND vrd.CLASSIFICACAO_CONTABIL = UPPER('Resultado')

UNION ALL

--RESULTADO

SELECT rec.ANO, rec.MES, rec.MES_ABV, 
CASE
    WHEN SUBSTR(TRIM((TO_CHAR(NVL(rec.VL_ORCADO - dep.VL_ORCADO,0), '999G999G999G999D99'))),0,1) = ',' THEN '0'
    ELSE TRIM(TO_CHAR(NVL(rec.VL_ORCADO - dep.VL_ORCADO,0), '999G999G999G999D99'))
END AS VL_ORCADO,
CASE
    WHEN SUBSTR(TRIM((TO_CHAR(NVL(rec.VL_REALIZADO - dep.VL_REALIZADO,0), '999G999G999G999D99'))),0,1) = ',' THEN '0'
    ELSE TRIM(TO_CHAR(NVL(rec.VL_REALIZADO - dep.VL_REALIZADO,0), '999G999G999G999D99'))
END AS VL_REALIZADO,
ROUND(NVL(rec.VL_REALIZADO - dep.VL_REALIZADO,0),0) AS VL_REALIZADO_ROUND,
ROUND(NVL(rec.VL_ORCADO - dep.VL_ORCADO,0),0) AS VL_ORCADO_ROUND
FROM orcamento_contabil.VW_RESULTADO_DESVIO rec
LEFT JOIN(SELECT rec.*
           FROM orcamento_contabil.VW_RESULTADO_DESVIO rec
           WHERE rec.ANO = 2022
           AND 'RESULTADO' = UPPER('Resultado')
           AND rec.CLASSIFICACAO_CONTABIL = UPPER('DESPESA')) dep
ON rec.ANO = dep.ANO
AND rec.MES = dep.MES
WHERE rec.ANO = 2022
AND 'RESULTADO' = UPPER('Resultado')
AND rec.CLASSIFICACAO_CONTABIL = UPPER('RECEITA')
ORDER BY 2 ASC
