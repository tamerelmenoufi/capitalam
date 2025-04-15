<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");


        if($_POST['deletar']){
          $query = "delete from bancos where codigo = '{$_POST['deletar']}'";
          mysqli_query($con, $query);
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
          <h5 class="card-header">Lista de Bancos</h5>


          <div class="card-body">

          <div style="display:flex; justify-content:end">
                <button
                    novoCadastro
                    class="btn btn-success"
                    data-bs-toggle="offcanvas"
                    href="#offcanvasDireita"
                    role="button"
                    aria-controls="offcanvasDireita"
                ><i class="fa-regular fa-file"></i> Novo</button>
            </div>


            <div class="table-responsiveXXX">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th scope="col">Banco</th>
                  <th style="width:180px;">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php

                  $query = "select 
                                  *
                            from bancos 
                            order by banco";
                  // if($_SESSION['ProjectPainel']->codigo == 2) echo $query;
                  $result = mysqli_query($con, $query);
                  $k = 1;
                  while($d = mysqli_fetch_object($result)){

                ?>
                <tr>
                  <td><?=$d->banco?></td>
                  <td style="text-align:right">
                    <button
                      class="btn btn-primary"
                      style="margin-bottom:1px"
                      editar="<?=$d->codigo?>"
                      data-bs-toggle="offcanvas"
                      href="#offcanvasDireita"
                      role="button"
                      aria-controls="offcanvasDireita"
                    >
                      Editar
                    </button>
                    <button
                      class="btn btn-danger"
                      style="margin-bottom:1px"
                      deletar="<?=$d->codigo?>"
                    >
                      Deletar
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

        $("button[editar]").click(function(){
            cod = $(this).attr("editar");
            $.ajax({
                url:"financeira/bancos/form.php",
                type:"POST",
                data:{
                  cod
                },
                success:function(dados){
                  $(".LateralDireita").html(dados);
                }
            })

        });

        $("button[deletar]").click(function(){
          deletar = $(this).attr("deletar");
          $.confirm({
            title:"Excluir Registro",
            type:"red",
            content:"Deseja realmente excluir o registro?",
            buttons:{
              'Sim':function(){
                $.ajax({
                    url:"financeira/bancos/index.php",
                    type:"POST",
                    data:{
                      deletar
                    },
                    success:function(dados){
                        $("#paginaHome").html(dados);
                    }
                })
              },
              'Não':function(){

              }
            }
          })

        });


        $("button[novoCadastro]").click(function(){
            $.ajax({
                url:"financeira/bancos/form.php",
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })
        })


    })
</script>