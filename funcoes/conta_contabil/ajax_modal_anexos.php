<?php 

    session_start();

    $cd_conta_contabil = $_GET['cd_conta_contabil'];

   
?>






<form action="funcoes/conta_contabil/ajax_enviar_anexo_conta_contabil.php" method="post" enctype="multipart/form-data">
    <input type="text" name="cd_conta_contabil" hidden value="<?php echo $cd_conta_contabil ?>">
    <div class="row">
        <div class="form-group col-md-10">
            Arquivo:(limite de 1MB)
            <br>
            <input type="file" id="fileAjax" accept=".png, .jpg, .jpeg, .pdf" name="fileAjax">
        </div>

        <div class="col-md-2">
            </br>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i></button>
        </div>
    </div>
</form>

