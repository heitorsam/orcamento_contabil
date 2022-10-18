<?php 
    

    //CABECALHO
    include 'cabecalho.php';

    //ACESSO RESTRITO
    include 'acesso_restrito.php';

    //ACESSO RESTRITO CONTABILIDADE
    include 'acesso_restrito_contabilidade.php';

    //CONEXAO
    include 'conexao.php';

    //RECEBENDO POST
    
    //RECEBENDO SESSAO
    //$var_cd_usuario = $_SESSION['usuarioLogin'];

    //SQL
    //include 'sql_listar_pendencias.php'; 
    
    $frm_cad_setor = @$_POST["frm_cad_setor"];
    $frm_cad_gerente = @$_POST["frm_cad_gerente"];
 
?>

    <!--TITULO-->
    <h11><i class="fa fa-line-chart"></i> Resultado Previsto</h11>
    <span class="espaco_pequeno" style="width: 6px;" ></span>
    <h27> <a href="home.php" style="color: #444444; text-decoration: none;"> <i class="fa fa-reply" aria-hidden="true"></i> Voltar </a> </h27> 
    
    <div class="div_br"> </div>       

    <!--MENSAGENS-->
    <?php
        include 'js/mensagens.php';
    ?>

    <div class="div_br"> </div>

    <form id="form_resul_prev" onsubmit="cad_vl_previsto();return false">

        <div class="row">
    
            <div class="col-3" style="text-align: left; background-color: #f9f9f9 !important;">         
                    
                Período: 
                <input type="month" class="form-control" id="frm_periodo" required>
                
            </div>

            <div class="col-3" style="text-align: left; background-color: #f9f9f9 !important;">         
                    
                Valor: 
                <input  type="number" step="any" class="form-control" id="frm_valor" required>
                
            </div>            

            <div class="col-2" style="text-align: left; background-color: #f9f9f9 !important;">

                </br>
                <button type="submit" class="btn btn-primary"> <i class="fas fa-plus"></i> </button>     

            </div>    

        </div>

    </form>
    
    <!--CAIXA PARA VALIDACAO DO AJAX -->
    <input id='msg' style='width: 100%' hidden>

     <!--DIV MENSAGEM ACOES-->
     <div id="mensagem_acoes"></div>

    <div class="div_br"> </div>  
    <div class="div_br"> </div>  

    <!--TABELA DE RESULTADOS -->
    <div class="table-responsive col-md-12">
        <table class="table table-fixed table-hover table-striped " cellspacing="0" cellpadding="0">

            <thead><tr>
                <!--COLUNAS-->
                <th class="align-middle" style="text-align: center !important;"><span>Período</span></th>
                <th class="align-middle" style="text-align: center !important;"><span>Resultado Previsto</span></th>
                <th class="align-middle" style="text-align: center !important;"><span>Opções</span></th>
                

            </tr></thead>            

            <tbody id="dados_tabela_result_prev">

            </tbody>

        </table>

    </div>
                        
<?php

    //JAVASCRIPT EDITAR CAMPOS
    include 'funcoes/js_editar_campos.php';

    //RODAPE
    include 'rodape.php';
?>


<script>

    $(document).ready(function() { 
        $('#dados_tabela_result_prev').load('funcoes/ajax_tabela_necessidade_prevista.php');
    });


    function cad_vl_previsto(){

        periodo = document.getElementById('frm_periodo').value;
        periodo = periodo.substring(5, 7) + '/' + periodo.substring(0, 4);
        vl_prev = document.getElementById('frm_valor').value;
      
        //alert(periodo);
        //alert(vl_prev);

        $.ajax({
            url: "funcoes/ajax_cad_result_prev.php",
            type: "POST",
            data: {
                var_periodo: periodo,
                var_prev: vl_prev
                },
            cache: false,
            success: function(dataResult){

                console.log(dataResult)

                if(dataResult == 'Sucesso'){

                    //MENSAGEM            
                    var_ds_msg = 'Resultado%20previsto%20cadastrado%20com%20sucesso!';
                    var_tp_msg = 'alert-success';
                    //var_tp_msg = 'alert-danger';
                    //var_tp_msg = 'alert-primary';    
                    $('#mensagem_acoes').load('funcoes/ajax_mensagem_acoes.php?ds_msg='+var_ds_msg+'&tp_msg='+var_tp_msg);                

                }else{

                    //MENSAGEM            
                    var_ds_msg = dataResult.replace(/\s+/g, '-');
                    var_tp_msg = 'alert-danger';
                    //var_tp_msg = 'alert-primary';
                    $('#mensagem_acoes').load('funcoes/ajax_mensagem_acoes.php?ds_msg='+var_ds_msg+'&tp_msg='+var_tp_msg); 

                }

                 //ATUALIZANDO TABELA DE RESULTADOS
                 $('#dados_tabela_result_prev').load('funcoes/ajax_tabela_necessidade_prevista.php');

            }

        }); 

    }

    function del_vl_previsto(var_cd_np){


        var result = confirm("Realmente deseja excluir?");
        if (result) {

            $.ajax({
                url: "funcoes/ajax_del_result_prev.php",
                type: "POST",
                data: {
                    cd_np : var_cd_np
                    },
                cache: false,
                success: function(dataResult){

                    console.log(dataResult)

                    if(dataResult == 'Sucesso'){

                        //MENSAGEM            
                        var_ds_msg = 'Resultado%20previsto%20excluído%20com%20sucesso!';
                        var_tp_msg = 'alert-success';
                        //var_tp_msg = 'alert-danger';
                        //var_tp_msg = 'alert-primary';    
                        $('#mensagem_acoes').load('funcoes/ajax_mensagem_acoes.php?ds_msg='+var_ds_msg+'&tp_msg='+var_tp_msg);                

                    }else{

                        //MENSAGEM            
                        var_ds_msg = dataResult.replace(/\s+/g, '-');
                        var_tp_msg = 'alert-danger';
                        //var_tp_msg = 'alert-primary';
                        $('#mensagem_acoes').load('funcoes/ajax_mensagem_acoes.php?ds_msg='+var_ds_msg+'&tp_msg='+var_tp_msg); 

                    }

                    //ATUALIZANDO TABELA DE RESULTADOS
                    $('#dados_tabela_result_prev').load('funcoes/ajax_tabela_necessidade_prevista.php');

                }

            }); 

        }

       
    }

</script>
