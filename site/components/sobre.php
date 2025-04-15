<?php

  $query = "select * from paginas_topicos where situacao = '1'";
  $result = sisLog( $query);
  $d = mysqli_fetch_object($result);

  $topicos = json_decode($d->topicos);

?>

<!-- ======= About Section ======= -->
<section id="conheca" class="about" style="background-color:#fff">
      <div class="container" data-aos="fade-up">

        <div class="section-header">
        <h2 style="color:#144397;font-weight:bold;margin-top:25px" ><?=$d->titulo?></h2>
        
          <p><?=$d->descricao?></p>
        </div>

        <div class="row g-4 g-lg-5" data-aos="fade-up" data-aos-delay="200">

          <div class="col-lg-5">
            <div class="about-img">
              <img src="<?=$localPainel?>site/volume/paginas_topicos/<?=$d->imagem?>" class="img-fluid" style="border-radius:20px;">

              <!-- <video  class="img-fluid" style="border-radius:20px; height:300px;" controls autoplay>
                <source src="<?=$localSite?>assets/videos/institucional.mp4" type="video/mp4">
                <source src="movie.ogg" type="video/ogg">
                Your browser does not support the video tag.
              </video> -->

            </div>
          </div>

          <div class="col-lg-7">
            <h3 class="pt-0 pt-lg-5"><?=$d->subtitulo?></h3>

            <!-- Tabs -->
            <ul class="nav nav-pills mb-3">
              <?php
              foreach($topicos->titulo as $i => $tipico){
              ?>
              <li><a class="nav-link <?=(($i == 0)?'active':false)?>" data-bs-toggle="pill" href="#tab<?=($i+1)?>"><?=$tipico?></a></li>
              <?php
              }
              ?>
            </ul><!-- End Tabs -->

            <!-- Tab Content -->
            <div class="tab-content">


            <?php
            foreach($topicos->descricao as $i => $descricao){
            ?>
            <div class="tab-pane fade show <?=(($i == 0)?'active':false)?>" id="tab<?=($i+1)?>" style="text-align:justify; color:var(--color-secondary-light)">
              <?=str_replace('&nbsp;'," ", $descricao)?>
            </div>
            <?php
            }
            ?>


              <!-- <div class="tab-pane fade show active" id="tab1">




              <p class="fst-italic">A PROJECT é uma empresa pautada no desenvolvimento de soluções, execução de projetos e treinamento.</p>
              <p class="fst-italic">Entendemos que o mercado cada vez mais globalizado se encontra em constante evolução, necessitando cada vez mais de produtos e serviços, no tempo e no local exato, ao menor custo possível.</p>
              <p class="fst-italic">Contamos com uma equipe sólida e experiente que persegue o sucesso de forma conjunta, e que entende que a completude e somatório dos esforços entre as partes é fundamental.</p>
              <p class="fst-italic">A atuação da PROJECT se dá tanto no privado, quanto no meio público. No segmento privado, a capacidade técnica de nosso quadro nos credencia a entregarmos resultados brilhantes, sempre com presteza no atendimento e responsabilidade ímpar na execução. No público, atuamos com uma equipe campeã, que capta e participa de processos licitatórios em todas as esferas do setor, abarcando todas as suas modalidades e tipos, entregando sempre a sociedade um serviço final de ótima qualidade.</p>
              <p class="fst-italic">Para tanto, afim de atingir os objetivos que nos propomos nossa atuação se dá sempre de forma ética e íntegra, respeitando as normas e leis vigentes.</p> -->



                <!-- <div class="d-flex align-items-center mt-4">
                  <i class="bi bi-check2"></i>
                  <h4>Repudiandae rerum velit modi et officia quasi facilis</h4>
                </div>
                <p>Laborum omnis voluptates voluptas qui sit aliquam blanditiis. Sapiente minima commodi dolorum non eveniet magni quaerat nemo et.</p>

                <div class="d-flex align-items-center mt-4">
                  <i class="bi bi-check2"></i>
                  <h4>Incidunt non veritatis illum ea ut nisi</h4>
                </div>
                <p>Non quod totam minus repellendus autem sint velit. Rerum debitis facere soluta tenetur. Iure molestiae assumenda sunt qui inventore eligendi voluptates nisi at. Dolorem quo tempora. Quia et perferendis.</p>

                <div class="d-flex align-items-center mt-4">
                  <i class="bi bi-check2"></i>
                  <h4>Omnis ab quia nemo dignissimos rem eum quos..</h4>
                </div>
                <p>Eius alias aut cupiditate. Dolor voluptates animi ut blanditiis quos nam. Magnam officia aut ut alias quo explicabo ullam esse. Sunt magnam et dolorem eaque magnam odit enim quaerat. Vero error error voluptatem eum.</p> -->

              <!-- </div> -->
              <!-- End Tab 1 Content -->

              <!-- <div class="tab-pane fade show" id="tab2">

                <div class="d-flex align-items-center mt-4">
                  <i class="bi bi-check2"></i>
                  <h4>Missão</h4>
                </div>
                <p>Se destacar no segmento privado e público, atuando no processo como agente facilitador e provedor de meios técnicos para atividade finalística requerida.</p>

                <div class="d-flex align-items-center mt-4">
                  <i class="bi bi-check2"></i>
                  <h4>Visão</h4>
                </div>
                <p>Apresentar aos clientes soluções técnicas viáveis, se posicionando sempre de maneira transparente e íntegra, sendo para o mercado nacional um referencial de probidade e aptidão no desenvolvimento da sua atividade.</p>

                <div class="d-flex align-items-center mt-4">
                  <i class="bi bi-check2"></i>
                  <h4>Valores</h4>
                </div>
                <i class="fa-solid fa-check-double" style="font-size:10px; margin-left:25px;"></i> Cooperação<br>
                <i class="fa-solid fa-check-double" style="font-size:10px; margin-left:25px;"></i> Aprimoramento<br>
                <i class="fa-solid fa-check-double" style="font-size:10px; margin-left:25px;"></i> Integridade<br>
                <i class="fa-solid fa-check-double" style="font-size:10px; margin-left:25px;"></i> Qualidade</p>

              </div> -->
              <!-- End Tab 2 Content -->

              <!-- <div class="tab-pane fade show" id="tab3">

                <div class="d-flex align-items-center mt-4">
                  <i class="bi bi-check2"></i>
                  <h4>Melhoria Contínua</h4>
                </div>
                <p>Realizamos regularmente investimentos na capacitação e na busca por processos ágeis, viáveis e seguros para a organização e seus colaboradores, de modo que, os seus serviços sejam realizados com a maior eficiência possível.</p>

                <div class="d-flex align-items-center mt-4">
                  <i class="bi bi-check2"></i>
                  <h4>Responsabilidade</h4>
                </div>
                <p>Atuar com zelo em detrimento das normas legais ambientais, jurídicas e de segurança do trabalho, sendo uma forte referência nos preceitos ora estabelecidos, fortalecendo e ratificando cotidianamente o compliance.</p>

                <div class="d-flex align-items-center mt-4">
                  <i class="bi bi-check2"></i>
                  <h4>Compromisso Social</h4>
                </div>
                <p>Racionalizar o uso dos recursos naturais e inserir a comunidade nos projetos, ao ponto que, o meio e o fim sejam um elo indissociável de bem feitoria ao meio ambiente e crescimento humano em aspectos diversos.</p>

              </div> -->
              <!-- End Tab 3 Content -->


            </div>

          </div>

        </div>

      </div>

     
     



    </section>
    
    <!-- End About Section -->

    <section id="fac" class="about" style="background-color:#fff">
      <div class="container">

        <div class="row gy-5 gx-lg-5">

          <div class="col-lg-5">

            <div class="info">
            
            <div style="margin-top:20px">
          <p style="color:#144397;font-size:25px;text-align:center;font-weight:bold;font-style:italic">Perguntas Frequentes</p>

          <p style="color:#144397;font-size:18px;text-align:center;">
      O que preciso para sacar meu FGTS?
        </p>

        <p style="color:#144397;font-size:18px;text-align:center;">
     Quais os documentos necessários para fazer a contratação?
        </p>

        <p style="color:#144397;font-size:18px;text-align:center;">
      Consigo fazer a contratação online?
        </p>

        <center> 
    <a href="perguntas-frequentes.php"><d style="padding:10px;background:#fcce00;color:#144397;font-size:18px;text-align:center;font-weight:bold;padding-top:5px;padding-right:25px;padding-left:25px">
    Confira as respostas aqui</d></a>
