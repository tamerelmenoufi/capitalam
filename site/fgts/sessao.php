<?php

    include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");

    $_SESSION['codUsr'] = $_POST['codUsr'];
    echo $_SESSION['codUsr'];