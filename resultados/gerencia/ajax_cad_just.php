<?php
    include '../../conexao.php';

    session_start();

    $cd_usuario = $_SESSION['usuarioLogin'];

    $cd_contabil = $_POST['cd_conta'];

    $ds_just_1 = $_POST['ds_just_1'];
    $ds_just_2 = $_POST['ds_just_2'];
    $ds_just_3 = $_POST['ds_just_3'];

    $cons_cad_just = "DELETE FROM JUSTIFICATIVA_CONTABIL_CLOB cc
    WHERE cc.CD_CONTA_CONTABIL = $cd_contabil";

    $result_cad_just = oci_parse($conn_ora, $cons_cad_just);

    oci_execute($result_cad_just);
    
  

        echo $cons_cad_just = "INSERT INTO orcamento_contabil.JUSTIFICATIVA_CONTABIL_CLOB
                                    (CD_CONTA_CONTABIL,
                                    DS_JUSTIFICATIVA_1,
                                    DS_JUSTIFICATIVA_2,
                                    DS_JUSTIFICATIVA_3,
                                    CD_USUARIO_CADASTRO,
                                    HR_CADASTRO,
                                    CD_USUARIO_ULT_ALT,
                                    HR_ULT_ALT)
                            VALUES
                            ($cd_contabil,
                            EMPTY_CLOB(),
                            EMPTY_CLOB(),
                            EMPTY_CLOB(),
                            '$cd_usuario',
                            SYSDATE,
                            '$cd_usuario',
                            SYSDATE)
                            RETURNING
                            DS_JUSTIFICATIVA_1, DS_JUSTIFICATIVA_2, DS_JUSTIFICATIVA_3 
                            INTO :just1, :just2, :just3";

        $result_cad_just = oci_parse($conn_ora, $cons_cad_just);

        $myLOB1 = oci_new_descriptor($conn_ora, OCI_D_LOB);
        $myLOB2 = oci_new_descriptor($conn_ora, OCI_D_LOB);
        $myLOB3 = oci_new_descriptor($conn_ora, OCI_D_LOB);

        oci_bind_by_name($result_cad_just, ":just1", $myLOB1, -1, OCI_B_CLOB);
        oci_bind_by_name($result_cad_just, ":just2", $myLOB2, -1, OCI_B_CLOB);
        oci_bind_by_name($result_cad_just, ":just3", $myLOB3, -1, OCI_B_CLOB);

        $resultado = oci_execute($result_cad_just, OCI_NO_AUTO_COMMIT);

        // Now save a value to the LOB
        $myLOB1->save($ds_just_1);
        $myLOB2->save($ds_just_2);
        $myLOB3->save($ds_just_3);

        oci_commit($conn_ora);
        
        // Free resources
        oci_free_statement($result_cad_just);

        $myLOB1->free();
        $myLOB2->free();
        $myLOB3->free();

?>