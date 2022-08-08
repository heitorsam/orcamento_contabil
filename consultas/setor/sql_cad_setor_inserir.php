<?php 
    session_start();	

    //ACESSO RESTRITO
    include '../../acesso_restrito.php';

    //CONEXAO
    include '../../conexao.php';  
    
    //RECEBENDO SESSAO
    $var_cd_usuario = $_SESSION['usuarioLogin']; 
    
    //RECEBENDO POST
    echo $var_cad_setor = $_POST["frm_cad_setor"];
    echo $var_cad_gerente = $_POST["frm_cad_gerente"];  
    echo $var_cad_usuario = $_POST["frm_cad_usuario"];
    //////////////////
    //CADASTRO SETOR//
    //////////////////

    echo $cad_setor = "INSERT INTO orcamento_contabil.SETOR
                                (CD_SETOR, DS_SETOR, NM_GESTOR, SN_ATIVO, CD_USUARIO_CADASTRO, HR_CADASTRO, CD_USUARIO)
                            VALUES
                                (seq_setor_cd_setor.NEXTVAL,
                                '$var_cad_setor',
                                '$var_cad_gerente',
                                'S',
                                '$var_cd_usuario',
                                SYSDATE,
                                UPPER('$var_cad_usuario')
                                )
    ";
    
    $result_cad_setor= oci_parse($conn_ora, $cad_setor);

    $valida = oci_execute($result_cad_setor);

    if (!$valida) {   

        $erro = oci_error($result_cad_setor);																							
        $_SESSION['msgerro'] = htmlentities($erro['message']);
        header('location: ../../cadastro_setor.php'); 

    }else{

        $_SESSION['msg'] = 'Setor cadastrado com sucesso!';
        header('location: ../../cadastro_setor.php'); 

    }  

?>