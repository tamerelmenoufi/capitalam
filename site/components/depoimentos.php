    <!-- ======= Recent Blog Posts Section ======= -->

    <style>

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
    color: #fff;
    font-weight: 700;
    margin: 15px 0 0 0;
    position: relative;
    transition: 0.3s;
}
.botaobranco {
    padding: 10px;
    padding-left: 35px;
    padding-right: 35px;
    border-radius: 0;
    font-size: 17px;
    color: #144397;
    background-color: #ffffff;
    border-color: #ffffff;
    margin: 10px;
}
.botaobranco:hover {
    color: #fff;
    background-color: #1f62d9;
    border-color: #2468e1;
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

  .recent-blog-posts .post-box .post-img {
    overflow: hidden;
    position: relative;
    border-radius: 0px;
}
  </style>

<div style="background:#144397;color:#fff">

    <section id="depoimentos"  style="padding:0px" class="recent-blog-posts">

      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2 style="color:#fff;font-weight:bold;margin-top:25px" >Depoimentos</h2>
          
        </div>

        <div class="row">


          <?php
          $query = "select * from depoimentos where situacao = '1' and tipo = 'audio' order by rand() limit 3";
          $result = mysqli_query($con, $query);
          while($d = mysqli_fetch_object($result)){
          ?>

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class=""  style="radial-gradient(circle, rgb(23 48 195) 0%, rgb(19 22 151) 48%)padding:13px;
            box-shadow: 0px 0px 5px #000;padding:10px">
            <h3 class="" style="overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;color:#fff">
          O que <b style="background:#000">dizem</b> <br> nossos clientes
          </h3>
         <div class="d-flex justify-content-center">
          <audio controls>         
              <source src="<?=$localPainel?>site/volume/depoimentos/<?=$d->imagem?>" type="audio/mpeg">
              </audio></div>

              <p style="color:#fff;font-size:16px;font-weight:bold;text-align:center"> <?=$d->nome?></p>
              <p style="color:#fff;font-size:12px;text-align:center;margin-top:-15px"><?=$d->empresa?></p>
              
            </div>
          </div>

          <?php
          }
          ?>

         

          <!-- <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class=""   style="radial-gradient(circle, rgb(23 48 195) 0%, rgb(19 22 151) 48%)padding:13px;
            box-shadow: 0px 0px 5px #000;padding:10px">
            <h3 class="" style="overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;color:#fff">
          O que <b style="background:#000">dizem</b> <br> nossos clientes
          </h3>
          <center>
          <audio controls>         
              <source src="assets/img/audio.mp3" type="audio/mpeg">
              </audio></center>

              <p style="color:#fff;font-size:16px;font-weight:bold;text-align:center"> Tamer Mohamed</p>
              <p style="color:#fff;font-size:12px;text-align:center;margin-top:-15px">ANTECIPE O FGTS</p>
              
            </div>
          </div>

          
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="" style="radial-gradient(circle, rgb(23 48 195) 0%, rgb(19 22 151) 48%)padding:13px;
            box-shadow: 0px 0px 5px #000;padding:10px">
            <h3 class="" style="overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;color:#fff">
          O que <b style="background:#000">dizem</b> <br> nossos clientes
          </h3>
              <div style="margin-top:10px;" class="post-img">
              
              
              <center>
          <audio controls>         
              <source src="assets/img/audio.mp3" type="audio/mpeg">
              </audio></center>

              <p style="color:#fff;font-size:16px;font-weight:bold;text-align:center"> Ellem Salvador</p>
              <p style="color:#fff;font-size:12px;text-align:center;margin-top:-15px">ANTECIPE O FGTS</p>
              
            </div>
          </div> -->

     
          <!-- <div class="col-lg-4" data-aos="fade-up" data-aos-delay="400">
            <div class="post-box">
              <div class="post-img"><img src="assets/img/blog/blog-2.jpg" class="img-fluid" alt=""></div>
              <div class="meta">
                <span class="post-date">Fri, September 05</span>
                <span class="post-author"> / Mario Douglas</span>
              </div>
              <h3 class="post-title">Et repellendus molestiae qui est sed omnis voluptates magnam</h3>
              <p>Voluptatem nesciunt omnis libero autem tempora enim ut ipsam id. Odit quia ab eum assumenda. Quisquam omnis aliquid necessitatibus tempora consectetur doloribus...</p>
              <a href="blog-details.html" class="readmore stretched-link"><span>Read More</span><i class="bi bi-arrow-right"></i></a>
            </div>
          </div>

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="600">
            <div class="post-box">
              <div class="post-img"><img src="assets/img/blog/blog-3.jpg" class="img-fluid" alt=""></div>
              <div class="meta">
                <span class="post-date">Tue, July 27</span>
                <span class="post-author"> / Lisa Hunter</span>
              </div>
              <h3 class="post-title">Quia assumenda est et veritatis aut quae</h3>
              <p>Quia nam eaque omnis explicabo similique eum quaerat similique laboriosam. Quis omnis repellat sed quae consectetur magnam veritatis dicta nihil...</p>
              <a href="blog-details.html" class="readmore stretched-link"><span>Read More</span><i class="bi bi-arrow-right"></i></a>
            </div>
          </div> -->


          <div style="padding:30px"></div>
          <!--<center style="margin-top:20px">
          <a href="servico_categoria.php">
         <button type="button" class=" botaobranco">
          Outros serviços
        </button></a>
      </center>-->


        </div>



        
      </div>

    </section><!-- End Recent Blog Posts Section -->

        </div>