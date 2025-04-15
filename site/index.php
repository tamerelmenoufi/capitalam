<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/site/assets/lib/includes.php");
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">


  <title>capitalsolucoes</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  
  <!-- Favicons -->
  <link href="assets/img/icone.png" rel="icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Source+Sans+Pro:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Variables CSS Files. Uncomment your preferred color scheme -->
  <!-- <link href="assets/css/variables.css" rel="stylesheet"> -->
  <link href="assets/css/variables-blue.css" rel="stylesheet">
  <!-- <link href="assets/css/variables-green.css" rel="stylesheet"> -->
  <!-- <link href="assets/css/variables-orange.css" rel="stylesheet"> -->
  <!-- <link href="assets/css/variables-purple.css" rel="stylesheet"> -->
  <!-- <link href="assets/css/variables-red.css" rel="stylesheet"> -->
  <!-- <link href="assets/css/variables-pink.css" rel="stylesheet"> -->

  <!-- Template Main CSS File -->
  <link href="assets/css/main.css?1" rel="stylesheet">

  <!-- =======================================================
  * Template Name: HeroBiz - v2.1.0
  * Template URL: https://bootstrapmade.com/herobiz-bootstrap-business-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <!-- JQUERY -->
  <script src="assets/vendor/jquery-3.6.0/jquery-3.6.0.min.js"></script>

  <!-- LIB CONFIRM -->
  <link href="assets/vendor/jquery-confirm-v3.3.4/dist/jquery-confirm.min.css" rel="stylesheet" >
  <script src="assets/vendor/jquery-confirm-v3.3.4/dist/jquery-confirm.min.js" ></script>

  <!-- LIB MASK -->
  <script src="assets/vendor/jQuery-Mask/jquery.mask.min.js" ></script>

  <!-- FONTE AWESOME -->
  <link href="assets/vendor/fontawesome-free-6.1.1-web/css/all.css" rel="stylesheet">

  <!-- JQUERY UI -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

  <?php include("assets/lib/scripts.php"); ?>


</head>

<body>

<style>
  .btn-warning {
    color: #dee2e6;
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.rodape1:hover {
    background: #ffdd2c;
    color:#144397;
}

.rodape1 {
  color:#144397;
  margin-right:45px;
  position: fixed;
  visibility: hidden;
  opacity: 0;
  right: 15px;
  bottom: 50px;
    z-index: 995;
    background: #ffdd2c;
    width: auto;
    /* height: 29px; */
    border-radius: 4px;
    transition: all 0.4s;
    padding: 10px;
    font-weight:bold;
}
  </style>
<?php include("assets/lib/scripts_body.php"); ?>
<div class="popup">
    <span><i class="bi bi-x"></i></span>
    <div class="body"></div>
</div>

<main id="main">
<?php

    $componentes = [
        'menu',
        // 'banner_principal',
         'banner_principal_scroll',
          'apresentacao',
          'depoimentos',
          'revolucionando',
          'sobre',
         //  'video',
        // 'banner_principal2',
        // 'banner_principal3',
        
        'servicos',
        
        
        
       // 'noticias',
        //'galeria',
        //'banner_depoimentos',


        // 'pagina_interna',
        // 'pagina_interna2',
        // 'mais_noticias',
        // 'noticias_detalhes',
        // 'produtos_servicos',
        // 'clientes',
        // 'destaque',
        // 'video',
        // 'solucoes',
        // 'produtos_servicos2',
        // 'planos',
        // 'faq',
        //
        'contato',
        'rodape'
    ];

    foreach($componentes as $i => $v){
        include("components/{$v}.php");
    }



?>
</main><!-- End #main -->

  <a href="#" style="color: #fff ;background-color: #154fb7;border-color: #154fb7;" class="scroll-top d-flex align-items-center justify-content-center btn btn-primary">
    <i class="bi bi-arrow-up-short"></i></a>

    <a href="<?=$UrlWhatsApp?>" style="" class=" rodape1 scroll-top   active">
ANTECIPE SEU FGTS
    </a>
  <!-- <div id="preloader"></div> -->



  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBSnblPMOwEdteX5UPYXf7XUtJYcbypx6w&language=pt&region=BR"
    async
></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="assets/js/js.js?opc123"></script>


  <script>
    $(function(){

    // $.dialog({
    //   content:'url:popup.php',
    //   title:'<h1 class="w-100 text-center">Dia de Antecipação FGTS</h1>',
    //   columnClass:'col-12'
    // });


      $('.contagem').each(function () {
        console.log($(this).attr("valor"));
          $(this).prop('Counter',0).animate({
              Counter: $(this).attr("valor")
          }, {
              duration: 5000,
              easing: 'swing',
              step: function (now) {
                  tipo = $(this).attr("tipo")
                  if(tipo == 'moeda'){
                      $(this).text(Math.ceil(now).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
                  }else{
                      $(this).text(Math.ceil(now));
                  }
                  
              }
          });
      });   

      $.ajax({
        url:"assets/lib/log_acessos.php",
        success:function(dados){
          //Retorno da função
          // console.log(dados);
        },
        error:function(){
          console.log('Error');
        }
      });


      setTimeout(() => {
        $("#loom-companion-mv3").remove();
      }, 1000);
    })
  </script>

  <!-- SCRIPT DA POLITICA DE PRIVACIDADE -->
  <?php
        if($_GET['u'] != 'politica_privacidade'){
        ?>
           
            <div
            style="margin:0px;position:fixed!important; top:0; right:0; left:0; bottom:0;
            background-color:#000000d6;z-index:99999999999;"
            id="exemplo1_fundo"
            >  
            </div>
        <?php
        }
        ?>       
            
            <div class=" "
            style=" margin:0px;position:fixed!important; bottom:0;
            background-color:#000000d6;z-index:999999999999;padding:20px;width:100%;color:#fff;font-weight:bold"
            id="exemplo1"
            >  
                <div class="col-md-9" style="" >
                    Este site utiliza cookies confiáveis e inofensivos para garantir uma melhor experiência de navegação. <br> 
                    <a style="font-size:16px;" href="politica.php?">Política de Privacidade. </a>
                </div>
            
                <div class="col-md-3" style="" >
                    <span><a id="ocultar" style="border-radius:16px;margin:5px;font-size:16px;" class="btn btn-warning pull-right"  role="button">Aceitar</a></span>
                </div> 
            </div>  
            
        <script>
            $(function(){
                
                verifica = window.localStorage.getItem('aceita_cookie');
                
                if(verifica === '1'){
                    $("#exemplo1, #exemplo1_fundo").hide();
                }
                
                $("#ocultar").click(function () {
                    $("#exemplo1, #exemplo1_fundo").hide();
                    window.localStorage.setItem('aceita_cookie', '1');
                });
                
                
                
            })
        </script>
        <!-- SCRIPT DA POLITICA DE PRIVACIDADE -->




</body>

</html>