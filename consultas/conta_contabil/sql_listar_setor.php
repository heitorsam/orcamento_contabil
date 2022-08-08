<?php 

   //PARA MODAL CADASTRAR

   /////////
   //SETOR//
   /////////

    $consulta_dados_setor = "SELECT st.CD_SETOR, st.DS_SETOR
    FROM orcamento_contabil.SETOR st
    WHERE st.SN_ATIVO = 'S'                            
    ORDER BY st.DS_SETOR ASC";

    $result_dados_setor = oci_parse($conn_ora, $consulta_dados_setor);																									

    //EXECUTANDO A CONSULTA SQL (ORACLE)
    @oci_execute($result_dados_setor);

   ///////////////////////////////////////////////////////////////////////////////////////////
   ///////////////////////////////////////////////////////////////////////////////////////////


   //PARA MODAL EDITAR

   @$var_cd_setor =  @$var_cd_setor;
    /////////
    //SETOR//
    /////////

        $consulta_dados_setor_edit = "SELECT st.CD_SETOR, st.DS_SETOR
                                    FROM orcamento_contabil.SETOR st
                                    WHERE st.SN_ATIVO = 'S' 
                                    AND st.CD_SETOR <> $var_cd_setor                           
                                    ORDER BY st.DS_SETOR ASC";

    
    
    $result_dados_setor_edit = oci_parse($conn_ora, $consulta_dados_setor_edit);																									

    //EXECUTANDO A CONSULTA SQL (ORACLE)
    @oci_execute($result_dados_setor_edit);

    


?>


