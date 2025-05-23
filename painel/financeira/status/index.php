<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/capitalam/painel/lib/includes.php");


        if($_POST['situacao']){
          $query = "update status set situacao = '{$_POST['opc']}' where codigo = '{$_POST['situacao']}'";
          mysqli_query($con, $query);
          exit();
        }

?>
<style>
  .legenda_status{
    border-left:5px solid;
    border-left-color:green;
  }
</style>
<div class="col">
  <div class="m-3">

    <div class="row">
      <div class="col">
        <div class="card">
          <h5 class="card-header">Lista de Status</h5>


          <div class="card-body">


            <div class="table-responsiveXXX">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Código</th>
                  <th scope="col">Descrição</th>
                  <th scope="col">Situação</th>
                  <th style="width:60px;">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php

                  $query = "select 
                                  *
                            from status 
                            order by status";
                  // if($_SESSION['ProjectPainel']->codigo == 2) echo $query;
                  $result = mysqli_query($con, $query);
                  $k = 1;
                  while($d = mysqli_fetch_object($result)){

                ?>
                <tr>
                  <td><?=$d->codigo?></td>
                  <td><?=$d->status?></td>
                  <td><?=$d->descricao?></td>
                  <td>
                    <div class="form-check form-switch">
                      <input class="form-check-input situacao" type="checkbox" <?=(($d->situacao)?'checked':false)?> situacao="<?=$d->codigo?>">
                    </div>
                  </td>
                  <td>
                    <button
                      class="btn btn-primary"
                      style="margin-bottom:1px"
                      conf="<?=$d->codigo?>"
                      data-bs-toggle="offcanvas"
                      href="#offcanvasDireita"
                      role="button"
                      aria-controls="offcanvasDireita"
                    >
                      Configurações
                    </button>
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

        $("button[conf]").click(function(){
            cod = $(this).attr("conf");
            $.ajax({
                url:"financeira/status/conf.php",
                type:"POST",
                data:{
                  cod
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })
        })


        $(".situacao").change(function(){

            situacao = $(this).attr("situacao");
            opc = false;

            if($(this).prop("checked") == true){
              opc = '1';
            }else{
              opc = '0';
            }


            $.ajax({
                url:"financeira/status/index.php",
                type:"POST",
                data:{
                    situacao,
                    opc
                },
                success:function(dados){
                    // $("#paginaHome").html(dados);
                }
            })

        });


    })
</script>