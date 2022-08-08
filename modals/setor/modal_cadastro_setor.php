<!-- Botão Modal Cadastro Pendencia -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cad_pendencia">
    <i class="fas fa-plus"></i> Cadastrar 
</button>

<!-- Modal Cadastro Pendencia -->
<div class="modal fade" id="cad_pendencia" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="consultas/setor/sql_cad_setor_inserir.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Cadastrar </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                        
                        <label>Setor:</label>
                        <input type="text" maxlength="20" class="form-control" name="frm_cad_setor" placeholder="Digite o setor" required>

                        <div class="div_br"> </div>
                        <div class="div_br"> </div>
                        

                        <label>Gerente:</label>
                        <input type="text" class="form-control" name="frm_cad_gerente" placeholder="Digite o gerente" required>
                        
                        <div class="div_br"> </div>
                        <div class="div_br"> </div>
                        
                        <label>Usuario:</label>
                        <input type="text" class="form-control" name="frm_cad_usuario" placeholder="Digite o usuario" required>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> <i class="fas fa-times"></i> Fechar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
