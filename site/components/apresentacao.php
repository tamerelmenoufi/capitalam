<?php

$query = "select * from destaques";
$result = sisLog( $query);
$d = mysqli_fetch_object($result);

?>

<section id="destaque" class="about">
<div class="container">

<div class="row">

<div class="col-lg-6">
<div class='d-flex align-items-center'> 
  <a video <?=(($d->video)?"href='{$d->video}' class='glightbox btn-watch-video'":false)?> style="position:relative;" >
    <img class="img-fluid" src="<?=$localPainel?>site/volume/destaques/<?=$d->imagem?>" style="max-height:550px"/>
    <?php
    if(trim($d->video)){
    ?>
    <i class="fa-brands fa-youtube" style="position:absolute; left:50%; top:50%; margin-left:-50px; margin-top:-50px; z-index:1; color:#a1a1a1; font-size:100px;"></i>
    <?php
    }
    ?>
  </a>
</div>
</div>


<div class="col-lg-6">
  <div style="color:#144397">
    <div style="padding:30px"> </div>

    <p style="font-size:35px;font-weight:bold;text-align:center"> <?=$d->titulo?> </p>
    <p><?=$d->materia?></p>
   
    <?php
    if(trim($d->botao_titulo)){
    ?>
    <div style="padding:30px"> </div>
    <center> 
      <!-- <a <?=(($d->botao_url)?"href='{$d->botao_url}'":false)?> <?=((strtolower(substr($d->botao_url,0,4)) == 'http')?"target='_blank'":false)?> style="padding:10px;background:#fcce00;color:#144397;font-size:25px;text-align:center;font-weight:bold; text-decoration:none;">
      <?=$d->botao_titulo?></a> -->
      <a href="<?=$UrlWhatsApp?>" target="_blank" style="padding:10px;background:#fcce00;color:#144397;font-size:25px;text-align:center;font-weight:bold; text-decoration:none;">
      <?=$d->botao_titulo?></a>
    </center>
    <?php
    }
    ?>
  </div>

</div>


</div>
</div>
</section>

<script>
  $(function(){

    $("a[video]").on( "mouseover", function() {
      $(this).children("i").css("color","red");
    });

    $("a[video]").on( "mouseout", function() {
      $(this).children("i").css("color","#a1a1a1");
    })

  })
</script>