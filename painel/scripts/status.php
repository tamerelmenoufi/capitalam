<?php

    include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");

    $query = "SELECT * FROM `consultas_log` where ativo = '1'";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
        set_time_limit(90);

        echo $q = "update clientes set status_atual = '{$d->log}' where codigo = '{$d->cliente}'";
        echo "<hr>";


    }