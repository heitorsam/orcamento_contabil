<?php
session_start();
//require_once('acesso_restrito.php');?>

<?php
include_once("../../conexao.php");

//INFORMACOES DO USUARIO
@$login_usuario = $_SESSION['usuarioLogin'];

@$cd_documento_conta_contabil = $_POST['cd_documento_conta_contabil'];
echo @$ds_doc = $_POST['ds_doc'];
print_r($_FILES);

$currentDir = getcwd();
    $uploadDirectory = "uploads/";

    // Store all errors
    $errors = [];

    // Available file extensions
    $fileExtensions = ['jpeg','jpg','png','pdf'];

    //print_r($_FILES);

   if(!empty($_FILES['fileAjax'] != null)) {
      $fileName = $_FILES['fileAjax']['name'];
      echo $fileTmpName  = $_FILES['fileAjax']['tmp_name'];
      $fileType = $_FILES['fileAjax']['type'];
      $fileExtension = strtolower(pathinfo($fileName,PATHINFO_EXTENSION));
      $extensao_arquivo = strrchr( $fileName, '.' );

      echo $nome_arquivo_personalizado = $_FILES['fileAjax']['name'];
      $uploadPath = $currentDir . $uploadDirectory . basename($fileName);

      //DECLARANDO VARIAVEIS DO ARQUIVO PARA IMPORTACAO PARA O BANCO
      $image = file_get_contents($_FILES['fileAjax']['tmp_name']);

        if (isset($fileName)) {
            if (! in_array($fileExtension,$fileExtensions)) {
                $errors[] = "São suportadas somente imagens JPEG, JPG and PNG e arquivos PDF.";
            }
            if (empty($errors)) {
                echo $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
                if ($didUpload) {
                    echo "A imagem " . basename($fileName) . " foi enviada.";
                } else {
                    echo "Um erro ocorreu no envio dos dados. Tente Novamente.";
                }
            } else {
                foreach ($errors as $error) {
                    echo $error . "Ocorreu o seguinte erro: " . "\n";
                }
            }
        }
    } 



if(empty($errors)){
   $consulta_insert_AD = "INSERT INTO orcamento_contabil.ANEXOS
                                    (CD_ANEXO,
                                    CD_CONTA_CONTABIL,
                                    DS_NOME_ARQUIVO,
                                    TP_EXTENSAO,
                                    CD_USUARIO_CADASTRO,
                                    HR_CADASTRO,
                                    LO_ARQUIVO_DOCUMENTO
                                    )
                          VALUES 
                                    (orcamento_contabil.SEQ_ANEXOS.NEXTVAL,
                                    $cd_documento_conta_contabil,
                                    '$nome_arquivo_personalizado',
                                    '$fileExtension',
                                    '$login_usuario',
                                    SYSDATE,
                                    empty_blob()
                                    ) RETURNING LO_ARQUIVO_DOCUMENTO INTO :image";


   echo '<br>' . $consulta_insert_AD . '<br>';

   $result_insert_AD = oci_parse($conn_ora, $consulta_insert_AD);
   $blob = oci_new_descriptor($conn_ora, OCI_D_LOB);
   oci_bind_by_name($result_insert_AD, ":image", $blob, -1, OCI_B_BLOB);
   oci_execute($result_insert_AD, OCI_DEFAULT);

   if(!$blob->save($image)) {
      oci_rollback($conn_ora);
   }
   else {
      oci_commit($conn_ora);
   }

   oci_free_statement($result_insert_AD);
   $blob->free();
  
   
   echo '</br>';

   
}