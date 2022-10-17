<?php

    session_start();

    //CONEXAO
    include '../conexao.php';    
    
    $var_ds_periodo = $_POST['var_periodo']; 
    $var_vl_prev = $_POST['var_prev']; 
    $va_usu = $_SESSION['usuarioLogin']; 

    $cons_insert_res_prev = "INSERT INTO orcamento_contabil.NECESSIDADE_PREVISTA np
                             SELECT orcamento_contabil.SEQ_CD_NECESSIDADE_PREVISTA.NEXTVAL AS CD_NECESSIDADE_PREVISTA,
                             '$var_ds_periodo' AS PERIODO, 
                             REPLACE('$var_vl_prev',',','.') AS VL_NECESSIDADE_PREVISTA,
                             '$va_usu' AS CD_USUARIO_CADASTRO,
                             SYSDATE AS HR_CADASTRO,
                             NULL AS CD_USUARIO_CADASTRO_ULT_ALT,
                             NULL AS HR_ULT_ALT
                             FROM DUAL";

    $insert_prev = oci_parse($conn_ora, $cons_insert_res_prev);

    $valida = oci_execute($insert_prev);

    //VALIDACAO
    if (!$valida) {   
        
        $erro = oci_error($insert_prev);																							
        $msg_erro = htmlentities($erro['message']);
        //header("Location: $pag_login");
        //echo $erro;
        echo $msg_erro;

    }else{

        echo 'Sucesso';
        
    }

?>