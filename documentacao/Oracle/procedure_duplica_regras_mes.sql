/*
BEGIN 
  orcamento_contabil.PRC_DUPLICA_REGRAS_MES('04/2022','05/2022','HSSAMPAIO');
END;
*/

--SELECT * FROM orcamento_contabil.CONTA_CONTABIL cc;

CREATE OR REPLACE PROCEDURE orcamento_contabil.PRC_DUPLICA_REGRAS_MES(
var_periodo_copia VARCHAR2,var_periodo_atual VARCHAR2, var_cd_usuario VARCHAR2)

IS

BEGIN
  
  --PROCEDURE CRIADA POR HEITOR SCALABRINI

  --------------------------------------------
  -- DUPLICA AS INFORMACOES DO GRUPO ORCADO --
  --------------------------------------------
  
  DELETE FROM orcamento_contabil.GRUPO_ORCADO
  WHERE PERIODO = var_periodo_atual;
  
  INSERT INTO GRUPO_ORCADO
  SELECT seq_grupo_cd_grupo_orcado.NEXTVAL AS CD_GRUPO_ORCADO,
  gpo.DS_GRUPO_ORCADO, var_periodo_atual AS PERIODO, gpo.VL_ORCADO, gpo.SN_ATIVO,
  var_cd_usuario AS CD_USUARIO_CADASTRO, SYSDATE AS HR_CADASTRO,
  NULL AS CD_USUARIO_ULT_ALT, NULL AS HR_ULT_ALT
  FROM GRUPO_ORCADO gpo
  WHERE gpo.PERIODO = var_periodo_copia;
    
  ----------------------------------------------
  -- DUPLICA AS INFORMACOES DA CONTA CONTABIL --
  ----------------------------------------------
  
  DELETE FROM orcamento_contabil.CONTA_CONTABIL
  WHERE PERIODO = var_periodo_atual;
  
  --SEM GRUPO OR�ADO
  INSERT INTO orcamento_contabil.CONTA_CONTABIL
  SELECT seq_conta_cd_conta_contabil.NEXTVAL AS CD_CONTA_CONTABIL,
  cc.CD_CONTA_MV, cc.CD_SETOR, var_periodo_atual AS PERIODO,
  cc.ORDEM, cc.SN_GRUPO_ORCADO,
  cc.CD_GRUPO_ORCADO, cc.VL_ORCADO,
  var_cd_usuario AS CD_USUARIO_CADASTRO, SYSDATE AS HR_CADASTRO,
  NULL AS CD_USUARIO_ULT_ALT, NULL AS HR_ULT_ALT
  FROM orcamento_contabil.CONTA_CONTABIL cc
  WHERE cc.PERIODO = var_periodo_copia
  AND cc.SN_GRUPO_ORCADO = 'N';
  
  --COM GRUPO OR�ADO
  INSERT INTO orcamento_contabil.CONTA_CONTABIL
  SELECT seq_conta_cd_conta_contabil.NEXTVAL AS CD_CONTA_CONTABIL,
  cc.CD_CONTA_MV, cc.CD_SETOR, var_periodo_atual AS PERIODO,
  cc.ORDEM, cc.SN_GRUPO_ORCADO,
  (SELECT CD_GRUPO_ORCADO 
   FROM orcamento_contabil.GRUPO_ORCADO
   WHERE DS_GRUPO_ORCADO || '-' || VL_ORCADO IN (SELECT DS_GRUPO_ORCADO || '-' || VL_ORCADO AS CRUZAMENTO
                                                FROM orcamento_contabil.GRUPO_ORCADO gpo
                                                WHERE CD_GRUPO_ORCADO IN (SELECT CD_GRUPO_ORCADO 
                                                                          FROM orcamento_contabil.CONTA_CONTABIL 
                                                                          WHERE CD_CONTA_CONTABIL =  cc.CD_CONTA_CONTABIL))
   AND PERIODO = var_periodo_atual) AS CD_GRUPO_ORCADO,
  cc.VL_ORCADO,
  var_cd_usuario AS CD_USUARIO_CADASTRO, SYSDATE AS HR_CADASTRO,
  NULL AS CD_USUARIO_ULT_ALT, NULL AS HR_ULT_ALT
  FROM orcamento_contabil.CONTA_CONTABIL cc
  WHERE cc.PERIODO = var_periodo_copia
  AND cc.SN_GRUPO_ORCADO = 'S';

COMMIT;
END;
