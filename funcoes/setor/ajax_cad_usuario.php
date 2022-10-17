<?php 
    include '../../conexao.php';

    session_start();

    $usuarios_login = $_SESSION['usuarioLogin'];

    $cd_setor = $_POST['cd_setor'];

    $usuario = $_POST['cd_usuario'];

    $consulta = "INSERT INTO 
                    orcamento_contabil.USUARIOS_SETOR (CD_SETOR, 
                                                        CD_USUARIO, 
                                                        CD_USUARIO_CADASTRO, 
                                                        HR_CADASTRO) 
                                                    VALUES($cd_setor,
                                                        UPPER('$usuario'),
                                                        UPPER('$usuarios_login'),
                                                        SYSDATE)
                                                    ";
    $resultado = oci_parse($conn_ora, $consulta);

    oci_execute($resultado);



?>