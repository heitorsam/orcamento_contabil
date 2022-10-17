<?php

    $var_msg = str_replace('%20',' ',$_GET['ds_msg']);
    $var_tp = $_GET['tp_msg'];

    echo "<div class='div_br'></div>";
    echo "<div class='alert " . $var_tp . "' role='alert'>";   
    echo $var_msg;                          
    echo "</div>";

?>

<script>

    $(".alert").delay(6000).fadeOut(1200);

</script>