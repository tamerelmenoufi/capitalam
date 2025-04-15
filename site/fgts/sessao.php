<?php

    include("{$_SERVER['DOCUMENT_ROOT']}/capitalam/painel/lib/includes.php");

    $_SESSION['codUsr'] = $_POST['codUsr'];
    echo $_SESSION['codUsr'];