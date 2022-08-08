<!-- RESULTADO DESVIO -->

<div class="table-responsive col-md-12" style="text-align: center;">
    <table class="table" style="padding: 0px !important; border-spacing: 0px !important;" cellspacing="0" cellpadding="0"> 

        <td style="width: margin: 0px !important; padding: 0px !important;">                
            <div class="pivot_tr_titulo"> <?php echo @strtoupper($var_ano); ?> </div> 
            <div class="pivot_tr_conteudo"><?php echo @strtoupper($var_visao); ?> ORÇAD<?php if($var_visao == 'Resultado') {
                                                                                                echo 'O';
                                                                                            }else{
                                                                                                echo 'A';
                                                                                            }
                                                                                        ?>
            </div>
            <div class="pivot_tr_conteudo"><?php echo @strtoupper($var_visao); ?> REALIZAD<?php if($var_visao == 'Resultado') {
                                                                                                    echo 'O';
                                                                                                }else{
                                                                                                    echo 'A';
                                                                                                }
                                                                                            ?>
            </div>
            <div class="pivot_tr_conteudo">% DESVIO</div>
            <div class="pivot_tr_conteudo">% DESVIO ACUMULADO</div>         
        </td>

        <?php 

            @oci_execute($result_resultado_desvio); 
            $soma_orcado = 0;
            $soma_realizado = 0;
        
        ?>
        
        <?php while($row_desvio = @oci_fetch_array($result_resultado_desvio)){ ?> 

            <td style="width: margin: 0px !important; padding: 0px !important;">  

                <div class="pivot_tr_titulo"> <?php echo $row_desvio['MES_ABV']; ?> </div> 
                <div class="pivot_tr_conteudo"> <?php echo @number_format($row_desvio['VL_ORCADO'], 2, ',', '.' ); ?> </div>
                <div class="pivot_tr_conteudo"> <?php echo @number_format($row_desvio['VL_REALIZADO'], 2, ',', '.' ); ?> </div> 

                <!-- COR VARIAÇÃO -->

                <?php 

                    $var_cor_variacao = 'red';
                    
                    if($var_visao == 'Despesa' AND @((($row_desvio['VL_REALIZADO']-$row_desvio['VL_ORCADO'])/$row_desvio['VL_ORCADO'])*100) < '0,5'){
                            
                        $var_cor_variacao = 'green';
                    }    
                    
                    if($var_visao <> 'Despesa' AND @((($row_desvio['VL_REALIZADO']-$row_desvio['VL_ORCADO'])/$row_desvio['VL_ORCADO'])*100) > '0,5'){
                            
                        $var_cor_variacao = 'green';
                    }   

                ?>

                <?php 

                    $porc_desvio = ROUND((($row_desvio['VL_REALIZADO_ROUND'] - $row_desvio['VL_ORCADO_ROUND']) / $row_desvio['VL_ORCADO_ROUND']) * 100,2);

                ?>

                <div class="pivot_tr_conteudo" style="color: <?php echo $var_cor_variacao; ?>"> <?php echo $porc_desvio . '%'; ?> </div>     

                <?php 

                    @$soma_orcado += @$row_desvio['VL_ORCADO'];
                    @$soma_realizado += @$row_desvio['VL_REALIZADO'];


                    $var_cor_variacao_acumulado = 'red';

                    if($var_visao == 'Despesa' AND ((($soma_realizado-$soma_orcado)/$soma_orcado)*100) < '0,5') {
                            
                        $var_cor_variacao_acumulado = 'green';
                    }    
                    
                    if($var_visao <> 'Despesa' AND ((($soma_realizado-$soma_orcado)/$soma_orcado)*100) > '0,5'){
                            
                        $var_cor_variacao_acumulado = 'green';
                    }   


                    $porc_desvio_acumulado = ROUND((($soma_realizado - $soma_orcado) / $soma_orcado) * 100,2);

                ?>

                <div class="pivot_tr_conteudo" style="color: <?php echo $var_cor_variacao_acumulado; ?>"> <?php echo @number_format($porc_desvio_acumulado, 2, ',', '.' ) . '%'; ?> </div>                  

            </td>

        <?php   

                $var_cor_variacao_total = 'red';

                if($var_visao == 'Despesa' AND ((($soma_realizado-$soma_orcado)/$soma_orcado)*100) < '0,5') {
                        
                    $var_cor_variacao_total = 'green';
                }    
                
                if($var_visao <> 'Despesa' AND ((($soma_realizado-$soma_orcado)/$soma_orcado)*100) > '0,5'){
                        
                    $var_cor_variacao_total = 'green';
                }   

            }
    
        ?>

        <td style="width: margin: 0px !important; padding: 0px !important;">  

            <div class="pivot_tr_titulo"> Total </div> 
            <div class="pivot_tr_conteudo"> <?php echo @number_format($soma_orcado, 2, ',', '.' ); ?> </div>
            <div class="pivot_tr_conteudo" style="color: <?php echo $var_cor_variacao_total; ?>"> <?php echo @number_format($soma_realizado, 2, ',', '.' ); ?> </div>

        </td>
        
    </table>

</div>