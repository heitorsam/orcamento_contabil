<!-- Button Modal Excluir -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#excluir<?php echo $var_modal_excluir ?>">
    <i class="fas fa-trash"></i>
</button>

<!-- Modal Excluir-->
<div class="modal fade" id="excluir<?php echo $var_modal_excluir ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog  modal-lg">
    <div class="modal-content">
        <form action="" method="POST">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Excluir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-left">


                <!--ID PENDENCIA-->
                <input type="hidden" name="frm_cd_pendencia" class="form-control" value="<?php echo @$row_lista_pend['CD_TP_PENDENCIAS']; ?>">

                Tipo de Pendência:
                <input type="text" class="form-control" value="<?php echo @$row_lista_pend['DS_PENDENCIA']; ?>" readonly>
                
                <div class="div_br"> </div>
                <div class="div_br"> </div>

                Classificação da Pendência:
                <p class="text-left text-uppercase">
                    <?php
                        //IF COR
                            //LEVE (VERDE)
                            if($row_lista_pend['DS_CLASSIFICACAO'] == 'L' ){
                                
                                echo "<i class='fas fa-circle' style='color: #93df72; font-size: 18px;'></i> - Leve";
                            
                            //MÉDIA (AMARELA)
                            } elseif($row_lista_pend['DS_CLASSIFICACAO'] == 'M' ) { 

                                echo "<i class='fas fa-circle' style='color: #ffe07c;  font-size: 18px;'></i> - Medio";

                            //OUTROS GRAVE (VERMELHO)
                            } else {

                                echo "<i class='fas fa-circle' style='color: #ff6262;  font-size: 18px;'></i> - Grave";
                            } 
                    ?>
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Excluir</button>
            </div>
        </form>
    </div>
</div>
</div>