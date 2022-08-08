<!-- BOTAO MODAL EDIT REALIZADO -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit_realizado<?php echo $row_conta_contabil['CD_CONTA_CONTABIL'];?>">
    <i class="fas fa-edit"></i> 
</button>

<!-- MODAL EDITAR REALIZADO -->
<div class="modal fade" id="edit_realizado<?php echo $row_conta_contabil['CD_CONTA_CONTABIL'];?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- CHAMANDO ACAO SQL_EDIT_REALIZADO -->
            <form action="consultas/conta_contabil/sql_edit_realizado.php" method="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Editar Realizado </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">    

                        <div class="col-md-2" style="text-align: left;"> 
                            <label>Período:</label>
                            <input type="text" class="form-control" name="frm_periodo" value="<?php echo @$row_conta_contabil['PERIODO']; ?>" readonly>
                        </div> 

                        <div class="col-md-2" style="text-align: left;"> 
                            <label>Reduzido:</label>
                            <input type="text" class="form-control" name="frm_cd_conta_contabil" value="<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>" readonly>
                        </div> 

                        <div class="col-md-7" style="text-align: left;"> 
                            <label>Descrição:</label>
                            <input type="text" class="form-control" name="frm_descricao" value="<?php echo @$row_conta_contabil['DS_CONTA']; ?>" readonly>
                        </div> 

                    </div> 

                    <div class="div_br"> </div>

                    <div class="row">    
                        <div class="col-md-5" style="text-align: left;"> 
                            <label>Valor:</label>
                            <input type="number" step="any" class="form-control" name="frm_realizado" value="<?php echo @$row_conta_contabil['VL_REALIZADO']; ?>" required>
                        </div> 
                    </div> 
                    
                    <div class="div_br"> </div>
                    <div class="div_br"> </div>                        
                        
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> <i class="fas fa-times"></i> Fechar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> Editar</button>
                </div>
            </form>
        </div>
    </div>
</div>
