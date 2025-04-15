<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");

?>
<style>
  tr{
      cursor:pointer;
  }
  .wapp-select{
    background-color:#ccc;
  }
</style>

<div class="col">
  <div class="m-3">

    <div class="row">
      <div class="col">
        <div class="card">
          <h5 class="card-header">Lista de Clientes </h5>
          <div class="card-body">

            <div class="table-responsive">
            <table class="table table-striped table-hover">

              <tbody>
                <?php
                  $query = "select 
                                    max(a.codigo) as cod,
                                    (select concat(mensagem,'^',data,'^',tipo) from wapp_chat where max(a.codigo) = codigo) as mensagem,
                                    (select data from wapp_chat where max(a.codigo) = codigo) as ordem,
                                    IF(a.de = '{$ConfWappNumero}',a.para, a.de) as agrupar,
                                    b.nome,
                                    b.status_atual as log,
                                    b.codigo as cod_cliente,
                                    b.phoneNumber
                            from wapp_chat a left join clientes b on a.de = REPLACE(REPLACE(REPLACE(REPLACE(b.phoneNumber, '(', ''), ')', ''), '-', ''), ' ', '') or a.para = REPLACE(REPLACE(REPLACE(REPLACE(b.phoneNumber, '(', ''), ')', ''), '-', ''), ' ', '') 
                            group by agrupar 
                            order by ordem desc 
                            limit 100";
                  $result = mysqli_query($con, $query);
                  $k = 1;
                  while($d = mysqli_fetch_object($result)){

                    list($mensagem, $data, $tipo) = explode("^",$d->mensagem);

                    switch($tipo){
                      case 'text':{
                          $mensagem = $mensagem;
                        break;
                      }
                      case 'audio':{
                          $mensagem = "Mensagem de áudio";
                        break;
                      }
                      default:{
                        $mensagem = false;
                      }
                    }

                ?>
                <tr selecionarChat="<?=$d->cod_cliente?>">

                  <td>
                    <div class="d-flex justify-content-between">
                      <div class="p-2" style="font-size:12px;"><i class="fa-solid fa-user"></i> <?=(($d->nome)?:"<span class='text-danger'>Sem Identificação</span>")?></div>
                      <div class="p-2" style="font-size:12px;"><i class="fa-solid fa-mobile-screen-button"></i> <?=(($d->phoneNumber)?:"<span class='text-danger'>Não Registrado</span>")?></div>
                    </div>
                    <div class="d-flex justify-content-between">
                      <i class="fa-regular fa-comment" style = "-moz-transform: scaleX(-1); 
                                                                -o-transform: scaleX(-1); 
                                                                -webkit-transform: scaleX(-1); 
                                                                transform: scaleX(-1);"
                                                                ></i>
                      <div class="p-2 w-100" style="font-size:12px; color:#a1a1a1;">
                        <span style="color:#333"><?=$mensagem?></span><br><?=dataBr($data)?>
                      </div>
                    </div>
                  </td>


                </tr>
                <?php
                $k++;
                  }
                ?>
              </tbody>
            </table>
                </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>


<script>
    $(function(){
        Carregando('none');

        $("tr[selecionarChat]").click(function(){
            $("tr[selecionarChat]").removeClass("wapp-select");
            $(this).addClass("wapp-select");
            Carregando();
            mensagens = $(this).attr("selecionarChat");
            $.ajax({
                url:"financeira/wapp/wapp.php",
                type:"POST",
                data:{
                  mensagens
                },
                success:function(dados){
                  $(".mensagensaWapp").html(dados);
                }
            })

        })
 
    })
</script>