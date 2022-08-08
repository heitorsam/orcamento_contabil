<?php 
       
        $var_periodo_filtro = @$_POST["frm_cad_periodo"];

            if(strlen($var_periodo_filtro) < 1){ 
                
                echo "Período:";
                echo "<input type='month' class='form-control' name='frm_cad_periodo' id='jv_filtro_periodo' required>";
            }else{
                echo "Período:";
                echo "<input type='month' class='form-control' name='frm_cad_periodo' id='jv_filtro_periodo' value='". $var_periodo_filtro."' required >";
            }
        
    ?>