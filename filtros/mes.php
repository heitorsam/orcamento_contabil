 Mês:
<select class="form-control" name="filtro_mes" id="jv_filtro_mes" required> 

    <?php 
       
            $var_mes = @$_POST["filtro_mes"];  

            if(strlen($var_mes) < 1){ 

                echo "<option value=''> Selecione </option>";

            }else{

                echo "<option value='" . $var_mes . "'>" . $var_mes . "</option>";
            }
        
    ?>

    <?php  if($var_mes <> 'Janeiro') { echo "<option value='Janeiro'>Janeiro         </option>"; } ?>
    <?php  if($var_mes <> 'Fevereiro') { echo "<option value='Fevereiro'>Fevereiro   </option>"; } ?>
    <?php  if($var_mes <> 'Março') { echo "<option value='Março'>Março               </option>"; } ?>
    <?php  if($var_mes <> 'Abril') { echo "<option value='Abril'>Abril               </option>"; } ?>
    <?php  if($var_mes <> 'Maio') { echo "<option value='Maio'>Maio                  </option>"; } ?>
    <?php  if($var_mes <> 'Junho') { echo "<option value='Junho'>Junho               </option>"; } ?>
    <?php  if($var_mes <> 'Julho') { echo "<option value='Julho'>Julho               </option>"; } ?>
    <?php  if($var_mes <> 'Agosto') { echo "<option value='Agosto'>Agosto            </option>"; } ?>
    <?php  if($var_mes <> 'Setembro') { echo "<option value='Setembro'>Setembro      </option>"; } ?>
    <?php  if($var_mes <> 'Outubro') { echo "<option value='Outubro'>Outubro         </option>"; } ?>
    <?php  if($var_mes <> 'Novembro') { echo "<option value='Novembro'>Novembro      </option>"; } ?>
    <?php  if($var_mes <> 'Dezembro') { echo "<option value='Dezembro'>Dezembro      </option>"; } ?>
     
</select>         

