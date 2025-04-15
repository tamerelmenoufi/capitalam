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
    <section id="noticias"  style="padding:0px; margin-bottom:30px;" class="recent-blog-posts">

      <div class="container" data-aos="fade-up">

        <div class="section-header">
          <h2>Noticias</h2>
          <p>Fique por dentro das atualizações no ramo de assessoria financeira com as nossas publicações.
          </p>
        </div>


        <div class="row">

          <?php
          $query = "select * from noticias where situacao = '1' order by codigo desc limit 0,3";
          $result = sisLog( $query);
          while($d = mysqli_fetch_object($result)){
          ?>

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="post-box">
              <div class="post-img evento">
              <div style="margin-top:10px;height:267px" class="post-img"><img src="<?=$localPainel?>site/volume/noticias/<?=$d->imagem?>" class="img-fluid" alt=""></div>
                <!-- <div style="background-image:url(<?=$localPainel?>site/volume/noticias/<?=$d->imagem?>);" class="imagemEventoFundo"></div>
                <div style="background-image:url(<?=$localPainel?>site/volume/noticias/<?=$d->imagem?>);" class="imagemEvento"></div> -->
              </div>
              <!-- <div class="meta">
                <span class="post-date">Tue, December 12</span>
                <span class="post-author"> / Julia Parker</span>
              </div> -->
              <h3 class="post-title" style="overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;"><?=$d->titulo?></h3>
              <p style="overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;"><?=strip_tags(str_replace('<',' <',str_replace('>','> ',$d->materia)))?></p>
              <a href="noticia.php?cod=<?=$d->codigo?>" class="mt-3 mb-3">
                <button type="button" class="botao botaoroxo">Leia Mais<i class="bi bi-arrow-right"></i></button>
              </a>
            </div>
          </div>

          <?php
          }
          ?>

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


          <center style="margin-top:20px">
          <a href="noticia_categoria.php">
         <button type="button" class=" botaoverde">
          Outras noticias
        </button></a>
      </center>


        </div>

      </div>

    </section><!-- End Recent Blog Posts Section -->