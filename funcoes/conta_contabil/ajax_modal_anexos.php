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
            <input type="file" id="fileAjax" name="fileAjax">
        </div>

        <div class="col-md-2">
            </br>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i></button>
        </div>
    </div>
</form>

<script>
    function ajax_cad_anexo() {
        event.preventDefault();

        var myFile = document.getElementById('fileAjax'); 
        var statusP = document.getElementById('status');
        var cd_documento_conta_contabil = document.getElementById('js_anexo_cd_conta_contabil').value;

        // Get the files from the form input
        var files = myFile.files;

        //alert(files);
        //alert(cd_documento_requerente);


        statusP.innerHTML = 'Carregando...';

        // Create a FormData object
        var formData = new FormData();

        // Select only the first file from the input array
        var file = files[0];

        
       

        // Add the file to the AJAX request
        formData.append('fileAjax', file, file.name);
        formData.append('cd_documento_conta_contabil', cd_documento_conta_contabil);

        // Set up the request
        var xhr = new XMLHttpRequest();
        // Open the connection
        xhr.open('POST', 'funcoes/conta_contabil/ajax_enviar_anexo_conta_contabil.php', true);

        // Set up a handler for when the task for the request is complete
        xhr.onload = function () {
            if (xhr.status == 200) {
                statusP.innerHTML = 'Upload realizado com sucesso!';
                //window.setTimeout(function(){location.reload()},500);
                $('#div_carrosel').load('funcoes/conta_contabil/ajax_galeria_anexos.php?cd_conta_contabil='+cd_documento_conta_contabil)
                $('#div_anexos').load('funcoes/conta_contabil/ajax_modal_anexos.php?cd_conta_contabil='+cd_documento_conta_contabil)
                //AnexoFotoTabela();
                //CHAMANDO O NOVO SELECT TIPO DOC
                //SelectTipoDoc();
            } else {
                statusP.innerHTML = 'Erro ao realizar o Upload. Tente novamente.';
                //window.setTimeout(function(){location.reload()},500);
                //AnexoFotoTabela();
                //CHAMANDO O NOVO SELECT TIPO DOC
                //SelectTipoDoc();
            }
            
        };

        // Send the data.
        xhr.send(formData);

    }
</script>
