<!-- Botão Modal Cadastro Pendencia -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cad_pendencia">
    <i class="fas fa-plus"></i> Cadastrar 
</button>

<!-- Modal Cadastro Pendencia -->
<div class="modal fade" id="cad_pendencia" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content ">
            <form action="consultas/grupo_orcado/sql_cad_grupo_orcado_inserir.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Cadastrar </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                                
                    <div class="row">
                        <div class="col-md-12">
                            <label>Nome:</label>
                            <input type="text" class="form-control" name="frm_cad_nome" placeholder="Digite o nome" required>
                        </div>
                    </div>
                   
                    <div class="div_br"> </div>
                    <div class="div_br"> </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <label>Valor orçado:</label>
                            <input type="number" step="any" class="form-control" name="frm_cad_meta" placeholder="Digite o valor orçado" required>
                        </div>

                        <div class="col-md-4">
                            <label>Período:</label>
                            <input type="month" class="form-control" name="frm_cad_periodo" placeholder="Digite o período" required>
                        </div>
                    </div>

                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> <i class="fas fa-times"></i> Fechar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
