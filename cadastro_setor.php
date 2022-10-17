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
    <h11><i class="fa fa-building"></i> Cadastro Setor</h11>
    <span class="espaco_pequeno" style="width: 6px;" ></span>
    <h27> <a href="home.php" style="color: #444444; text-decoration: none;"> <i class="fa fa-reply" aria-hidden="true"></i> Voltar </a> </h27> 
    
    <div class="div_br"> </div>       

    <!--MENSAGENS-->
    <?php
        include 'js/mensagens.php';
    ?>

    <div class="div_br"> </div>

    <!--MODEL CADASTRAR-->
    <?php
        include 'modals/setor/modal_cadastro_setor.php';
    ?>        

    <div class="div_br"> </div>  
    <div class="div_br"> </div>  

    <?php 
        /////////////////////
        ////LISTAR SETOR/////
        /////////////////////
    
        
        $lista_setor = "SELECT setor.CD_SETOR, setor.DS_SETOR, setor.NM_GESTOR, setor.SN_ATIVO, setor.CD_USUARIO 
                        FROM orcamento_contabil.SETOR setor
                        ORDER BY setor.HR_CADASTRO DESC";

        $result_setor = oci_parse($conn_ora, $lista_setor);

        @oci_execute($result_setor);

        include 'modals/setor/modal_usuarios_setor.php';
    ?>


    <!--TABELA DE RESULTADOS -->
    <div class="table-responsive col-md-12">
    <table class="table table-fixed table-hover table-striped " cellspacing="0" cellpadding="0">

        <thead><tr>
            <!--COLUNAS-->
            <th class="align-middle" style="text-align: center !important;"><span>Nome</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>Gerente</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>Ativo</span></th>
            <th class="align-middle" style="text-align: center !important;"><span>Usuários</span></th>

        </tr></thead>            

        <tbody>
            <?php
                while($row_setor = @oci_fetch_array($result_setor)){  ?>  
                    
                <tr>
                    
                    <td id="DS_SETOR<?php echo @$row_setor['CD_SETOR']; ?>"
                    ondblclick="fnc_editar_campo('orcamento_contabil.SETOR', 'DS_SETOR', '<?php echo @$row_setor['DS_SETOR']; ?>', 'CD_SETOR', '<?php echo @$row_setor['CD_SETOR']; ?>', '')"
                    class='align-middle' style='text-align: center;'><?php echo @$row_setor['DS_SETOR']; ?></td>

                    <td id="NM_GESTOR<?php echo @$row_setor['CD_SETOR']; ?>"
                    ondblclick="fnc_editar_campo('orcamento_contabil.SETOR', 'NM_GESTOR', '<?php echo @$row_setor['NM_GESTOR']; ?>', 'CD_SETOR', '<?php echo @$row_setor['CD_SETOR']; ?>', 'SELECT NM_GESTOR AS COLUNA_DS FROM orcamento_contabil.SETOR_TESTE')"
                    class='align-middle' style='text-align: center;'><?php echo @$row_setor['NM_GESTOR']; ?></td>
                    
                    <?php
                    if ($row_setor['SN_ATIVO'] == 'S') { 
                                echo "<td class='text-center align-middle'>" .
                                "<a class='botoes' style='color: #3c3c3c;' href='consultas/proc_alterar_sn_ativo.php?id=" . 
                                $row_setor['CD_SETOR']  . "&sn_ativo=N&tabela=SETOR&pagina=cadastro_setor" .
                                "' onclick=\"return confirm('Tem certeza que deseja desativar esse registro?');\">" . 
                                "<i class='fa fa-toggle-on' aria-hidden='true'></i>" . "</a>" . "</td>";
                            } 
                            else {
                                echo "<td class='text-center align-middle'>" . 
                                "<a class='botoes' style='color: #3c3c3c;' href='consultas/proc_alterar_sn_ativo.php?id=" .
                                $row_setor['CD_SETOR']  . "&sn_ativo=S&tabela=SETOR&pagina=cadastro_setor" . 
                                "' onclick=\"return confirm('Tem certeza que deseja ativar esse registro?');\">" . 
                                "<i class='fa fa-toggle-off' aria-hidden='true'></i>" . "</a>" . "</td>"; 
                            };?>
                    <td class='align-middle' style='text-align: center;'>
                        <a onclick="ajax_modal_usuarios('<?php echo $row_setor['CD_SETOR'] ?>')" class="btn btn-primary" data-toggle="modal" data-target="#cad_usuarios"><i class="fas fa-users"></i></a>
                    </td>

                </tr>
                <?php 

                } ?>
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

    function ajax_modal_usuarios(cd_setor){
        $('#div_usuarios').load('funcoes/setor/ajax_tabela_usuarios.php?cd_setor='+cd_setor)
        $('#div_input').load('funcoes/setor/ajax_input_usuarios.php?cd_setor='+cd_setor)
    }

    function ajax_cad_usuario(cd_setor){
        usuario = document.getElementById('inpt_usuario')
        if(usuario.value == ''){
            usuario.focus()
        }else{
            $.ajax({
                url: "funcoes/setor/ajax_cad_usuario.php",
                type: "POST",
                data: {
                    cd_setor: cd_setor,
                    cd_usuario: usuario.value		
                    },
                cache: false,
                success: function(dataResult){                    
                    usuario.value = ''
                    $('#div_usuarios').load('funcoes/setor/ajax_tabela_usuarios.php?cd_setor='+cd_setor)    

                }
            });
        }
    }

    function ajax_apagar_usuario(cd_usuario, cd_setor){
        $.ajax({
            url: "funcoes/setor/ajax_apagar_usuario.php",
            type: "POST",
            data: {
                cd_setor: cd_setor,
                cd_usuario: cd_usuario		
                },
            cache: false,
            success: function(dataResult){                    

                $('#div_usuarios').load('funcoes/setor/ajax_tabela_usuarios.php?cd_setor='+cd_setor)    

            }
        });
    }

</script>