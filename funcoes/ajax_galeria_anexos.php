<?php 

    include '../conexao.php';

    $cd_conta_contabil = $_GET['cd_conta_contabil'];

    $cons_qtd = "SELECT COUNT(*) as qtd
                        FROM orcamento_contabil.ANEXOS an
                    WHERE an.Cd_Conta_Contabil = $cd_conta_contabil";
    $result_qtd = oci_parse($conn_ora, $cons_qtd);

    oci_execute($result_qtd);

    $row_qtd = oci_fetch_array($result_qtd);

    $qtd = $row_qtd['QTD'];

    $cons_anexo = "SELECT an.cd_anexo,
                        an.LO_ARQUIVO_DOCUMENTO,
                        an.cd_conta_contabil,
                        an.Ds_Nome_Arquivo,
                        an.TP_EXTENSAO

                    FROM orcamento_contabil.ANEXOS an
                    WHERE an.Cd_Conta_Contabil = $cd_conta_contabil
                    ";

    $result_anexo = oci_parse($conn_ora, $cons_anexo);

    oci_execute($result_anexo);

        $p = 0;
    if($qtd > 0){
        while($row_anexo = oci_fetch_array($result_anexo)){
            $img = $row_anexo['LO_ARQUIVO_DOCUMENTO']->load();
            
            $array_img[$p] = base64_encode($img); 

            $array_cd_anexo[$p] = $row_anexo['CD_ANEXO'];
            
            $array_ext[$p] = $row_anexo['TP_EXTENSAO'];
            
            $p++;
        }
        $ext = '.jpeg';
        include 'galeria.php';
    }
?>