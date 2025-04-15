
  <style>

/* .footer .footer-legal {
  padding: 30px 0;
  background: #057a34!important;
} */

.footer .footer-legal .social-links a:hover {

    text-decoration: none;
}

.footer .footer-legal {
    padding: 15px 0;
background:none;
}
  </style>

  <!-- ======= Footer ======= -->
  

<footer id="footer" class="footer" style="background:#144397">
<div class="container text-center footer-legal" >
  <div class="row align-items-center">
    <div class="col-md-3  ">
    <p style="color:#fff;font-size:18px;text-align:center;font-weight:bold;font-style:italic;margin-bottom:0px">UNIDADE CIDADE NOVA</p>
<p style="color:#fff">Rua Prof. felix Valois, 61<br> Cidade nova - Manaus/AM</p>
    </div>
    <div class="col-md-3">
    <p style="color:#fff;font-size:18px;text-align:center;font-weight:bold;font-style:italic;margin-bottom:0px">UNIDADE MANOA</p>
<p style="color:#fff">Av. Francisco Queiroz, 02  <br> Manoa - Manaus/AM</p>

    </div>
    <div class="col-md-6">
      <div class="social-links">
    <?php

$query = "select * from configuracoes where codigo = '1'";
$result = sisLog( $query);
$d = mysqli_fetch_object($result);

$midias = json_decode($d->midias_sociais);

$midias_sociais = [
  'facebook' => 'https://www.facebook.com/',
  'twitter' => 'https://twitter.com/',
  'instagram' => 'https://instagram.com/',
  'youtube' => 'https://www.youtube.com/',
  'linkedin' => 'https://www.linkedin.com/',
  'whatsapp' => 'https://api.whatsapp.com/send?phone='
];

foreach($midias_sociais as $ind => $url){
  if($midias->$ind){
?>
<a href="<?=$url.$midias->$ind?>" target="_black" class="<?=$ind?>"><i class="bi bi-<?=$ind?>"></i></a>
<?php
  }
}
?> 
</div>
    </div>
  </div>
</div>

<div style="text-align:center;font-size:14px;padding-bottom:2px;margin-top:40px">
  <div class="row g-0">
  <div class="col-md-2"> </div>
  <div class="col-md-8" style="padding:15px">
  
  <p style="text-align: justify">
  A Capital Soluções não é uma instituição financeira e não realiza operações de crédito diretamente. A Capital Soluções é uma plataforma digital que atua como correspondente bancário para facilitar o processo de contratação de empréstimos. Como correspondente bancário, seguimos as diretrizes do Banco Central do Brasil, nos termos da Resolução CMN 4.935/2021, do BACEN. Toda avaliação de crédito será realizada conforme a política de crédito da Instituição Financeira escolhida pelo usuário. Antes da contratação de qualquer serviço através de nossos parceiros, você receberá todas as condições e informações relativas ao produto a ser contrato, de forma completa e transparente. As taxas de juros, margem consignável e prazo de pagamento praticados nos empréstimos com consignação em pagamento dos Governos Federais, Estaduais e Municipais, Forças armadas e INSS observam as determinações de cada convênio, bem como a política de crédito da instituição financeira a ser utilizada.
  </p>

  <p>
  EJ SERVICOS DE CORRESPONDENTES DE INSTITUICOES FINANCEIRAS LTDA - CNPJ 27.652.302/0001-09 <br>
  Endereço: Rua Professor Felix Valois, 61 - 69095-010 - Manaus – Amazonas
</p>
<p>Copyright © 2025 Capital Soluções. Todos Direitos Reservados</p>
</div>
<div class="col-md-2"> </div>
<!--<div class="col-md-6 text-center text-md-start">
  <a class="janela" janela="components/popup.php" style="color:#fff; text-decoration:underline; cursor:pointer" >Todos os direitos reservados </a>
</div>
-->
</div> 
</div>


</footer>


<script>
    $(function(){

    <?php
    if($_GET['u'] == 'termo'){
    ?>
    // $.dialog({
    //             title:false,
    //             content:"url:components/popup.php",
    //             columnClass:"col-md-6"
    //         })
    <?php
    }
    ?>


    <?php
    if($_GET['u'] == 'politica'){
    ?>
    // $.dialog({
    //             title:false,
    //             content:"url:components/politica_privacidade.php",
    //             columnClass:"col-md-12"
    //         })
    <?php
    }
    ?>

        
        // $(".janela").click(function(){
        //     url = $(this).attr("janela");
        //     console.log(url);
        //     $.dialog({
        //         title:false,
        //         content:"url:"+url,
        //         columnClass:"col-md-6"
        //     })
        // });        
    });
    
</script>



