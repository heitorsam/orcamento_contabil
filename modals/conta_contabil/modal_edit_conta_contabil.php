<?php 
    //////////////////////////////////////////
    //POPULANDO A LISTA SETOR / GRUPO ORÇADO//
    //////////////////////////////////////////

    //RECEBENDO CD SETOR
     $var_cd_setor = $row_conta_contabil['CD_SETOR'];

    //RECEBENDO POST
    @$var_frm_cd_grupo_orcado = $_POST['frm_cd_grupo_orcado'];
    @$var_frm_edit_valor_orcado = $_POST['frm_edit_valor_orcado'];
  
    //SQL
    include 'consultas/conta_contabil/sql_listar_setor.php';  
    //include 'consultas/conta_contabil/sql_listar_grupo_orcado_editar.php';  

?>

<!-- Button Modal Editar -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editar<?php echo $var_modal_editar ?>">
    <i class="fas fa-edit"></i>
</button>

<!-- Modal Editar-->
<div class="modal fade" id="editar<?php echo $var_modal_editar ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="consultas/conta_contabil/sql_edit_conta_contabil.php" method="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Editar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body text-left">
                    
                    <input type="hidden" name="edit_cd_conta_contabil" class="form-control" value="<?php echo @$row_conta_contabil['CD_CONTA_CONTABIL']; ?>">


                    <div class="row"> 
                        <div class="col-md-4"> 
                                <label>Ordem:</label>
                                <input type="number" class="form-control" name="frm_edit_ordem" value="<?php echo @$row_conta_contabil['ORDEM']; ?>"required>
                        </div>

                        <!-- Select - SETOR -->
                        <div class="col-md-4"> 
                            <label>Setor:</label>
                                <select id="id_setor" name="frm_edit_cd_setor" class="form-control" required>
                                    <?php
                                        while($row_dados_setor_edit = oci_fetch_array($result_dados_setor_edit)){	
                                            echo "<option value='" . $row_dados_setor_edit['CD_SETOR'] . "'>" . $row_dados_setor_edit['DS_SETOR'] . "</option>";
                                        }
                                    ?>

                                    <?php
                                            echo "<option value='" . $row_conta_contabil['CD_SETOR'] . "'selected>" . $row_conta_contabil['DS_SETOR'] . "</option>";
                                    ?>
                                </select>
  
                        </div>

                        <?php 
                            //CONVERTE O PERIODO
                            @$var_periodo_filtro = @substr($row_conta_contabil['PERIODO'],3,4) . '-' . @substr($row_conta_contabil['PERIODO'],0,2);
                        ?>
                      
                        <!-- Text - PERÍODO -->
                        <div class="col-md-4"> 
                            <label>Período:</label>
                            <input type="month" class="form-control" name="frm_edit_periodo" id='jv_periodo_edit<?php echo $var_modal_editar; ?>' value="<?php echo @$var_periodo_filtro; ?>" required>
                        </div>
                    </div>

                
                    <div class="div_br"> </div>
                 

                    <div class="row"> 
                        
                    
                        <!-- Text - CÓDIGO -->
                        <div class="col-md-2"> 
                            <label>Código:</label>
                            <input type="text" class="form-control" name="frm_edit_codigo_mv" id="jv_reduzido_edit<?php echo $var_modal_editar; ?>" value="<?php echo @$row_conta_contabil['CD_CONTA_MV']; ?>"required>
                        </div> 

                        <!-- Text - DESCRIÇÃO -->
                        <div class="col-md-10"> 
                            <label>Descrição:</label>
                            <input type="text" class="form-control" name="frm_edit_descricao_mv"  id="jv_descricao_edit<?php echo $var_modal_editar; ?>" value="<?php echo @$row_conta_contabil['DS_CONTA']; ?>"readonly>
                        </div>

                    </div> 

                 <div class="div_br"> </div>
                   

                    <div class="row">  
                        
                        <!-- Select S/N - GRUPO ORÇADO -->
                        <div class="col-md-4"> 
                            <label>Grupo Orçado:</label>
                            <select class="custom-select" name="frm_edit_grupo_orcado_sn" id="grupo_orcado_edit<?php echo $var_modal_editar ?>" onChange="update<?php echo $var_modal_editar; ?>()"required>   
                            <?php 
                                if(!isset($row_conta_contabil['CD_GRUPO_ORCADO'])){
                                    echo '<option value="S">Sim</option>';
                                    echo '<option value="N" selected>Não</option>';
                                }else{
                                    echo '<option value="S" selected>Sim</option>';
                                    echo '<option value="N">Não</option>';
                                    
                                }
                            ?>

                            </select>
                        </div>

                                         <!-- Select - GRUPO ORÇADO -->
                        <div class="col-md-8" id="div_grupo_edit<?php echo $var_modal_editar ?>" > 
                            <div id="lista_orcado<?php echo $var_modal_editar; ?>"></div>      
                        </div>

                        <!-- Valor Orçado -->
                        <div class="col-md-4" style="display:none;" id="div_valor_orcado_edit<?php echo $var_modal_editar ?>"> 
                            <label>Valor Orçado:</label>
                            <input type="number" step="any" class="form-control" name="frm_edit_valor_orcado" id="edit_valor_orcado<?php echo $var_modal_editar ?>" value="<?php echo @$row_conta_contabil['VL_ORCADO']; ?>" placeholder="Digite o Valor Orçado">
                        </div>

                    </div> 
                    
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

 <!-- SCRIPT (SELECT S/N - GRUPO ORÇADO) -->
 <script type="text/javascript">

