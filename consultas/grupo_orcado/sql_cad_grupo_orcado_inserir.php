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
    echo $var_cad_nome = $_POST["frm_cad_nome"];
    echo '</br>';
    echo $var_cad_periodo = $_POST["frm_cad_periodo"];  
    echo '</br>';
    echo $var_cad_periodo = SUBSTR($var_cad_periodo,5,2) . '/' . SUBSTR($var_cad_periodo,0,4);
    echo '</br>';
    echo $var_cad_meta = str_replace(",", ".",  $_POST["frm_cad_meta"]);
    echo '</br>'; 

    /////////////////////////
    //CADASTRO GRUPO ORCADO//
    /////////////////////////

    echo $cad_grupo_orcado = "INSERT INTO orcamento_contabil.GRUPO_ORCADO
                                    (CD_GRUPO_ORCADO,
                                    DS_GRUPO_ORCADO,
                                    PERIODO,
                                    VL_ORCADO,
                                    SN_ATIVO,
                                    CD_USUARIO_CADASTRO,
                                    HR_CADASTRO)
                              VALUES
                                    (seq_grupo_cd_grupo_orcado.NEXTVAL,
                                    '$var_cad_nome',
                                    '$var_cad_periodo',
                                    '$var_cad_meta',
                                    'S',
                                    '$var_cd_usuario',
                                    SYSDATE)
                            ";
    
    $result_cad_grupo_orcado= oci_parse($conn_ora, $cad_grupo_orcado);

    $valida = oci_execute($result_cad_grupo_orcado);

    if (!$valida) {   

        $erro = oci_error($result_cad_grupo_orcado);																							
        $_SESSION['msgerro'] = htmlentities($erro['message']);
        header('location: ../../cadastro_grupo_orcado.php'); 

    }else{

        $_SESSION['msg'] = 'Grupo orçado cadastrado com sucesso!';
        header('location: ../../cadastro_grupo_orcado.php'); 

    }  

?>