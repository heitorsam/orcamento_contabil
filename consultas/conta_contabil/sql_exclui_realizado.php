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

        $_SESSION['msg'] = 'Realizado excluído com sucesso!';
        header('location: ../../cadastro_conta_contabil.php');

    }  

?>