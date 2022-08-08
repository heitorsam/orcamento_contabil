<?php 
    session_start();	

    //ACESSO RESTRITO
    include '../../acesso_restrito.php';

    //CONEXAO
    include '../../conexao.php';  
    
    //RECEBENDO SESSAO
    $var_cd_usuario = $_SESSION['usuarioLogin']; 
    
    //RECEBENDO POST
    $var_cd_setor = $_POST["frm_cd_setor"];
    echo $var_edit_setor = $_POST["frm_edit_setor"];
    echo $var_edit_gerente = $_POST["frm_edit_gerente"];  

    /////////////////////
    //EDIÇÃO PENDENCIAS//
    /////////////////////

    echo $edit_setor = "UPDATE orcamento_contabil.SETOR st
                                SET st.DS_SETOR  = '$var_edit_setor',
                                    st.NM_GESTOR = '$var_edit_gerente',
                                    st.CD_USUARIO_ULT_ALT = '$var_cd_usuario',
                                    st.HR_ULT_ALT = SYSDATE
                            WHERE CD_SETOR = '$var_cd_setor' ";                          
                            
    
    $result_edit_setor= oci_parse($conn_ora, $edit_setor);

    $valida = oci_execute($result_edit_setor);

    if (!$valida) {   

        $erro = oci_error($result_edit_setor);																							
        $_SESSION['msgerro'] = htmlentities($erro['message']);
        header('location: ../../cadastro_setor.php'); 

    }else{

        $_SESSION['msg'] = 'Setor editado com sucesso!';
        header('location: ../../cadastro_setor.php'); 

    }  

?>
