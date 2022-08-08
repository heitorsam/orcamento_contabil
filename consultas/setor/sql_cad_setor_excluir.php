<?php 
    session_start();	

    //ACESSO RESTRITO
    include '../../acesso_restrito.php';

    //CONEXAO
    include '../../conexao.php';  
    
    //RECEBENDO SESSAO
    $var_cd_usuario = $_SESSION['usuarioLogin']; 
    
    //RECEBENDO POST
     $var_cd_pendencia = $_POST["frm_cd_pendencia"];

    ////////////////////////
    ///VALIDA LANÇAMENTOS///
    ////////////////////////
    echo $lancamento ="SELECT 
                  CASE
                    WHEN COUNT(tpp.CD_TP_PENDENCIAS) >=1 THEN 'PEND_LANCADA' 
                    ELSE 'PEND_NAO_LANCADA'
                  END AS LANCAMENTOS
                  FROM pgrme.PENDENCIA pend
                  INNER JOIN pgrme.TP_PENDENCIAS tpp
                    ON tpp.CD_TP_PENDENCIAS = pend.CD_TP_PENDENCIA
                  WHERE tpp.CD_TP_PENDENCIAS = '$var_cd_pendencia'";

    $result_valida_lancamentos = oci_parse($conn_ora, $lancamento);
    oci_execute($result_valida_lancamentos);

    $row_lanc = oci_fetch_array($result_valida_lancamentos);

    if($row_lanc['LANCAMENTOS'] == 'PEND_LANCADA'){
        
        $_SESSION['msgerro'] = 'Já existem lançamentos com essa pendência!';
        header('location: ../../cad_tipo_pendencia.php'); 
    
    }else{
         
        ///////////////////////
        //CADASTRO PENDENCIAS//
        ///////////////////////

        $exclui_pendencia = "DELETE FROM pgrme.TP_PENDENCIAS 
                                WHERE CD_TP_PENDENCIAS = '$var_cd_pendencia'";
        
        $result_exclui_pendencia= oci_parse($conn_ora, $exclui_pendencia);

        $valida = oci_execute($result_exclui_pendencia);

        if (!$valida) {   

            $erro = oci_error($result_exclui_pendencia);																							
            $_SESSION['msgerro'] = htmlentities($erro['message']);
            header('location: ../../cad_tipo_pendencia.php'); 

        }else{

            $_SESSION['msg'] = 'Pendência excluida com sucesso!';
            header('location: ../../cad_tipo_pendencia.php'); 
        }
    }     

?>