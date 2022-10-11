<?php     

    //CABECALHO
    include 'cabecalho.php';

    //CONEXAO
    include 'conexao.php';

    //RECEBENDO POST
    $var_indicador = @$_POST["filtro_indicador"];  
    $var_ano = @$_POST["filtro_ano"];  
  
    //echo $var_periodo; echo '</br>';
    //echo $_SESSION['periodo']; echo '</br>';

    if(isset($_POST["frm_cad_periodo"])){

        @$var_periodo = @$_SESSION['periodo'];
        @$var_periodo_filtro = @substr($var_periodo,3,4) . '-' . @substr($var_periodo,0,2);
    
    }else{
        date_default_timezone_set('America/Sao_Paulo');
        $date = date('Y-m' , time());
        @$var_periodo = $date;
        $var_periodo_filtro = $date;
        
    }
    
    //RECEBENDO SESSAO
    @$var_cd_usuario = @$_SESSION['usuarioLogin'];

    $reduzido = @$_POST['jv_reduzido'];

?>

    <!--TITULO-->
    <h11><i class="fa fa-bar-chart"></i> Resultados</h11>
   
    
    <div class="div_br"> </div>       

    <!--MENSAGENS-->
    <?php
        include 'js/mensagens.php';
    ?>

    <form action="resultados_usu.php" method="POST">

        <!--FILTROS-->
        <div class="row">

            
            
            <div class="col-2" id="div_filtro_setor" style="text-align: left; background-color: #f9f9f9 !important;">
                <?php include 'filtros/setor.php'; ?>
            </div>

            
            <div class="col-3" id="div_filtro_periodo" style="text-align: left; background-color: #f9f9f9 !important; display: none;">
                <?php if(strlen($var_periodo_filtro) < 1){ 
                
                        echo "Período:";
                        echo "<input type='month' class='form-control' name='frm_cad_periodo' id='jv_filtro_periodo'  required>";
                    }else{
                        
                        echo "Período:";
                        echo "<input type='month' class='form-control' name='frm_cad_periodo' id='jv_filtro_periodo' value='". $var_periodo_filtro."' required >";
                    } ?>  
                    </div> 
            
            <div class="col-2" id="div_reduzido" style="text-align: left; background-color: #f9f9f9 !important; display: none;">
          
                Reduzido:
                <input type="number" name="jv_reduzido" id="jv_reduzido" value="<?php echo $reduzido ?>" class="form-control">
            </div>
            


            <div class="col-3" id="div_filtro_button" style="text-align: left; background-color: #f9f9f9 !important; ">
                 <br>
                <!-- BOTAO PESQUISAR -->
                <button type="submit" class="btn btn-primary" >
                    <i class="fas fa-search"></i> Pesquisar
                </button>           
            </div>  

        </div>

    </form>

    <!-------------->
    <!-- GERENCIA -->
    <!-------------->
    
    <?php 
            
        include 'resultados_usu/estrutura_gerencia_usu.php';
        
    ?>


<script>


    var var_div_filtro_periodo = document.getElementById("div_filtro_periodo");
    var var_div_filtro_setor = document.getElementById("div_filtro_setor");
    var var_div_filtro_button = document.getElementById("div_filtro_button"); 
    var var_div_filtro_reduzido = document.getElementById("div_reduzido"); 

    var var_jv_filtro_setor = document.getElementById("jv_filtro_setor");
    var var_jv_filtro_periodo = document.getElementById("jv_filtro_periodo");
    var var_jv_filtro_reduzido = document.getElementById("jv_reduzido");   

    $(document).ready(function() {
        console.log('sai do console curioso')

        var_div_filtro_button.style.display = 'inline';
        var_div_filtro_setor.style.display = 'inline';
        var_div_filtro_periodo.style.display = 'inline';
        var_div_filtro_reduzido.style.display = 'inline';

        var_jv_filtro_setor.value = 'Todos'

        var_jv_filtro_setor.required = true;
        var_jv_filtro_periodo.required = true;
    });

</script>

<?php
    //RODAPE
    include 'rodape.php';
?>
