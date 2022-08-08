<?php 

    session_start();	

    //ACESSO RESTRITO
    include '../../acesso_restrito.php';

    //CONEXAO
    include '../../conexao.php';  
    
    //RECEBENDO SESSAO
    $var_cd_usuario = $_SESSION['usuarioLogin']; 
    
    //RECEBENDO POST
    echo '</br>';
    echo $var_periodo = $_POST["frm_periodo"];  
    echo '</br>';
    echo $var_cd_conta_contabil = $_POST["frm_cd_conta_contabil"];
    echo '</br>'; 
    echo $var_descricao = $_POST["frm_descricao"];
    echo '</br>'; 
    echo $var_realizado = str_replace(",", ".",  $_POST["frm_realizado"]);
    echo '</br>';

    ////////////////////////////
    //EXCLUIR REALIZADO MANUAL//
    ////////////////////////////

    echo $exclui_realizado = "DELETE FROM orcamento_contabil.REALIZADO_MANUAL rm
                              WHERE rm.CD_CONTA_CONTABIL = '$var_cd_conta_contabil'";
    echo '</br>';
    
    $result_exclui_realizado = oci_parse($conn_ora, $exclui_realizado);

    $valida = oci_execute($result_exclui_realizado);

    if (!$valida) {   

        $erro = oci_error($result_exclui_realizado);																							
        $_SESSION['msgerro'] = htmlentities($erro['message']);
        header('location: ../../cadastro_conta_contabil.php'); 

    }else{

        ////////////////////////////
        //INSERE REALIZADO MANUAL///
        ////////////////////////////

        echo $insere_realizado = "INSERT INTO orcamento_contabil.REALIZADO_MANUAL rm
                                  SELECT seq_realiz_cd_realizado_manual.NEXTVAL AS CD_REALIZADO_MANUAL,
                                  '$var_cd_conta_contabil' AS CD_CONTA_CONTABIL, '$var_periodo' AS PERIODO, 
                                  '$var_realizado' AS VL_REALIZADO,
                                  '$var_cd_usuario' AS CD_USUARIO_CADASTRO, SYSDATE AS HR_CADASTRO,
                                  NULL AS CD_USUARIO_ULT_ALT, NULL AS HR_USUARIO_ULT_ALT
                                  FROM DUAL";
        echo '</br>';

        $result_insere_realizado = oci_parse($conn_ora, $insere_realizado);

        $valida = oci_execute($result_insere_realizado);

        if (!$valida) {   

            $erro = oci_error($result_insere_realizado);																							
            $_SESSION['msgerro'] = htmlentities($erro['message']);
            header('location: ../../cadastro_conta_contabil.php'); 

        }else{

            $_SESSION['msg'] = 'Realizado editado com sucesso!';
            header('location: ../../cadastro_conta_contabil.php'); 

        }  

    }  


?>