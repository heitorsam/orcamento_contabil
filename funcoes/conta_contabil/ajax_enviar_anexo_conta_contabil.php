<?php
session_start();
//require_once('acesso_restrito.php');?>

<?php
include_once("../../conexao.php");

//INFORMACOES DO USUARIO
$login_usuario = $_SESSION['usuarioLogin'];
$cd_documento_conta_contabil = $_POST['cd_conta_contabil'];
//$ds_doc = $_POST['ds_doc'];

$currentDir = getcwd();
    $uploadDirectory = "uploads/";

    // Store all errors
    $errors = [];

    // Available file extensions
    $fileExtensions = ['jpeg','jpg','png','pdf'];

    $tamanho = 1024 * 1024;

    //print_r($_FILES);

   if(!empty($_FILES['fileAjax'] != null)) {
        $file = $_FILES['fileAjax'];
        print_r($file);
        $fileName = $file['name'];
        $fileTmpName  = $file['tmp_name'];
        $fileExtension = strtolower(pathinfo($fileName,PATHINFO_EXTENSION));
        $nome_arquivo_personalizado = $file['name'];
        //$nome_arquivo_personalizado = str_replace(' ', '_', $nome_arquivo_personalizado);

        //DECLARANDO VARIAVEIS DO ARQUIVO PARA IMPORTACAO PARA O BANCO
        $image = file_get_contents($file['tmp_name']);
        if (isset($fileName)) {
            if (!in_array($fileExtension,$fileExtensions)) {
                $errors[] = "São suportadas somente imagens JPEG, JPG and PNG e arquivos PDF.";
            }
            if($file['size'] >= $tamanho){
                $_SESSION['msgerro'] = "Arquivo muito grande!";
                $errors[] = "Arquivo muito grande!";
    
            }
            if (empty($errors)) {
                //echo $didUpload =;
                
                echo "A imagem " . basename($fileName) . " foi enviada.";

            } else {
                foreach ($errors as $error) {
                    echo $error . "Ocorreu o seguinte erro: " . "\n";
                    header('Location: ../../cadastro_conta_contabil.php');

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

   $valida = oci_free_statement($result_insert_AD);
   $blob->free();
  
   if(isset($valida)){
    header('Location: ../../cadastro_conta_contabil.php');
   }else{
    $_SESSION['msgerro'] = "Ocorreu um erro!";
   }
   echo '</br>';

   /*$stmt = $conn_ora->prepare($consulta_insert_AD);
   $fp = fopen($file['tmp_name'], 'rb');

   $stmt ->bindParam(":image",$fp,PDO::PARAM_LOB);
   $stmt ->execute();*/

   
}