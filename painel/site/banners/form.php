<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/capitalam/painel/lib/includes.php");

    if($_POST['excluir']){

    }

    if($_POST['acao'] == 'salvar'){

      $dados = $_POST;
      unset($dados['acao']);
      unset($dados['codigo']);

      //Imagem
      $img = false;
      unset($dados['base64']);
      unset($dados['imagem_tipo']);
      unset($dados['imagem_nome']);

      if($_POST['base64'] and $_POST['imagem_tipo'] and $_POST['imagem_nome']){

        if($_POST['imagem']) unlink("../volume/banners/{$_POST['imagem']}");

        $base64 = explode('base64,', $_POST['base64']);
        $img = base64_decode($base64[1]);
        $ext = substr($_POST['imagem_nome'], strripos($_POST['imagem_nome'],'.'), strlen($_POST['imagem_nome']));
        $nome = md5($_POST['base64'].$_POST['imagem_tipo'].$_POST['imagem_nome'].date("YmdHis")).$ext;

        if(!is_dir("../volume/banners")) mkdir("../volume/banners");
        if(file_put_contents("../volume/banners/".$nome, $img)){
          $dados['imagem'] = $nome;
        }
      }
      //Fim da Verificação da Imagem


      //Imagem
      $img = false;
      unset($dados['base64_mb']);
      unset($dados['imagem_tipo_mb']);
      unset($dados['imagem_nome_mb']);

      if($_POST['base64_mb'] and $_POST['imagem_tipo_mb'] and $_POST['imagem_nome_mb']){

        if($_POST['imagem_mb']) unlink("../volume/banners/{$_POST['imagem_mb']}");

        $base64 = explode('base64,', $_POST['base64_mb']);
        $img = base64_decode($base64[1]);
        $ext = substr($_POST['imagem_nome_mb'], strripos($_POST['imagem_nome_mb'],'.'), strlen($_POST['imagem_nome_mb']));
        $nome = md5($_POST['base64_mb'].$_POST['imagem_tipo_mb'].$_POST['imagem_nome_mb']).date("YmdHis").$ext;

        if(!is_dir("../volume/banners")) mkdir("../volume/banners");
        if(file_put_contents("../volume/banners/".$nome, $img)){
          $dados['imagem_mb'] = $nome;
        }
      }
      //Fim da Verificação da Imagem


      $campos = [];
      foreach($dados as $i => $v){
        $campos[] = "{$i} = '{$v}'";
      }
      if($_POST['codigo']){
        $query = "UPDATE banners set ".implode(", ",$campos)." WHERE codigo = '{$_POST['codigo']}'";
        mysqli_query($con, $query);
        $acao = mysqli_affected_rows($con);
      }else{
        $query = "INSERT INTO banners set ".implode(", ",$campos)."";
        mysqli_query($con, $query);
        $acao = mysqli_affected_rows($con);
      }

      if($acao){
        echo "Atualização realizada com sucesso!";
      }else{
        echo "Nenhuma alteração foi registrada!";
      }

      exit();


    }


    if($_POST['cod']){
      $query = "select * from banners where codigo = '{$_POST['cod']}'";
      $result = mysqli_query($con, $query);
      $d = mysqli_fetch_object($result);
    }else{
      $query = "select * from banners where codigo = '{$_POST['vinculo']}'";
      $result = mysqli_query($con, $query);
      $v = mysqli_fetch_object($result);
    }

?>
<style>
  .titulo<?=$md5?>{
    position:fixed;
    top:7px;
    margin-left:50px;
  }
</style>

