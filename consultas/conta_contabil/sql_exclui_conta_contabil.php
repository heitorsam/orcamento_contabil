<?php 

    session_start();	

    //ACESSO RESTRITO
    include '../../acesso_restrito.php';

    //CONEXAO
    include '../../conexao.php';  
    
    //RECEBENDO SESSAO
    $var_cd_usuario = $_SESSION['usuarioLogin']; 
    
    //RECEBENDO POST
    echo $var_cd_conta_contabil = $_GET["cd_conta_contabil"];
    echo '</br>'; 

    ////////////////////////////
    //EXCLUIR REALIZADO MANUAL//
    ////////////////////////////

    //FALTA EXCLUIR O REALIZADO MANUAL PRA NAO SUJAR O BANCO

    echo $exclui_realizado = "DELETE FROM orcamento_contabil.REALIZADO_MANUAL mn
                              WHERE mn.CD_CONTA_CONTABIL = '$var_cd_conta_contabil'";
    echo '</br>';
    
    $result_exclui_realizado = oci_parse($conn_ora, $exclui_realizado);

    $valida = oci_execute($result_exclui_realizado);

    if (!$valida) {   

        $erro = oci_error($result_exclui_realizado);																							
        $_SESSION['msgerro'] = htmlentities($erro['message']);
        header('location: ../../cadastro_conta_contabil.php'); 

    }else{

        //////////////////////////
        //EXCLUIR CONTA CONTABIL//
        //////////////////////////

        //FALTA EXCLUIR O REALIZADO MANUAL PRA NAO SUJAR O BANCO

        echo $exclui_conta = "DELETE FROM orcamento_contabil.CONTA_CONTABIL cc
        WHERE cc.CD_CONTA_CONTABIL = '$var_cd_conta_contabil'";
        echo '</br>';

        $result_exclui_conta = oci_parse($conn_ora, $exclui_conta);

        $valida = oci_execute($result_exclui_conta);

        if (!$valida) {   

            $erro = oci_error($result_exclui_conta);																							
            $_SESSION['msgerro'] = htmlentities($erro['message']);
            header('location: ../../cadastro_conta_contabil.php'); 

        }else{

            $_SESSION['msg'] = 'Conta contábil excluída com sucesso!';
            header('location: ../../cadastro_conta_contabil.php');

        }  

    }  
    
?>