</center>
  <div style="padding:10px"> </div>
        <div style="padding:15px"> </div>

  </div>
              
            </div>

          </div>

          <div class="col-lg-7">
            <form class="php-email-form">
              <div class="row">
                <p style="color:#144397;font-size:18px;text-align:center;margin-top:25px">Você continua com dúvidas, escreva-as para que nos possamos ajuda-lo(a).  </p>
                <div class="col-md-6 form-group">
                  <input type="text" name="name" class="form-control" id="name" placeholder="Nome Completo" required>
                </div>
                <div class="col-md-6 form-group mt-3 mt-md-0">
                  <input type="email" class="form-control" name="email" id="email" placeholder="E-mail" required>
                </div>
              </div>

              <div class="form-group mt-3">
                <textarea class="form-control" name="message" id="message" placeholder="..Escreva sua dúvida aqui.." required></textarea>
              </div>
              <div class="text-center"><button type="submit" class="botaodiferente" style="
                
                background: #ffffff;
                border: 0;
                padding: 13px 50px;
                color: #144397;
                transition: 0.4s;
                border-radius: 25px;
                border-left: 10px #144397 solid;
                border-right: #144397 10px solid;
                border-top: #144397 solid 1px;
                border-bottom: #144397 solid 1px;
                margin-top: 10px;
                margin-bottom:10px;
              
              "
              >Enviar</button></div>
            </form>
          </div><!-- End Contact Form -->

        </div>
</div>
          </section>


<script>
      $(function(){
        $.ajax({
          url:"plugins/visualizar_mapa.php",
          success:function(dados){
            $(".exibir_mapa").html(dados);
          }
        });


        $( "form.php-email-form" ).on( "submit", function( event ) {

          event.preventDefault();
          // materia = editor.getData();
          data = $( this ).serialize();
          // data.push({name:'materia', value:editor});
          // console.log(data);

          $.ajax({
            url:"plugins/enviar_email.php",
            type:"POST",
            data,
            success:function(dados){

              $("#name").val('');
              $("#email").val('');
              $("#message").val('');

              $.alert({
                content:dados,
                type:"orange",
                title:false,
                buttons:{
                  'ok':{
                    text:'<i class="fa-solid fa-check"></i> OK',
                    btnClass:'btn btn-warning'
                  }
                }
              });

            }
          });
        });


      })
    </script>