<?php 
    session_start();	

    //ACESSO RESTRITO
    include '../acesso_restrito.php';

    //CONEXAO
    include '../conexao.php';  
    
    //RECEBENDO SESSAO
    $var_cd_usuario = $_SESSION['usuarioLogin']; 

    echo $var_ref = $_POST["frm_mes_ref"];
    echo '</br>';

    echo $var_new = $_POST["frm_mes_new"];
    echo '</br>';
    
    //RECEBENDO POST
    echo '</br>';
    echo $var_ref = SUBSTR($var_ref,5,2) . '/' . SUBSTR($var_ref,0,4);
    echo '</br>';
    echo $var_new = SUBSTR($var_new,5,2) . '/' . SUBSTR($var_new,0,4);

    /////////////////////////
    //DUPLICA REGRA PERIODO//
    /////////////////////////

    //PASSAR VAR_SETOR

    echo $cad_duplica_periodo = "BEGIN 

                                    orcamento_contabil.PRC_DUPLICA_REGRAS_MES_SETOR('$var_ref','$var_new','$var_cd_usuario');
                                
                                END;";
    
    $result_cad_duplica_periodo= oci_parse($conn_ora, $cad_duplica_periodo);

    $valida = oci_execute($result_cad_duplica_periodo);

    if (!$valida) {   

        $erro = oci_error($result_cad_duplica_periodo);																							
        $_SESSION['msgerro'] = htmlentities($erro['message']);
        header('location: ../duplicar_periodo.php'); 

    }else{

        $_SESSION['msg'] = 'Período duplicado com sucesso!';
        header('location: ../duplicar_periodo.php'); 

    }  

?>