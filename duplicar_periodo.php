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
    <h11><i class="fas fa-copy"></i> Duplicar Período</h11>
    <span class="espaco_pequeno" style="width: 6px;" ></span>
    <h27> <a href="home.php" style="color: #444444; text-decoration: none;"> <i class="fa fa-reply" aria-hidden="true"></i> Voltar </a> </h27> 
    
    <div class="div_br"> </div>       

    <!--MENSAGENS-->
    <?php
        include 'js/mensagens.php';
    ?>

    <div class="div_br"> </div>

    <p style="color: red;"> <b> Atenção! Ao realizar essa ação o mês selecionado como Novo Período irá sobrescrever as regras atuais caso exista! </b> </p>

    <form action="consultas/proc_duplica_regra_periodo.php" method="POST">

        <div class="row">

            <div class="col-4" style="text-align: left; background-color: #f9f9f9 !important;">

                Período Referência:
                <input type="month" class="form-control" name="frm_mes_ref" placeholder="Digite o período" required>
            
            </div>

            <div class="col-4" style="text-align: left; background-color: #f9f9f9 !important;">

                Novo Período:
                <input type="month" class="form-control" name="frm_mes_new" placeholder="Digite o período" required>
            
            </div>
            
        </div>

        <button type="submit"  onclick="return confirm('Tem certeza que deseja realizar essa ação?');" class="btn btn-primary"><i class="fas fa-copy"></i> Duplicar</button>

    </form>

<?php

    //RODAPE
    include 'rodape.php';

?>