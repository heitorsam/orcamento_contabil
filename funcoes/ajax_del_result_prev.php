<?php

    session_start();

    //CONEXAO
    include '../conexao.php';    
    
    $var_cd_np = $_POST['cd_np']; 

    $cons_insert_res_prev = "DELETE FROM orcamento_contabil.NECESSIDADE_PREVISTA np
                             WHERE np.CD_NECESSIDADE_PREVISTA = $var_cd_np";

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