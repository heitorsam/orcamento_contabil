

<!-- Botão Modal Cadastro Conta Contabil -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#pendentes">
    <i class="fas fa-eye"></i> Pendencias  
</button>

<!-- Modal Cadastro Conta Contabil -->
<div class="modal fade" id="pendentes" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="consultas/conta_contabil/sql_cad_conta_contabil_inserir.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Cadastrar </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
               
                <?php 
                
                //echo $var_periodo_filtro = SUBSTR($var_periodo_filtro,5,2) . '/' . SUBSTR($var_periodo_filtro,0,4);
                //echo $var_periodo_filtro; ?>
                <?php 
                $lista_pendentes = "SELECT 
                                    CASE
                                        WHEN SUBSTR(pc.CD_CONTABIL, 0, 1) = 3 THEN
                                            'RECEITA'
                                    ELSE
                                            'DESPESA'
                                    END AS DS_CLASSIFICACAO,
                                    pc.CD_REDUZIDO,
                                    pc.DS_CONTA
                                    FROM dbamv.PLANO_CONTAS pc
                                    WHERE SUBSTR(pc.CD_CONTABIL, 0, 1) IN (3, 4)
                                    AND pc.SN_ATIVO = 'S'
                                    AND pc.CD_REDUZIDO NOT IN
                                        (SELECT DISTINCT cc.CD_CONTA_MV
                                         FROM orcamento_contabil.CONTA_CONTABIL cc
                                         WHERE cc.PERIODO = '$var_periodo_filtro')
                                    ORDER BY DS_CLASSIFICACAO, CD_REDUZIDO, DS_CONTA ASC";

                $result_pendentes  = oci_parse($conn_ora, $lista_pendentes );

                @oci_execute($result_pendentes);
                
                ?>

                    <!-- DIV COM SCROLL -->
                    <div style="width: 100%; height: 300px; overflow-y: scroll;">

                        <!--TABELA DE RESULTADOS -->
                        <div class="table-responsive col-md-12">
                            
                            <table class="table table-fixed table-hover table-striped " cellspacing="0" cellpadding="0">

                                <thead><tr>
                                    <!--COLUNAS-->
                                    <th class="align-middle" style="text-align: center !important;"><span>Período</span></th>
                                    <th class="align-middle" style="text-align: center !important;"><span>Setor</span></th>
                                    <th class="align-middle" style="text-align: center !important;"><span>Ordem</span></th>

                                </tr></thead>            

                                <tbody>
                                    <?php
                                    $var_modal_editar = 1;
                                    $var_modal_excluir = 1;

                                        while($row_pendentes = @oci_fetch_array($result_pendentes)){  ?>  
                                            
                                        <tr>
                                            <td class='align-middle' style='text-align: center;'><?php echo @$row_pendentes['DS_CLASSIFICACAO']; ?></td>
                                            <td class='align-middle' style='text-align: center;'><?php echo @$row_pendentes['CD_REDUZIDO']; ?></td>
                                            <td class='align-middle' style='text-align: left;'><?php echo @$row_pendentes['DS_CONTA']; ?></td>
                                        </tr>
                                        
                                        <?php 
                                            $var_modal_editar = $var_modal_editar + 1;
                                            $var_modal_excluir = $var_modal_excluir + 1;
                                        } ?>
                                </tbody>           
                            </table>
                            </div>
                        
                        </div>

                    </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> <i class="fas fa-times"></i> Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>

