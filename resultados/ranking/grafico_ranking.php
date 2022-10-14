<!-- RESULTADO DESVIO -->

<div style="height: 260px;">
   <canvas id="grafdesvio" width="400" height="400"></canvas>
</div>

<?php 

    //INICIANDO AS VARIAVEIS
    $var_setor = '[';
    $var_valor = '[';


    @oci_execute($result_resultado_ranking); 

?>
        
<?php while($row_ranking = @oci_fetch_array($result_resultado_ranking)){

    $var_setor .= "'" . $row_ranking['DS_SETOR'] . "',";
    $var_valor .= "'" .$row_ranking['VL_REALIZADO'] . "',";


} 

    //FINALIZANDO AS VARIAVEIS
    $var_setor .= ']';
    $var_valor .= ']';


?>

<script>
    
    new Chart(document.getElementById("grafdesvio"), {
        type: 'bar',
        data: {
        labels: <?php echo $var_setor; ?>,
        datasets: [
            {
            label: "Orçado",
            backgroundColor: "rgba(54, 162, 235, 0.5)",
            data: <?php echo $var_valor; ?>
            }

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
 