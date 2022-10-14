<div class="row justify-content-md-center">

    <?php
    
    //I
    $i = 0;

    //BUSCANDO ARQUIVOS DE IMAGENS
    //$dir = new DirectoryIterator($path);

    foreach($array_img as $path) {

        if(strlen($ext) > 1) {
            $imagem = $path;
            
    ?>

            <div class="col-6 col-md-3 col-lg-2 col-sm-6" style="margin: 0px !important; background-color: #ffffff !important;">

                <!--IMAGEM 01-->
                <a style="height: 30px !important; padding: 0px 6px 0px 6px;" 
                data-toggle="modal" data-target="#detalhejust<?php echo $i;?>">
                    <?php if($array_ext[$i] == 'PDF' OR $array_ext[$i] == 'pdf'){?>



                        <!--<object class="img_foto_pequena" data="data:application/pdf;base64,<?php echo $imagem; ?>" type="application/pdf" ></object>
                        <div class="btn btn-primary">visualizar</div>-->
                        <img src="img/pdf_pequeno.jpg" class="img_foto_pequena">  





                    <?php }else{ ?>
                    
                        <img src="data:image/<?php echo $array_ext[$i] . ';base64,'. $imagem;?>" class="img_foto_pequena">  

                    <?php } ?>    
                </a>

                <div class="modal" tabindex="-1" role="dialog" id="detalhejust<?php echo $i;?>">
                    <div class="modal-dialog" style="max-width: 100vw !important;
    max-height: 86vh !important;
    background-color: #3f3f3f !important;"  role="document">
                        <div class="modal-content" 
                        style="padding: 0 !important; margin:0 !important; background-color: rgb(0, 0, 0, 0); border: none;">
                                
                            <div class="modal-body" style="text-align: center !important">                         

                                <div>
                                    <div style='margin: 0 auto; width: 100%; height: 86vh; 
                                    background-repeat: no-repeat; background-size:contain; 
                                    background-position:center top;
                                    <?php if($array_ext[$i] <> 'PDF' AND $array_ext[$i] <> 'pdf'){?>
                                        background-image: url(data:image/<?php echo $array_ext[$i] . ';base64,'. $imagem;?>)
                                    <?php } ?>
                                    '>
                                        
                                        <div style="margin: 0 auto; width: 100%; line-height: 20px;">
                                            
                                            <a style="text-decoration: none;" class="botoes_modal anterior<?php echo $i;?>"> 
                                                <i class="fas fa-angle-left"></i> Anterior</i>
                                            <a>

                                            <a style="text-decoration: none; color: #dc3545" onclick="ajax_apagar_anexo('<?php echo $cd_conta_contabil ?>','<?php echo $array_cd_anexo[$i] ?>')" class="botoes_modal">
                                                <i class='fas fa-trash'></i>
                                            </a>

                                            <a style="text-decoration: none;" class="botoes_modal fechar<?php echo $i;?>">
                                                <i class="far fa-times-circle"></i>
                                            </a>

                                            <a style="text-decoration: none;" class="botoes_modal proximo<?php echo $i;?> "> 
                                                Próximo <i class="fas fa-angle-right"></i></i>
                                            </a>  

                                        </div>
                                        <?php if($array_ext[$i] == 'PDF' OR $array_ext[$i] == 'pdf'){ ?>
                                            </br>
                                            <div style="height: 100%; width: 100%;">
                                                <object style="height: 100%; width: 100%; " data="data:application/pdf;base64,<?php echo $imagem; ?>" ></object>
                                            </div>
                                        <?php } ?>

                                    </div>
                                </div>                                                 

                            </div>
                                                                        
                        </div>

                    </div>
                            
                </div>

            </div>

            <script>

            $(function () {
                $(".fechar<?php echo $i;?>").on('click', function() {
                    $('#detalhejust<?php echo $i;?>').modal('hide');
                });
            });

            $(function () {
                $(".anterior<?php echo $i;?>").on('click', function() {
                    $('#detalhejust<?php echo $i - 1;?>').modal('show');
                    $('#detalhejust<?php echo $i;?>').modal('hide');
                });
            });

            $(function () {
                $(".proximo<?php echo $i;?>").on('click', function() {
                    $('#detalhejust<?php echo $i + 1;?>').modal('show');
                    $('#detalhejust<?php echo $i;?>').modal('hide');
                });
            });

            </script>

    <?php 
    $i++;
    }} 
    ?>

</div>


<style>

/*GALERIA*/

.img_foto_pequena{

    width: 100%; 
    height: 160px; 
    object-fit:cover; 
    border-radius: 5px;
    cursor: pointer;

}

.img_foto_pequena:hover{

    height: 180px;
    cursor: pointer;

}

.modal-lg {
    max-width: 100% !important;

}

.botoes_modal{

    font-size: 18px;
    color: #70aedc;
    margin-left: 10px;
    margin-right: 10px;
    text-decoration: none;
    cursor: pointer;

    text-shadow: 0.05em 0.05em 0.1em #70aedc;

}

.botoes_modal:hover{

    font-size: 17px;
    color: #f6f6f6;
    
}

.link_servicos:hover {
    cursor: pointer;
}

</style>