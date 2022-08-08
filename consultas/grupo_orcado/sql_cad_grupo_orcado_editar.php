<?php 
    session_start();	

    //ACESSO RESTRITO
    include '../../acesso_restrito.php';

    //CONEXAO
    include '../../conexao.php';  
    
    //RECEBENDO SESSAO
    $var_cd_usuario = $_SESSION['usuarioLogin']; 
    
    //RECEBENDO POST
    $var_cd_grupo_orcado = $_POST["frm_cd_grupo_orcado"];
    echo $var_edit_nome = $_POST["frm_edit_nome"];
    echo $var_edit_periodo = $_POST["frm_edit_periodo"];  

    echo $var_edit_periodo = SUBSTR($var_edit_periodo,5,2) . '/' . SUBSTR($var_edit_periodo,0,4);

    echo $var_edit_meta = str_replace(".", ",",  $_POST["frm_edit_meta"]);

    ///////////////////////
    //EDITAR GRUPO ORCADO//
    ///////////////////////

    echo $edit_grupo_orcado = "UPDATE orcamento_contabil.GRUPO_ORCADO gpo
                               SET gpo.DS_GRUPO_ORCADO = '$var_edit_nome', 
                                   gpo.PERIODO = '$var_edit_periodo',
                                   gpo.VL_ORCADO = REPLACE('$var_edit_meta',',','.'),
                                   gpo.CD_USUARIO_ULT_ALT = '$var_cd_usuario ',
                                   gpo.HR_ULT_ALT = SYSDATE
                               WHERE gpo.CD_GRUPO_ORCADO = '$var_cd_grupo_orcado'";
        
    $result_edit_grupo_orcado= oci_parse($conn_ora, $edit_grupo_orcado);

    $valida = oci_execute($result_edit_grupo_orcado);

    if (!$valida) {   

        $erro = oci_error($result_edit_grupo_orcado);																							
        $_SESSION['msgerro'] = htmlentities($erro['message']);
        header('location: ../../cadastro_grupo_orcado.php'); 

    }else{

        $_SESSION['msg'] = 'Grupo orçado editado com sucesso!';
        header('location: ../../cadastro_grupo_orcado.php'); 

    } 

?>


