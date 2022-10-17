<?php 
    include '../../conexao.php';

    session_start();

    $cd_setor = $_POST['cd_setor'];

    $usuario = $_POST['cd_usuario'];

    $consulta = "DELETE orcamento_contabil.USUARIOS_SETOR WHERE CD_SETOR = $cd_setor AND CD_USUARIO = '$usuario'
                                                    ";
    $resultado = oci_parse($conn_ora, $consulta);

    oci_execute($resultado);



?>