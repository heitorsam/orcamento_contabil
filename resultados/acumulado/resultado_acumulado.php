<!-- RESULTADO ACUMULADO -->

<div class="table-responsive col-md-12" style="text-align: center;">
    <table class="table" style="padding: 0px !important; border-spacing: 0px !important;" cellspacing="0" cellpadding="0"> 

        <td style="margin: 0px !important; padding: 0px !important;">                
            <div class="pivot_tr_titulo"> RECEITA </div>          
            <div class="pivot_tr_conteudo">ORÇADO</div>
            <div class="pivot_tr_conteudo">REALIZADO</div>

            <div> <br> </div>

            <div class="pivot_tr_titulo">DESPESA</div>            
            <div class="pivot_tr_conteudo">ORÇADO</div>
            <div class="pivot_tr_conteudo">REALIZADO</div>

            <div> <br> </div>

            <div class="pivot_tr_titulo">RESULTADO</div>            
            <div class="pivot_tr_conteudo">ORÇADO</div>
            <div class="pivot_tr_conteudo">REALIZADO</div>
        
        </td>

        <?php 

            @oci_execute($result_resultado_acumulado); 
            $soma_orcado_receita = 0;
            $soma_realizado_receita = 0;
            $soma_orcado_despesa = 0;
            $soma_realizado_despesa = 0;
            $soma_orcado_resultado = 0;
            $soma_realizado_resultado = 0;

            $cor_texto = 'green';
        
        ?>
        
        <?php while($row_acumulado = @oci_fetch_array($result_resultado_acumulado)){ ?> 

            <td style="margin: 0px !important; padding: 0px !important;">  

                <?php

                    if(@intval($row_acumulado['VL_REALIZADO_RECEITA'] + $soma_realizado_receita) > @intval($row_acumulado['VL_ORCADO_RECEITA'] + $soma_orcado_receita)){

                        $cor_texto = 'green';

                    }else{

                        $cor_texto = 'red';

                    }

                ?>

                <div class="pivot_tr_titulo"> <?php echo $row_acumulado['MES_ABV']; ?> </div> 
                <div class="pivot_tr_conteudo"> <?php echo @number_format($row_acumulado['VL_ORCADO_RECEITA'] + $soma_orcado_receita, 2, ',', '.' ); ?> </div>
                <div class="pivot_tr_conteudo" style="color: <?php echo $cor_texto; ?>"> <?php echo @number_format($row_acumulado['VL_REALIZADO_RECEITA'] + $soma_realizado_receita, 2, ',', '.' ); ?> </div> 
            
                <div> <br> </div>

                <?php

                    if(@intval($row_acumulado['VL_REALIZADO_DESPESA'] + $soma_realizado_despesa) < @intval($row_acumulado['VL_ORCADO_DESPESA'] + $soma_orcado_despesa)){

                        $cor_texto = 'green';

                    }else{

                        $cor_texto = 'red';

                    }

                ?>

                <div class="pivot_tr_titulo"> <?php echo $row_acumulado['MES_ABV']; ?> </div> 
                <div class="pivot_tr_conteudo"> <?php echo @number_format($row_acumulado['VL_ORCADO_DESPESA'] + $soma_orcado_despesa, 2, ',', '.' ); ?> </div>
                <div class="pivot_tr_conteudo" style="color: <?php echo $cor_texto; ?>"> <?php echo @number_format($row_acumulado['VL_REALIZADO_DESPESA'] + $soma_realizado_despesa, 2, ',', '.' ); ?> </div> 

                <div> <br> </div>

                <?php

                    if(@intval($row_acumulado['VL_REALIZADO_RESULTADO'] + $soma_realizado_despesa) > @intval($row_acumulado['VL_ORCADO_DESPESA'] + $soma_orcado_despesa)){

                        $cor_texto = 'green';

                    }else{

                        $cor_texto = 'red';

                    }

                ?>

                <div class="pivot_tr_titulo"> <?php echo $row_acumulado['MES_ABV']; ?> </div> 
                <div class="pivot_tr_conteudo"><?php echo @number_format($row_acumulado['VL_ORCADO_RESULTADO'] + $soma_orcado_resultado, 2, ',', '.' ); ?> </div>
                <div class="pivot_tr_conteudo" style="color: <?php echo $cor_texto; ?>"> <?php echo @number_format($row_acumulado['VL_REALIZADO_RESULTADO'] + $soma_realizado_resultado, 2, ',', '.' ); ?> </div> 
                        
            </td>
        
        <?php 

                @$soma_orcado_receita += @$row_acumulado['VL_ORCADO_RECEITA'];
                @$soma_realizado_receita += @$row_acumulado['VL_REALIZADO_RECEITA'];
                @$soma_orcado_despesa += @$row_acumulado['VL_ORCADO_DESPESA'];
                @$soma_realizado_despesa += @$row_acumulado['VL_REALIZADO_DESPESA'];
                @$soma_orcado_resultado += @$row_acumulado['VL_ORCADO_RESULTADO'];
                @$soma_realizado_resultado += @$row_acumulado['VL_REALIZADO_RESULTADO'];
        
            }

        ?>            

    </table>

</div>