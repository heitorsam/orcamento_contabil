<?php 
    session_start();	

    //ACESSO RESTRITO
    include '../../acesso_restrito.php';

    //CONEXAO
    include '../../conexao.php';  
    
    //RECEBENDO SESSAO
    $var_cd_usuario = $_SESSION['usuarioLogin']; 
    
    //RECEBENDO POST
    echo 'var_ordem: ';
    echo $var_ordem = $_POST['frm_cad_ordem'];
    echo '</br>';

    echo 'var_codigo_mv: ';
    echo $var_codigo_mv = $_POST['frm_cad_codigo_mv'];
    echo '</br>';

    echo 'var_descricao: ';
    echo $var_descricao = $_POST['frm_cad_descricao_mv'];
    echo '</br>';

    echo 'var_periodo: ';
    echo $var_periodo = $_POST['frm_cad_periodo'];
    echo '</br>';

    echo 'var_periodo: ';
    echo $var_periodo = SUBSTR($var_periodo,5,2) . '/' . SUBSTR($var_periodo,0,4);
    echo '</br>';

    echo 'var_cd_setor: ';
    echo $var_cd_setor = $_POST['frm_cd_setor'];
    echo '</br>';

    echo 'var_grupo_orcado_sn: ';
    echo $var_grupo_orcado_sn = $_POST['frm_grupo_orcado_sn'];
    echo '</br>';
 

    echo 'var_valor_orcado: ';

    if($var_grupo_orcado_sn == 'N'){

        echo $var_valor_orcado = str_replace(".", ",",  $_POST["frm_cad_valor_orcado"]);

        echo 'var_cd_grupo_orcado: ';
        echo $var_cd_grupo_orcado = NULL;
        echo '</br>';

    }else{

        echo $var_valor_orcado = NULL;

        echo 'var_cd_grupo_orcado: ';
        echo $var_cd_grupo_orcado = $_POST['frm_cd_grupo_orcado'];
        echo '</br>';


    }  



    ///////////////////////////
    //CADASTRO CONTA CONTABIL//
    ///////////////////////////

    echo $cad_conta_contabil = "INSERT INTO orcamento_contabil.CONTA_CONTABIL
                                    (CD_CONTA_CONTABIL,
                                    CD_CONTA_MV,
                                    CD_SETOR,
                                    PERIODO,
                                    ORDEM,
                                    SN_GRUPO_ORCADO,
                                    CD_GRUPO_ORCADO,
                                    VL_ORCADO,
                                    CD_USUARIO_CADASTRO,
                                    HR_CADASTRO
                                    )
                                VALUES
                                    (seq_conta_cd_conta_contabil.NEXTVAL,
                                    '$var_codigo_mv',
                                    '$var_cd_setor',
                                    '$var_periodo',
                                    '$var_ordem',
                                    '$var_grupo_orcado_sn',
                                    '$var_cd_grupo_orcado',
                                    REPLACE('$var_valor_orcado',',','.'), 
                                    '$var_cd_usuario',
                                    SYSDATE)
                            ";
    
    $result_cad_conta_contabil= oci_parse($conn_ora, $cad_conta_contabil);

    $valida = oci_execute($result_cad_conta_contabil);

    if (!$valida) {   

        $erro = oci_error($result_cad_conta_contabil);																							
        $_SESSION['msgerro'] = htmlentities($erro['message']);
        header('location: ../../cadastro_conta_contabil.php'); 

    }else{

        $_SESSION['msg'] = 'Conta Contábil cadastrado com sucesso!';
        header('location: ../../cadastro_conta_contabil.php'); 

    }  

?>