var div_grupo_edit<?php echo $var_modal_editar ?> = document.getElementById("div_grupo_edit<?php echo $var_modal_editar ?>");
var div_valor_orcado_edit<?php echo $var_modal_editar ?> = document.getElementById("div_valor_orcado_edit<?php echo $var_modal_editar ?>");
var edit_valor_orcado<?php echo $var_modal_editar ?> = document.getElementById("edit_valor_orcado<?php echo $var_modal_editar ?>");

function update<?php echo $var_modal_editar; ?>() {
    //alert('teste<?php echo $var_modal_editar ?>');

    
    var select<?php echo $var_modal_editar ?> = document.getElementById('grupo_orcado_edit<?php echo $var_modal_editar ?>');
    var option<?php echo $var_modal_editar ?> = select<?php echo $var_modal_editar ?>.options[select<?php echo $var_modal_editar ?>.selectedIndex];
    
    if (option<?php echo $var_modal_editar ?>.value == 'S' ) {
        div_grupo_edit<?php echo $var_modal_editar ?>.style.display = 'inline';
        div_valor_orcado_edit<?php echo $var_modal_editar ?>.style.display = 'none';
        edit_valor_orcado<?php echo $var_modal_editar ?>.required = false;
    } else {
        div_valor_orcado_edit<?php echo $var_modal_editar ?>.style.display = 'inline';
        div_grupo_edit<?php echo $var_modal_editar ?>.style.display = 'none';
        edit_valor_orcado<?php echo $var_modal_editar ?>.required = true;
    }

}

update<?php echo $var_modal_editar; ?>();

</script> 

<script> 

/////////////////////////////////////////
//AUTOCOMPLETE DESCRIÇÃO PLANO DE CONTA//
/////////////////////////////////////////

document.getElementById("jv_reduzido_edit<?php echo $var_modal_editar; ?>").onkeyup = function() {buscarDescricao<?php echo $var_modal_editar; ?>()};

function buscarDescricao<?php echo $var_modal_editar; ?>() {


    //alert('teste');

    var vl_reduzido = document.getElementById("jv_reduzido_edit<?php echo $var_modal_editar; ?>").value;

    $.ajax({
        type: 'GET',
        async: 'true',
        url: 'consultas/sql_ajax_plano_contas.php',
        data: {vl_reduzido:vl_reduzido},
        success: function(data){

            //debug result console
            console.log(data); 

            //set input value
            document.getElementById("jv_descricao_edit<?php echo $var_modal_editar; ?>").value = data;

        }

    });
    
}

</script> 


<script> 

    //////////////////
    //BUSCAR PERIODO//
    //////////////////
    document.getElementById("jv_periodo_edit<?php echo $var_modal_editar; ?>").onchange = function() {buscarGrupo<?php echo $var_modal_editar; ?>()};

    function buscarGrupo<?php echo $var_modal_editar; ?>() {

        //var vl_reduzido<?php echo $var_modal_editar; ?> = document.getElementById("jv_periodo_edit<?php echo $var_modal_editar; ?>").value;
        //var jv_periodo_edittt<?php echo $var_modal_editar; ?> = document.getElementById("jv_periodo_edit<?php echo $var_modal_editar; ?>").value;
        //alert(vl_reduzido<?php echo $var_modal_editar ?>);

        $('#lista_orcado<?php echo $var_modal_editar; ?>').load('consultas/sql_ajax_grupo_orcado.php?vl_periodo='+$('#jv_periodo_edit<?php echo $var_modal_editar; ?>').val()) 


    }


    buscarGrupo<?php echo $var_modal_editar; ?>(); 
    

</script>