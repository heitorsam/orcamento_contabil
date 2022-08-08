<?php 
    //////////////////////////////////////////
    //POPULANDO A LISTA SETOR / GRUPO ORÇADO//
    //////////////////////////////////////////
    

    //RECEBENDO POST
    @$var_frm_cd_grupo_orcado = $_POST['frm_cd_grupo_orcado'];
    @$var_frm_cad_valor_orcado = $_POST['frm_cad_valor_orcado'];

    //SQL
    include 'consultas/conta_contabil/sql_listar_setor.php';  
    //include 'consultas/conta_contabil/sql_listar_grupo_orcado_cadastro.php';  
?>

<!-- Botão Modal Cadastro Conta Contabil -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cad_pendencia">
    <i class="fas fa-plus"></i> Cadastrar 
</button>

<!-- Modal Cadastro Conta Contabil -->
<div class="modal fade" id="cad_pendencia" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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

                    <div class="row">    
                        <div class="col-md-4"> 
                            <label>Ordem:</label>
                            <input type="number" class="form-control" name="frm_cad_ordem" placeholder="10, 20, 30... " required>
                        </div>

                        <!-- Select - SETOR -->
                        <div class="col-md-4"> 
                            <label>Setor:</label>
                                <select id="id_setor" name="frm_cd_setor" class="form-control" required>
                                    <?php
                                        while($row_dados_setor = oci_fetch_array($result_dados_setor)){	

                                            echo "<option value='" . $row_dados_setor['CD_SETOR'] . "'>" . $row_dados_setor['DS_SETOR'] . "</option>";
                                        }
                                    ?>
                                </select>
                        </div>
                        
                        <!-- Text - PERÍODO -->
                        <div class="col-md-4"> 
                            <label>Período:</label>
                            <input type="month" class="form-control" name="frm_cad_periodo" id="jv_periodo" placeholder="Digite o período" required>
                        </div>
                    </div> 

                    <div class="div_br"> </div>
    

                    <div class="row"> 
                        
                    
                        <!-- Text - CÓDIGO -->
                        <div class="col-md-2"> 
                            <label>Código:</label>
                            <input type="text" class="form-control" name="frm_cad_codigo_mv" id="jv_reduzido" required>
                        </div> 

                        <!-- Text - DESCRIÇÃO -->
                        <div class="col-md-10"> 
                            <label>Descrição:</label>
                            <input type="text" class="form-control" name="frm_cad_descricao_mv"  id="jv_descricao" readonly>
                        </div>
                       
 
                    </div> 

                    <div class="div_br"> </div>
                   

                    <div class="row">  
                        
                        <!-- Select S/N - GRUPO ORÇADO -->
                        <div class="col-md-4"> 
                            <label>Grupo Orçado:</label>
                            <select class="custom-select" name="frm_grupo_orcado_sn" id="grupo_orcado" onChange="update()" required>
                                <option value="S">Sim</option>
                                <option value="N">Não</option>
                            </select>
                        </div>

                        <!-- GRUPO ORÇADO -->
                        <div class="col-md-8" style="display:none;" id="div_grupo">                             
                            <div id="lista_orcado"></div>                               
                        </div>

                        <!-- Valor Orçado -->
                        <div class="col-md-4" style="display:none;" id="div_valor_orcado"> 
                            <label>Valor Orçado:</label>
                            <input type="number" step="any"  class="form-control" name="frm_cad_valor_orcado" id="cad_valor_orcado" placeholder="Digite o Valor Orçado" >
                        </div>


                    </div> 

                    
                    <div class="div_br"> </div>
                   
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> <i class="fas fa-times"></i> Fechar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

 <!-- SCRIPT (SELECT S/N - GRUPO ORÇADO) -->
<script type="text/javascript">

    var cad_valor_orcado = document.getElementById("cad_valor_orcado");
    
    var div_grupo = document.getElementById("div_grupo");
    var div_valor_orcado = document.getElementById("div_valor_orcado");

    function update() {

        var select = document.getElementById('grupo_orcado');
        var option = select.options[select.selectedIndex];
        
        if (option.value == 'S' ) {
            div_grupo.style.display = 'inline';
            div_valor_orcado.style.display = 'none';
            cad_valor_orcado.required = false;
        } else {
            div_valor_orcado.style.display = 'inline';
            div_grupo.style.display = 'none';
            cad_valor_orcado.required = true;
        }

    }

    update();

</script> 

<script> 

/////////////////////////////////////////
//AUTOCOMPLETE DESCRIÇÃO PLANO DE CONTA//
/////////////////////////////////////////

const myInput = document.querySelector('input'),
	mySpan = document.querySelector('span');
let counter = 0;

function debounce(func, wait) {
	let timer = null;
	return function() {
		clearTimeout(timer);
		timer = setTimeout(func, wait);
	}
}


document.getElementById("jv_reduzido").onkeyup = debounce(function() {buscarDescricao()}, 2000);

function buscarDescricao() {

    //alert('teste');

    var vl_reduzido = document.getElementById("jv_reduzido").value;

    $.ajax({
        type: 'GET',
        async: 'true',
        url: 'consultas/sql_ajax_plano_contas.php',
        data: {vl_reduzido:vl_reduzido},
        success: function(data){

            //debug result console
            console.log(data); 

            //set input value
            document.getElementById("jv_descricao").value = data;

        }

    });
    
}

</script> 


<!-- -->
<script> 

//////////////////
//BUSCAR PERIODO//
//////////////////
document.getElementById("jv_periodo").onchange = function() {buscarGrupo()};

function buscarGrupo() {

    //alert('teste');

    $('#lista_orcado').load('consultas/sql_ajax_grupo_orcado.php?vl_periodo='+$('#jv_periodo').val()) 

}

</script> 

