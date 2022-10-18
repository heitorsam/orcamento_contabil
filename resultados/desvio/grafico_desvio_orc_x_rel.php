<!-- RESULTADO DESVIO -->

<div style="height: 260px;">
   <canvas id="grafdesvio" width="400" height="400"></canvas>
</div>

<?php 

    //INICIANDO AS VARIAVEIS
    $var_mes = '[';
    $var_orcado = '[';
    $var_realizado = '[';
<<<<<<< HEAD

    if($var_indicador == 'Desvio' && $var_setor == 'Todos' && $var_visao == 'Resultado'){

        $var_previsto = '[';

    }
=======
>>>>>>> parent of da73053 (Merge branch 'main' of https://github.com/heitorsam/orcamento_contabil)

    @oci_execute($result_resultado_desvio); 

?>
        
<?php while($row_desvio = @oci_fetch_array($result_resultado_desvio)){

    $var_mes .= "'" . $row_desvio['MES_ABV'] . "',";
    $var_orcado .= "'" .$row_desvio['VL_ORCADO_ROUND'] . "',";
    $var_realizado .= "'" . $row_desvio['VL_REALIZADO_ROUND'] . "',";
<<<<<<< HEAD

    if($var_indicador == 'Desvio' && $var_setor == 'Todos' && $var_visao == 'Resultado'){

        $var_previsto .= "'" . $row_desvio['VL_NECESSIDADE_PREVISTA_ROUND'] . "',";

    }
=======
>>>>>>> parent of da73053 (Merge branch 'main' of https://github.com/heitorsam/orcamento_contabil)

} 

    //FINALIZANDO AS VARIAVEIS
    $var_mes .= ']';
    $var_orcado .= ']';
    $var_realizado .= ']';
<<<<<<< HEAD

    if($var_indicador == 'Desvio' && $var_setor == 'Todos' && $var_visao == 'Resultado'){

        $var_previsto .= ']';

    }
=======
>>>>>>> parent of da73053 (Merge branch 'main' of https://github.com/heitorsam/orcamento_contabil)

?>

<script>
    
    new Chart(document.getElementById("grafdesvio"), {
        type: 'bar',
        data: {
        labels: <?php echo $var_mes; ?>,
        datasets: [
            {
            label: "Orçado",
            backgroundColor: "rgba(54, 162, 235, 0.5)",
            data: <?php echo $var_orcado; ?>
            }, {
            label: "Realizado",
            backgroundColor: "rgba(255, 99, 132, 0.5)",
            data: <?php echo $var_realizado; ?>
<<<<<<< HEAD
            }
            
            <?php 

                if($var_indicador == 'Desvio' && $var_setor == 'Todos' && $var_visao == 'Resultado'){

            ?> 

            , {
            label: "Previsto",
            backgroundColor: "rgba(87, 179, 171, 0.5)",
            data: <?php echo $var_previsto; ?>
            }

            <?php

                }

            ?>


            
=======
            }
>>>>>>> parent of da73053 (Merge branch 'main' of https://github.com/heitorsam/orcamento_contabil)
        ]
        },
        options: {
            maintainAspectRatio: false,
        title: {
            display: true
        }
        }
    });

</script>
 