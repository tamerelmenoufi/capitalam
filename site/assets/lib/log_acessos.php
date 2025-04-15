<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/site/assets/lib/includes.php");

    // $dados = str_replace(['\n','\"','\/'],[' ','\\"','\\/'],json_encode($_SERVER));
    $dados = addslashes(json_encode($_SERVER));

    $query = "insert into log_acessos set data = NOW(), dados = '{$dados}'";
    mysqli_query($con, $query);

?>