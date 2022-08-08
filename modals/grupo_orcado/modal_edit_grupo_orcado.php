
    <!-- Button Modal Editar -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editar<?php echo $var_modal_editar ?>">
        <i class="fas fa-edit"></i>
    </button>

<!-- Modal Editar-->
<div class="modal fade" id="editar<?php echo $var_modal_editar ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="consultas/grupo_orcado/sql_cad_grupo_orcado_editar.php" method="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Editar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body text-left">
                    


                    <input type="hidden" name="frm_cd_grupo_orcado" class="form-control" value="<?php echo @$row_grupo_orcado['CD_GRUPO_ORCADO']; ?>">


                    <div class='row'>
                        <div class="col-md-12">
                            <label>Nome:</label>
                            <input type="text" class="form-control" name="frm_edit_nome" value="<?php echo @$row_grupo_orcado['DS_GRUPO_ORCADO']; ?>" required>
                        </div>
                    </div>

                    <div class="div_br"> </div>
                    <div class="div_br"> </div>

                    <?php 
                        @$var_periodo_filtro = @substr($row_grupo_orcado['PERIODO'],3,4) . '-' . @substr($row_grupo_orcado['PERIODO'],0,2);
                    ?>
                    <div class='row'>
                        <div class="col-md-8">
                            <label>Valor orçado:</label>
                            <input type="number" step="any" class="form-control" name="frm_edit_meta" value="<?php echo @$row_grupo_orcado['VL_ORCADO_FLOAT']; ?>" required>
                        </div>
                  
                    
                        <div class="col-md-4">
                            <label>Período:</label>
                            <input type="month" class="form-control" name="frm_edit_periodo" value="<?php echo @$var_periodo_filtro; ?>" required>
                        </div>
                    </div>

        
                    <div class="div_br"> </div>
                    <div class="div_br"> </div>
                    
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> Editar</button>
                </div>
            </form>
        </div>
    </div>
</div>