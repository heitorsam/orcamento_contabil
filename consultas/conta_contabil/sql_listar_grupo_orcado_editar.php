<?php 
    @$var_frm_cd_grupo_orcado = $_POST['frm_cd_grupo_orcado'];

    $periodo = @$row_conta_contabil['PERIODO']; 

    /////////
    //SETOR//
    /////////

    if(isset($_POST['frm_cd_grupo_orcado'])){

        $consulta_dados_grupo_orcado = "SELECT g.CD_GRUPO_ORCADO, g.DS_GRUPO_ORCADO
                                 FROM orcamento_contabil.Grupo_Orcado g
                                 WHERE g.SN_ATIVO = 'S'                 
                                 AND g.CD_GRUPO_ORCADO <> $var_frm_cd_grupo_orcado
                                 AND g.PERIODO = '$periodo'
                                 ORDER BY g.DS_GRUPO_ORCADO ASC";
    } else {

        $consulta_dados_grupo_orcado = "SELECT g.CD_GRUPO_ORCADO, g.DS_GRUPO_ORCADO
                                 FROM orcamento_contabil.Grupo_Orcado g
                                 WHERE g.SN_ATIVO = 'S'  
                                 AND g.PERIODO = '$periodo'                        
                                 ORDER BY g.DS_GRUPO_ORCADO ASC";

    }
    
    $result_dados_grupo_orcado = oci_parse($conn_ora, $consulta_dados_grupo_orcado);																									

    //EXECUTANDO A CONSULTA SQL (ORACLE)
    @oci_execute($result_dados_grupo_orcado);

    /////////////////
    //TODOS - SETOR//
    /////////////////
    /*
        $consulta_dados_setor = "SELECT 0 AS CD_GRUPO_ORCADO, 'TODOS' AS DS_GRUPO_ORCADO
                                FROM DUAL

                                UNION ALL    
        
                                SELECT res.* FROM (
                                SELECT g.CD_GRUPO_ORCADO, g.DS_GRUPO_ORCADO
                                FROM orcamento_contabil.Grupo_Orcado g
                                WHERE g.SN_ATIVO = 'S'
                                ORDER BY g.DS_GRUPO_ORCADO ASC) res";

        $result_dados_setor = oci_parse($conn_ora, $consulta_dados_setor);																									

        //EXECUTANDO A CONSULTA SQL (ORACLE)
        @oci_execute($result_dados_setor);
    */

    ///////////////
    //SETOR ATUAL//
    ///////////////
    
    

        if($var_frm_cd_grupo_orcado == 0){

            $consulta_dados_grupo_orcado_at = "SELECT 0 AS CD_GRUPO_ORCADO, 'TODOS' AS DS_GRUPO_ORCADO
                                        FROM DUAL";
    
        } else {
    
            $consulta_dados_grupo_orcado_at = "SELECT grupo.CD_GRUPO_ORCADO, grupo.DS_GRUPO_ORCADO
                                        FROM orcamento_contabil.Grupo_Orcado grupo  
                                        WHERE grupo.CD_GRUPO_ORCADO = $var_frm_cd_grupo_orcado
                                        AND grupo.PERIODO = '$periodo'";
        }
    
        
    
        $result_dados_grupo_orcado_at = oci_parse($conn_ora, $consulta_dados_grupo_orcado_at);																									
    
        //EXECUTANDO A CONSULTA SQL (ORACLE)
        @oci_execute($result_dados_grupo_orcado_at);
        @$row_dados_grupo_orcado_at = oci_fetch_array($result_dados_grupo_orcado_at);
?>