<h3 class="titulo<?=$md5?>">Gerenciamento de Banners</h3>

    <form id="acaoMenu">

      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título do Banner" value="<?=$d->titulo?>">
        <label for="titulo">Título</label>
        <div class="form-text">Digite o nome do menu que aparecerá no site.</div>
      </div>

      <div class="form-floating mb-3">
        <textarea class="form-control" style="height:100px;" id="descricao" name="descricao" placeholder="Descrição do Banner"><?=$d->titulo?></textarea>
        <label for="titulo">Descrição do Banner</label>
        <div class="form-text">Digite a descrição do Banner.</div>
      </div>

      <div showImage class="form-floating" style="display:<?=(($d->imagem)?'block':'none')?>">
        <img src="<?=$localPainel?>site/volume/banners/<?=$d->imagem?>" class="img-fluid mt-3 mb-3" alt="" />
      </div>

      <!-- <div class="form-floating"> -->
        <input type="file" opc="pc" class="form-control" placeholder="Banner">
        <input type="hidden" id="base64" name="base64" value="" />
        <input type="hidden" id="imagem_tipo" name="imagem_tipo" value="" />
        <input type="hidden" id="imagem_nome" name="imagem_nome" value="" />
        <input type="hidden" id="imagem" name="imagem" value="<?=$d->imagem?>" />
        <!-- <label for="url">Banner</label> -->
        <div class="form-text mb-3">Selecione a imagem para o Banner</div>
      <!-- </div> -->




      <div showImage_mb class="form-floating" style="display:<?=(($d->imagem_mb)?'block':'none')?>">
        <img src="<?=$localPainel?>site/volume/banners/<?=$d->imagem_mb?>" class="img-fluid mt-3 mb-3" alt="" />
      </div>

      <!-- <div class="form-floating"> -->
        <input type="file" opc="mb" class="form-control" placeholder="Banner">
        <input type="hidden" id="base64_mb" name="base64_mb" value="" />
        <input type="hidden" id="imagem_tipo_mb" name="imagem_tipo_mb" value="" />
        <input type="hidden" id="imagem_nome_mb" name="imagem_nome_mb" value="" />
        <input type="hidden" id="imagem_mb" name="imagem_mb" value="<?=$d->imagem_mb?>" />
        <!-- <label for="url">Banner</label> -->
        <div class="form-text mb-3">Selecione a imagem para o Banner (Versão Mobile)</div>
      <!-- </div> -->

      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="url" name="url" placeholder="Endereço do link do Banner" value="<?=$d->url?>">
        <label for="url">Endereço (URL)</label>
        <div class="form-text">Digite o caminho do link que deseja direcionar com o banner.</div>
      </div>

      <div class="form-floating">
        <select id="situacao" name="situacao" class="form-control" placeholder="Situação">
          <option value="1" <?=(($d->situacao == '1')?'selected':false)?>>Liberado</option>
          <option value="0" <?=(($d->situacao == '0')?'selected':false)?>>Bloqueado</option>
        </select>
        <label for="situacao">Banner</label>
        <div class="form-text">Selecione a imagem para o Banner</div>
      </div>

      <button type="submit" data-bs-dismiss="offcanvas" class="btn btn-primary mt-3"> <i class="fa fa-save"></i> Salvar Dados</button>
      <button cancelar type="button" data-bs-dismiss="offcanvas" class="btn btn-danger mt-3"> <i class="fa fa-cancel"></i> Cancelar</button>

      <input type="hidden" id="acao" name="acao" value="salvar" >
      <input type="hidden" id="codigo" name="codigo" value="<?=$d->codigo?>" >
    </form>

<script>
    $(function(){

      Carregando('none');

      // $("#acaoMenu button[cancelar]").click(function(){
      //   $("div[formBanners]").html('');
      // })


      $( "form" ).on( "submit", function( event ) {

        data = [];

        event.preventDefault();

        data = $( this ).serialize();

        $.ajax({
          url:"site/banners/form.php",
          type:"POST",
          data,
          success:function(dados){

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

            $("div[listaBanners]").html('');
            $.ajax({
              url:"site/banners/lista.php",
              success:function(dados){
                  // $("div[listaBanners]").html(dados);
                  $("#paginaHome").html(dados);
              }
            });

          }
        });
      });





      if (window.File && window.FileList && window.FileReader) {

        $('input[type="file"]').change(function () {

            if ($(this).val()) {
                var files = $(this).prop("files");
                opc = $(this).attr("opc");
                for (var i = 0; i < files.length; i++) {
                    (function (file) {
                        var fileReader = new FileReader();
                        fileReader.onload = function (f) {


                        /*
                        //////////////////////////////////////////////////////////////////

                        var img = new Image();
                        img.src = f.target.result;

                        img.onload = function () {



                            // CREATE A CANVAS ELEMENT AND ASSIGN THE IMAGES TO IT.
                            var canvas = document.createElement("canvas");

                            var value = 50;

                            // RESIZE THE IMAGES ONE BY ONE.
                            w = img.width;
                            h = img.height;
                            img.width = 800 //(800 * 100)/img.width // (img.width * value) / 100
                            img.height = (800 * h / w) //(img.height/100)*img.width // (img.height * value) / 100

                            var ctx = canvas.getContext("2d");
                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                            canvas.width = img.width;
                            canvas.height = img.height;
                            ctx.drawImage(img, 0, 0, img.width, img.height);

                            // $('.Foto').append(img);      // SHOW THE IMAGES OF THE BROWSER.
                            console.log(canvas.toDataURL(file.type));

                            ///////


                            // var Base64 = canvas.toDataURL(file.type); //f.target.result;

                            // $("#encode_file").val(Base64);
                            // $("#encode_file").attr("nome", name);
                            // $("#encode_file").attr("tipo", type);

                            // $(".Foto").css("background-image",`url(${Base64})`);
                            // $(".Foto div i").css("opacity","0");
                            // $(".Apagar span").css("opacity","1");

                            //////



                        }

                        //////////////////////////////////////////////////////////////////
                        //*/


                        var Base64 = f.target.result;
                        var type = file.type;
                        var name = file.name;

                        if(opc == 'mb'){
                          $("#base64_mb").val(Base64);
                          $("#imagem_tipo_mb").val(type);
                          $("#imagem_nome_mb").val(name);

                          $("div[showImage_mb] img").attr("src",Base64);
                          $("div[showImage_mb]").css("display",'block');
                        }else{
                          $("#base64").val(Base64);
                          $("#imagem_tipo").val(type);
                          $("#imagem_nome").val(name);

                          $("div[showImage] img").attr("src",Base64);
                          $("div[showImage]").css("display",'block');
                        }




                        };
                        fileReader.readAsDataURL(file);
                    })(files[i]);
                }
          }
        });
      } else {
        alert('Nao suporta HTML5');
      }




    })
</script>