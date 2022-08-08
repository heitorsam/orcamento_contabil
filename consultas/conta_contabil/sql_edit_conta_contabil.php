<?php 
    session_start();	

    //ACESSO RESTRITO
    include '../../acesso_restrito.php';

    //CONEXAO
    include '../../conexao.php';  
    
    //RECEBENDO SESSAO
    $var_cd_usuario = $_SESSION['usuarioLogin']; 
    

    //RECEBENDO POST
    $edit_cd_conta_contabil = $_POST["edit_cd_conta_contabil"];


    echo 'var_edit_ordem: ';
    echo $var_edit_ordem = $_POST["frm_edit_ordem"];
    echo '</br>';

    echo 'var_edit_cd_setor: ';
    echo $var_edit_cd_setor = $_POST["frm_edit_cd_setor"];
    echo '</br>';

    echo 'var_edit_periodo: ';
     $var_edit_periodo = $_POST["frm_edit_periodo"]; 
    
    echo $var_edit_periodo = SUBSTR($var_edit_periodo,5,2) . '/' . SUBSTR($var_edit_periodo,0,4);
    echo '</br>';

    echo 'var_edit_codigo_mv: ';
    echo $var_edit_codigo_mv = $_POST["frm_edit_codigo_mv"];
    echo '</br>';

    echo 'var_edit_descricao_mv: ';
    echo $var_edit_descricao_mv = $_POST["frm_edit_descricao_mv"];
    echo '</br>';

    echo 'var_edit_grupo_orcado_sn: ';
    echo $var_edit_grupo_orcado_sn = $_POST["frm_edit_grupo_orcado_sn"];  
    echo '</br>';


    if($var_edit_grupo_orcado_sn == 'N'){
        echo 'var_cd_grupo_orcado: ';
        echo $var_edit_valor_orcado = str_replace(".", ",",  $_POST["frm_edit_valor_orcado"]);  
        

        
        echo $var_edit_cd_grupo_orcado = NULL;
        echo '</br>';

    }else{

        echo $var_edit_valor_orcado = NULL;

    echo 'var_edit_cd_grupo_orcado: ';
    echo $var_edit_cd_grupo_orcado = $_POST["frm_cd_grupo_orcado"];
    echo '</br>';


    }  

    //echo $var_edit_periodo = SUBSTR($var_edit_periodo,5,2) . '/' . SUBSTR($var_edit_periodo,0,4);
    //echo $var_edit_meta = str_replace(".", ",",  $_POST["frm_edit_meta"]);

    /////////////////////////
    //EDITAR Conta Contabil//
    /////////////////////////
 

    echo $edit_conta_contabil = "UPDATE orcamento_contabil.CONTA_CONTABIL cc 
                                    SET cc.CD_CONTA_MV = '$var_edit_codigo_mv',
                                        cc.CD_SETOR = '$var_edit_cd_setor',
                                        cc.PERIODO = '$var_edit_periodo',
                                        cc.ORDEM = '$var_edit_ordem',
                                        cc.SN_GRUPO_ORCADO = '$var_edit_grupo_orcado_sn',
                                        cc.CD_GRUPO_ORCADO = '$var_edit_cd_grupo_orcado',
                                        cc.VL_ORCADO = REPLACE('$var_edit_valor_orcado',',','.'), 
                                        cc.CD_USUARIO_ULT_ALT = '$var_cd_usuario',
                                        cc.HR_ULT_ALT = SYSDATE
                                    WHERE cc.CD_CONTA_CONTABIL = '$edit_cd_conta_contabil'
                                ";

        
    $result_edit_conta_contabil= oci_parse($conn_ora, $edit_conta_contabil);

    $valida = oci_execute($result_edit_conta_contabil);

    if (!$valida) {   

        $erro = oci_error($result_edit_conta_contabil);																							
        $_SESSION['msgerro'] = htmlentities($erro['message']);
        header('location: ../../cadastro_conta_contabil.php'); 

    }else{

        $_SESSION['msg'] = 'Conta Contábil editado com sucesso!';
        header('location: ../../cadastro_conta_contabil.php'); 

    } 









?>