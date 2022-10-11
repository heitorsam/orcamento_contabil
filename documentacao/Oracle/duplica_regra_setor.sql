BEGIN 
  orcamento_contabil.PRC_DUPLICA_REGRAS_MES_SETOR('08/2022','12/2022','HSSAMPAIO',9);
END;

SELECT * FROM orcamento_contabil.SETOR;

SELECT * FROM orcamento_contabil.CONTA_CONTABIL WHERE PERIODO = '12/2022' AND CD_SETOR = 9;

SELECT * FROM GRUPO_ORCADO;

CREATE OR REPLACE PROCEDURE PRC_DUPLICA_REGRAS_MES_SETOR(
var_periodo_copia VARCHAR2,var_periodo_atual VARCHAR2, var_cd_usuario VARCHAR2, var_cd_setor NUMBER)

IS

BEGIN

  --PROCEDURE CRIADA POR HEITOR SCALABRINI

  --------------------------------------------
  -- DUPLICA AS INFORMACOES DO GRUPO ORCADO --
  --------------------------------------------

  DELETE FROM orcamento_contabil.GRUPO_ORCADO
  WHERE PERIODO = var_periodo_atual
  AND CD_GRUPO_ORCADO IN(SELECT DISTINCT cc.CD_GRUPO_ORCADO
                         FROM orcamento_contabil.CONTA_CONTABIL cc
                         WHERE cc.PERIODO = var_periodo_atual
                         AND cc.CD_SETOR = var_cd_setor
                         AND cc.SN_GRUPO_ORCADO = 'S'
                         AND cc.CD_GRUPO_ORCADO IS NOT NULL);

  INSERT INTO GRUPO_ORCADO
  SELECT seq_grupo_cd_grupo_orcado.NEXTVAL AS CD_GRUPO_ORCADO,
  gpo.DS_GRUPO_ORCADO, var_periodo_atual AS PERIODO, gpo.VL_ORCADO, gpo.SN_ATIVO,
  var_cd_usuario AS CD_USUARIO_CADASTRO, SYSDATE AS HR_CADASTRO,
  NULL AS CD_USUARIO_ULT_ALT, NULL AS HR_ULT_ALT
  FROM GRUPO_ORCADO gpo
  WHERE gpo.PERIODO = var_periodo_copia
  AND gpo.CD_GRUPO_ORCADO IN(SELECT DISTINCT cc.CD_GRUPO_ORCADO
                             FROM orcamento_contabil.CONTA_CONTABIL cc
                             WHERE cc.PERIODO = var_periodo_copia
                             AND cc.CD_SETOR = var_cd_setor
                             AND cc.SN_GRUPO_ORCADO = 'S'
                             AND cc.CD_GRUPO_ORCADO IS NOT NULL);

  ----------------------------------------------
  -- DUPLICA AS INFORMACOES DA CONTA CONTABIL --
  ----------------------------------------------

  DELETE FROM orcamento_contabil.CONTA_CONTABIL
  WHERE PERIODO = var_periodo_atual
  AND CD_SETOR = var_cd_setor;

  --SEM GRUPO ORÇADO
  INSERT INTO orcamento_contabil.CONTA_CONTABIL
  SELECT seq_conta_cd_conta_contabil.NEXTVAL AS CD_CONTA_CONTABIL,
  cc.CD_CONTA_MV, cc.CD_SETOR, var_periodo_atual AS PERIODO,
  cc.ORDEM, cc.SN_GRUPO_ORCADO,
  cc.CD_GRUPO_ORCADO, cc.VL_ORCADO,
  var_cd_usuario AS CD_USUARIO_CADASTRO, SYSDATE AS HR_CADASTRO,
  NULL AS CD_USUARIO_ULT_ALT, NULL AS HR_ULT_ALT
  FROM orcamento_contabil.CONTA_CONTABIL cc
  WHERE cc.PERIODO = var_periodo_copia
  AND cc.CD_SETOR = var_cd_setor
  AND cc.SN_GRUPO_ORCADO = 'N';

  --COM GRUPO ORÇADO
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
                                                                           WHERE CD_CONTA_CONTABIL = cc.CD_CONTA_CONTABIL))
  AND PERIODO = var_periodo_atual) AS CD_GRUPO_ORCADO,
  cc.VL_ORCADO,
  var_cd_usuario AS CD_USUARIO_CADASTRO, SYSDATE AS HR_CADASTRO,
  NULL AS CD_USUARIO_ULT_ALT, NULL AS HR_ULT_ALT
  FROM orcamento_contabil.CONTA_CONTABIL cc
  WHERE cc.PERIODO = var_periodo_copia
  AND cc.CD_SETOR = var_cd_setor
  AND cc.SN_GRUPO_ORCADO = 'S';

COMMIT;
END;
