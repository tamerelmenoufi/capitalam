<?php
    session_start();

    if($_SERVER["HTTP_HOST"] == 'capital.mohatron.com'){
        header("location:http://painel.capitalsolucoesam.com.br");
        exit();
    }

    // include("connect_local.php");

    include("/appinc/connect.php");
    $con = AppConnect('capital');

    // $ConfWappNumero = '5592981490062';
    $ConfWappNumero = '559231901244';
    
    //Configurações diversas
    include("/appinc/config.php");

    // include("/appinc/connect.php");
    include("fn.php");

    include("{$_SERVER['DOCUMENT_ROOT']}/capitalam/painel/lib/vendor/api/vctex.php");
    include("{$_SERVER['DOCUMENT_ROOT']}/capitalam/painel/lib/vendor/api/facta.php");

    //Bibliotecas de comunicação
    include("{$_SERVER['DOCUMENT_ROOT']}/capitalam/painel/lib/vendor/wapp/wgw/classes.php");

    $md5 = md5(date("YmdHis"));

    // $localPainel = $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"]."/painel/";
    // $localSite = $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"]."/site/";

    // $localPainel = "http://capital.mohatron.com/painel/";
    // $localSite = "http://capital.mohatron.com/site/";

    $localPainel = "http://146.190.52.49:8081/capitalam/painel/";
    $localSite = "http://146.190.52.49:8081/capitalam/site/";