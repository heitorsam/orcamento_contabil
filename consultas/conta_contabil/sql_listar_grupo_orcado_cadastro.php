
<?php 


    @$var_frm_cd_grupo_orcado = $_POST['frm_cd_grupo_orcado'];
    /////////
    //SETOR//
    /////////

    if(isset($_POST['frm_cd_grupo_orcado'])){

        $consulta_dados_grupo_orcado = "SELECT g.CD_GRUPO_ORCADO, g.DS_GRUPO_ORCADO, g.PERIODO
                                 FROM orcamento_contabil.Grupo_Orcado g
                                 WHERE g.SN_ATIVO = 'S'                
                                 AND g.CD_GRUPO_ORCADO <> $var_frm_cd_grupo_orcado
                                 ORDER BY g.DS_GRUPO_ORCADO, g.PERIODO ASC";

    } else {

        $consulta_dados_grupo_orcado = "SELECT g.CD_GRUPO_ORCADO, g.DS_GRUPO_ORCADO, g.PERIODO
                                 FROM orcamento_contabil.Grupo_Orcado g
                                 WHERE g.SN_ATIVO = 'S'                         
                                 ORDER BY g.DS_GRUPO_ORCADO, g.PERIODO ASC";

    }
    
    $result_dados_grupo_orcado = oci_parse($conn_ora, $consulta_dados_grupo_orcado);																									

    //EXECUTANDO A CONSULTA SQL (ORACLE)
    @oci_execute($result_dados_grupo_orcado);

?>


