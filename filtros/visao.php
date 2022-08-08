Visão:
<select class="form-control"  name="filtro_visao" id="jv_filtro_visao" required>
            
    <?php 

        $var_visao = @$_POST["filtro_visao"];  

        if(strlen($var_visao) < 1){ 

            echo "<option value=''> Selecione </option>";

        }else{

            echo "<option value='" . $var_visao . "'>" . $var_visao . "</option>";
        }

    ?>

    <?php if($var_visao <> 'Receita') { echo "<option value='Receita'> Receita </option>"; } ?>
    <?php if($var_visao <> 'Despesa') { echo "<option value='Despesa'> Despesa </option>"; } ?>
    <?php if($var_visao <> 'Resultado') { echo "<option value='Resultado'> Resultado </option>"; } ?>

</select>