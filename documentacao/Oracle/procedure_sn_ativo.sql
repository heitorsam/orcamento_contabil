CREATE OR REPLACE PROCEDURE PRC_SN_ATIVO(var_tabela    in varchar2
                                         ,var_sn_ativo in varchar2
                                         ,var_codigo   in int
                                         ,var_usuario  in varchar2
) IS

var_qtd_vinculos int := 0;
msg_dados_existentes_sn_ativo EXCEPTION;
    
BEGIN
  
  ---------
  --SETOR--
  ---------
  
  --IF SETOR    
  IF var_tabela = 'SETOR' THEN
    
      --VERIFICA VINCULOS
      SELECT COUNT(*) AS QTD
      INTO var_qtd_vinculos
      FROM orcamento_contabil.CONTA_CONTABIL cc
      WHERE cc.CD_SETOR = var_codigo; 
        
      --IF QTD VINCULOS
      IF var_qtd_vinculos >= 1 THEN
        
          RAISE msg_dados_existentes_sn_ativo;
        
      ELSE 
    
          UPDATE orcamento_contabil.SETOR st
          SET st.SN_ATIVO = var_sn_ativo,
              st.CD_USUARIO_ULT_ALT = var_usuario,
              st.HR_ULT_ALT = SYSDATE
          WHERE st.CD_SETOR = var_codigo;
      
      END IF;
      --FIM IF QTD VINCULOS

  END IF;
  --FIM IF SETOR
  
  ----------------
  --GRUPO OR�ADO--
  ---------------- 

  --IF GRUPO_ORCADO    
  IF var_tabela = 'GRUPO_ORCADO' THEN
    
       --VERIFICA VINCULOS
       SELECT COUNT(*) AS QTD
       INTO var_qtd_vinculos
       FROM orcamento_contabil.CONTA_CONTABIL cc
       WHERE cc.CD_GRUPO_ORCADO = var_codigo; 
        
      --IF QTD VINCULOS
      IF var_qtd_vinculos >= 1 THEN
        
          RAISE msg_dados_existentes_sn_ativo;
        
      ELSE 
    
          UPDATE orcamento_contabil.SETOR st
          SET st.SN_ATIVO = var_sn_ativo,
              st.CD_USUARIO_ULT_ALT = var_usuario,
              gpo.HR_ULT_ALT = SYSDATE
          WHERE st.CD_SETOR = var_codigo;
      
      END IF;
      --FIM IF QTD VINCULOS

  END IF;
  --FIM IF SETOR
  
EXCEPTION
    WHEN msg_dados_existentes_sn_ativo THEN
        RAISE_APPLICATION_ERROR(-20001,'J� EXISTEM VINCULOS NA CONTA CONTABIL!');

END;
