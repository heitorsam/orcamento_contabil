<?php 
//require_once('acesso_restrito.php');

session_start();
include_once("../conexao.php");

//RECEBENDO SESSAO
$var_cd_usuario = $_SESSION['usuarioLogin']; 

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$sn_ativo = filter_input(INPUT_GET, 'sn_ativo', FILTER_SANITIZE_STRING);
$tabela = filter_input(INPUT_GET, 'tabela', FILTER_SANITIZE_STRING);
$item = filter_input(INPUT_GET, 'item', FILTER_SANITIZE_NUMBER_INT);
$pagina = '../' . filter_input(INPUT_GET, 'pagina', FILTER_SANITIZE_STRING) . '.php';

echo $id . "</br></br>";
echo $sn_ativo . "</br></br>";
echo $tabela . "</br></br>";
echo $item . "</br></br>";
	
echo $proc_sn_ativo = "BEGIN 

                         orcamento_contabil.PRC_SN_ATIVO('$tabela','$sn_ativo','$id','$var_cd_usuario');
                                
                       END;";
    
$result_sn_ativo = oci_parse($conn_ora, $proc_sn_ativo);

$valida = oci_execute($result_sn_ativo);

if ($valida) {   

    $_SESSION['msg'] = 'Alteração realizada com sucesso!';
    header('location: ' . $pagina);

}else{
																							
    $_SESSION['msgerro'] = 'Já existem vínculos com esse ' . ucwords(strtolower(str_replace('_',' ',$tabela))) . '!';
    header('location: ' . $pagina);

}  
