    <!-- ======= Recent Blog Posts Section ======= -->
<section id="recent-blog-posts" class="recent-blog-posts">

<style>

  .botaoazul{
    background: var(--color-primary);
    border: 0;
    padding: 10px 35px;
    color: #fff;
    transition: 0.4s;
    border-radius: 0;


  }



  .recent-blog-posts .post-box .post-title {
    font-size: 24px;
    color: var(--color-secondary);
    font-weight: 700;
    margin: 15px 0 0 0;
    position: relative;
    transition: 0.3s;
}

.recent-blog-posts .post-box .post-title:hover {
    font-size: 24px;
    color: #574ec2;
    font-weight: 700;
    margin: 15px 0 0 0;
    position: relative;
    transition: 0.3s;
}
    .botaoverde{
      padding: 15px;
      padding-left: 35px;
      padding-right: 35px;
      border-radius: 25px 2px 25px;
      font-size: 17px;
      color: #fff;
    background-color: #393287;
    border-color: #393287;
    }
    .botaoverde:hover {
    color: #fff;
    background-color: #574ec2;
    border-color: #574ec2;
}
    
    .botaoroxo:hover {
    color: #fff;
    background-color: #574ec2;
    border-color: #574ec2;
}
 .botaoroxo {
    color: #fff;
    background-color: #393287;
    border-color: #393287;
}
.botao {
    display: inline-block;
    font-weight: 400;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, 
    border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;

  }
</style>

    <!-- ======= Breadcrumbs ======= -->
    <div class="bg-primary breadcrumbs" class="breadcrumbs" style="margin-top:20px; background:#3a338b !important;color:#fff">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2 style="">Time Completo</h2>

        </div>

      </div>
    </div><!-- End Breadcrumbs -->
    <section id="time" class="team">
      <div class="container" data-aos="fade-up">

        

        <div class="row gy-5">


        <?php
            $query = "select * from time where situacao = '1'  order by codigo  desc";
            $result = sisLog( $query);
            while($d = mysqli_fetch_object($result)){

              $midias = json_decode($d->canais_contatos);

        ?>
          <div class="col-xl-3 col-md-6 d-flex" data-aos="zoom-in" data-aos-delay="200">
            <div class="team-member">
              <div class="member-img" style="height:320px">
                <img src="<?=$localPainel?>site/volume/time/<?=$d->imagem?>" class="img-fluid" alt="">
              </div>
              <div class="member-info">
                <div class="social">
                  <?php
                    $midias_sociais = [
                      'facebook' => 'https://www.facebook.com/',
                      'twitter' => 'https://twitter.com/',
                      'instagram' => 'https://www.instagram.com/',
                      'youtube' => 'https://www.youtube.com/',
                      'linkedin' => 'https://www.linkedin.com/',
                      'whatsapp' => 'https://api.whatsapp.com/send?phone='
                    ];

                    foreach($midias_sociais as $ind => $url){
                      if($midias->$ind){
                  ?>
                  <a href="<?=$url.$midias->$ind?>" target="_black"><i class="bi bi-<?=$ind?>"></i></a>
                  <?php
                      }
                    }
                  ?>
                  <!-- <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a> -->
                </div>
                <h4><?=$d->nome?></h4>
                <span><?=$d->cargo?></span>
              </div>
            </div>
          </div>
          <?php
            }

            /*
          ?>

          <!-- End Team Member -->

          <div class="col-xl-4 col-md-6 d-flex" data-aos="zoom-in" data-aos-delay="400">
            <div class="team-member">
              <div class="member-img">
                <img src="assets/img/team/team-2.jpg" class="img-fluid" alt="">
              </div>
              <div class="member-info">
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
                <h4>Sarah Jhonson</h4>
                <span>Programadora Senior</span>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-xl-4 col-md-6 d-flex" data-aos="zoom-in" data-aos-delay="600">
            <div class="team-member">
              <div class="member-img">
                <img src="assets/img/team/team-3.jpg" class="img-fluid" alt="">
              </div>
              <div class="member-info">
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
                <h4>William Anderson</h4>
                <span>Designer de Web</span>
              </div>
            </div>
          </div><!-- End Team Member -->
            <?php
            //*/
            ?>
<a style="color:#fff;text-align:right" href="./">
    <button style="margin-top:10px;padding:10px;text-align:right"  type="button"  class=" botaoverde ">
                Voltar ao inicio</button></a>

        </div>

      </div>
    </section><!-- End Team Section -->

    </section><!-- End Recent Blog Posts Section --> 
    
    