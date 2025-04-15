<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");

    if($_GET['s']){
        $_SESSION = [];
        header("location:./");
        exit();
    }

    $app = (($_GET['app'])?:'financeira');

    if($_SESSION['ProjectPainel']->perfil == 'adm'){
        $url = "{$app}/home/index.php";
    }else if($_SESSION['ProjectPainel']->perfil == 'financeiro'){
        $url = "financeira/home/index.php";
    }else if($_SESSION['ProjectPainel']->perfil == 'site'){
        $url = "site/home/index.php";
    }else if($_SESSION['ProjectPainel']->perfil == 'consulta'){
        $url = "{$app}/home/index.php";
    }else{
        $url = "src/login/index.php";
    }
?>
<!doctype html>
<html lang="en" translate="no">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="img/icone.png" rel="icon">
    <meta name="google" content="notranslate">
    <title>capitalsolucoes - Painel de controle</title>
    <?php
    include("lib/header.php");
    ?>
  </head>
  <style>
body {

    background: url(img/fundopncred.jpg);
    background-repeat: no-repeat center fixed;
    background-size: cover;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}
.popupWappBg{
    position:fixed;
    right:0;
    top:0;
    bottom:0;
    left:0;
    background-color:#000;
    opacity:0.7;
    z-index: 998;
    display:none;
}
.popupWapp{
    position:fixed;
    right:0;
    top:0;
    bottom:0;
    width:var(--popupWapp-width);
    background-color:#fff;
    z-index: 999;
    display:none;
}
.popupWappButton{
    position:fixed;
    left:20px;
    bottom:20px;
    width:auto;
    height:auto;
    padding:10px;
    border-radius:10px;
    background-color:green;
    color:#fff;
    cursor:pointer;
    z-index: 997;
}
</style>

  <body chatData="<?=date("Y-m-d H:i:s")?>">

    <div class="Carregando">
        <div><i class="fa-solid fa-rotate fa-pulse"></i></div>
    </div>

    <div class="popupWappBg"></div>
    <div class="popupWapp"></div>
    <?php
    if($_SESSION['ProjectPainel']->wapp){
    ?>
    <div class="popupWappButton" data-bs-toggle="offcanvas" href="#offcanvasDireita" aria-controls="offcanvasDireita" >
        <i class="fa-brands fa-whatsapp" style="font-size:30px;"></i>
    </div>
    <?php
    }
    ?>
    <div class="CorpoApp"></div>


    <div class="toast-container bottom-0 end-0 p-3"></div>
    <?php
    include("lib/footer.php");
    if($_SESSION['ProjectPainel']->wapp) include("lib/js/wapp.php");
    ?>

    <script>
        $(function(){

            Carregando();
            $.ajax({
                url:"<?=$url?>",
                success:function(dados){
                    $(".CorpoApp").html(dados);
                }
            });

            setInterval(() => {
                $.ajax({
                    url:"lib/sessao.php",
                    success:function(dados){
                        $("body").attr("sessao",dados);
                    }
                });                
            }, 5000);

            $(".popupWappButton").click(function(){
                Carregando();
                // $.ajax({
                //     url:"financeira/wapp/index.php",
                //     success:function(dados){
                //         $(".popupWapp").html(dados);
                //         $(".popupWapp").show();
                //         $(".popupWappBg").show();
                //     }
                // });      
                $.ajax({
                    url:"financeira/clientes/wapp_lista.php",
                    success:function(dados){
                        $(".LateralDireita").html(dados);
                    }
                })            
                
            })



            if( navigator.userAgent.match(/Android/i)
                || navigator.userAgent.match(/webOS/i)
                || navigator.userAgent.match(/iPhone/i)
                || navigator.userAgent.match(/iPad/i)
                || navigator.userAgent.match(/iPod/i)
                || navigator.userAgent.match(/BlackBerry/i)
                || navigator.userAgent.match(/Windows Phone/i)
            ){
                $(".popupWapp").css("--popupWapp-width","100%")
            }
            else {
                $(".popupWapp").css("--popupWapp-width","80%")
            }



        })


        //Jconfirm
        jconfirm.defaults = {
            typeAnimated: true,
            type: "blue",
            smoothContent: true,
        }

    </script>

  </body>
</html>