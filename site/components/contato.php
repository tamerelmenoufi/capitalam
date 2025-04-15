
<style>
    
    .botaodiferente{
      background: #ffffff;
    border: 0;
    padding: 13px 50px;
    color: #393286;
    transition: 0.4s;
    border-radius: 25px;
    border-left: 10px #393286 solid;
    border-right: #393286 10px solid;
    border-top: #393286 solid 1px;
    border-bottom: #393286 solid 1px;
}

.botaodiferente:hover{
    background: #393286;
    border: 0;
    padding: 13px 50px;
    color: #fff;
    transition: 0.4s;
    border-radius: 25px;
    border-left: 10px #393286 solid;
    border-right: #393286 10px solid;
    border-top: #393286 solid 1px;
    border-bottom: #393286 solid 1px;
}



  </style>

<?php

    $query = "select * from configuracoes where codigo = '1'";
    $result = sisLog( $query);
    $d = mysqli_fetch_object($result);
?><!-- ======= Contact Section ======= -->
    <style>
      .contact .php-email-form textarea {
        padding: 10px 12px;
        height: 115px!important;
      }
    </style>

    <section id="contact" class="contact" style="padding:0px!important">
    


    </div>

      

      <div class="exibir_mapa">
      <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3984.0578054013845!2d-60.02677828524287!3d-3.07924319775767!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zM8KwMDQnNDUuMyJTIDYwwrAwMScyOC41Ilc!5e0!3m2!1spt-BR!2sbr!4v1675953069641!5m2!1spt-BR!2sbr" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div><!-- End Google Maps -->

     
    </section><!-- End Contact Section -->


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