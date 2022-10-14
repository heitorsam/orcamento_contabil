Indicador: </br>   

<select class="form-control" id="indicador_filtro" onchange="filtroIndicador()" name="filtro_indicador">

    <?php 

        if(strlen($var_indicador) < 1){ 

            echo "<option value=''> Selecione </option>";

        }else{

            echo "<option value='" . $var_indicador . "'>" . $var_indicador . "</option>";
        }

    ?>

    <?php if($var_indicador <> 'Desvio') { echo "<option value='Desvio'> Desvio </option>"; } ?>
    <?php if($var_indicador <> 'Gerencia') { echo "<option value='Gerencia'> Gerencia </option>"; } ?>
    <?php if($var_indicador <> 'Acumulado') { echo "<option value='Acumulado'> Acumulado </option>"; } ?>
    <?php if($var_indicador <> 'Ranking') { echo "<option value='Ranking'> Ranking </option>"; } ?>

</select>    