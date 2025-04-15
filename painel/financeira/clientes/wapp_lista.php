<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");

?>
<style>
  .legenda_status{
    /* border-left:5px solid;
    border-left-color:green; */
  }
  .Titulo<?=$md5?>{
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }
    tr{
        cursor:pointer;
    }

</style>

<h4 class="Titulo<?=$md5?>">Últimas Conversas</h4>


<div class="col">
  <div class="m-3">

    <div class="row">
      <div class="col">
        <div class="card">
          <h5 class="card-header">Lista de Clientes </h5>
          <div class="card-body">

            <div class="table-responsive">
            <table class="table table-striped table-hover">
              <!-- <thead>
                <tr>
                  <th scope="col">#</th> -->
                  <!-- <th scope="col">Código</th> -->
                  <!-- <th scope="col">Nome</th>
                  <th scope="col">CPF</th>
                  <th scope="col">Situação</th>
                </tr>
              </thead> -->
              <tbody>
                <?php
                  // $query = "select 
                  //                   max(a.codigo) as cod,
                  //                   (select concat(mensagem,'^',data,'^',tipo) from wapp_chat where max(a.codigo) = codigo) as mensagem,
                  //                   (select data from wapp_chat where max(a.codigo) = codigo) as ordem,
                  //                   IF(a.de = '{$ConfWappNumero}',a.para, a.de) as agrupar,
                  //                   b.nome,
                  //                   b.status_atual as log,
                  //                   b.codigo as cod_cliente,
                  //                   b.phoneNumber
                  //           from wapp_chat a left join clientes b on a.de = REPLACE(REPLACE(REPLACE(REPLACE(b.phoneNumber, '(', ''), ')', ''), '-', ''), ' ', '') or a.para = REPLACE(REPLACE(REPLACE(REPLACE(b.phoneNumber, '(', ''), ')', ''), '-', ''), ' ', '') 
                  //           group by agrupar 
                  //           order by ordem desc 
                  //           limit 100";
                  // teste
                  $query = "select 
                                  a.*,
                                  b.codigo as cliente, 
                                  b.nome, 
                                  b.phoneNumber, 
                                  (select count(*) from wapp_chat where de = REPLACE(REPLACE(REPLACE(REPLACE(b.phoneNumber, '(', ''), ')', ''), '-', ''), ' ', '') and recebida = '0') as qtmsg 
                            from wapp_chat a 
                                  left join clientes b on a.de = REPLACE(REPLACE(REPLACE(REPLACE(b.phoneNumber, '(', ''), ')', ''), '-', ''), ' ', '') 
                            where (a.status = 'n' and a.recebida = '0' and a.de != '{$ConfWappNumero}' and 
                                  a.para = '{$ConfWappNumero}') or 
                                  (a.data like '%".date("Y-m-d")."%' and a.de != '{$ConfWappNumero}') 
                              group by a.de order by qtmsg desc, a.codigo asc";

                  //exit();
                  $result = mysqli_query($con, $query);
                  $k = 1;
                  while($d = mysqli_fetch_object($result)){

                    // $log = json_decode($d->log);

                    // if($log->statusCode and $log->message){
                    //   $situacao = "{$log->statusCode} - {$log->message}";
                    // }else{
                    //   $situacao = "Situação detalhada não identificada";
                    // }
                    

                    // if($log->statusCode and $_POST['filtro'] == 'NC'){
                    //   $situacao = "{$log->statusCode} - {$log->message}";
                    //   $cor="orange";
                    // }else if($log->proposalStatusId and $_POST['filtro'] == 'NC'){
                    //   $situacao = "{$log->proposalStatusId} - {$log->proposalStatusDisplayTitle}";
                    //   if($log->proposalStatusId == 130){
                    //     $cor="green";
                    //   }else{
                    //     $cor="red";
                    //   }
                    // }else if($_POST['filtro'] == 'NC'){
                    //   $situacao = "000 - Cliente sem movimentação";
                    //   $cor="#ccc";
                    // }else if(in_array($log->statusCode, ['200'])){
                    //   $cor="orange";
                    // }else if(!in_array($log->statusCode, ['200']) and $_POST['filtro'] == 'SN'){
                    //   $cor="red";
                    // }else if(in_array($log->statusCode, ['130'])){
                    //   $cor="green";
                    // }else if(in_array($log->statusCode, ['200', '95', '60', '61'])){
                    //   $cor="orange";
                    // }else if(in_array($log->statusCode, ['200', '130', '95', '60', '61'])){
                    //   $cor="red";
                    // }else{
                    //   $cor="red";
                    // }
                    list($mensagem, $data, $tipo) = explode("^",$d->mensagem);

                    switch($d->tipo){
                      case 'text':{
                          $mensagem = $d->mensagem;
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
                <tr selecionarChat="<?=$d->cliente?>">

                  <td>
                    <div class="d-flex justify-content-between">
                      <div class="p-2" style="font-size:12px;"><i class="fa-solid fa-user"></i> <?=(($d->nome)?:"<span class='text-danger'>Sem Identificação</span>")?></div>
                      <div class="p-2" style="font-size:12px;"><i class="fa-solid fa-mobile-screen-button"></i> <?=(($d->phoneNumber)?:"<!--<span class='text-danger'>Não Registrado</span>-->{$d->de}")?></div>
                    </div>
                    <div class="d-flex justify-content-between">
                      <i class="fa-<?=(($d->qtmsg)?'solid':'regular')?> fa-comment <?=(($d->qtmsg)?'text-success':false)?>" style = "-moz-transform: scaleX(-1); 
                                                                -o-transform: scaleX(-1); 
                                                                -webkit-transform: scaleX(-1); 
                                                                transform: scaleX(-1);"
                                                                ></i>
                      <div class="p-2 w-100" style="font-size:12px; color:#a1a1a1;">
                        <span style="color:#333"><?=$mensagem?></span><br><?=dataBr($data)?>
                      </div>
                    </div>
                  </td>




                  <!-- <td><?=$k?></td>-->
                  <!-- <td><?=$d->cod_cliente?></td> -->
                  <!-- <td><?=$d->nome?></td>
                  <td><?=$d->cpf?></td>
                  <td class="legenda_status" style="border-left-color:<?=$cor?>;">
                    <?=$situacao?>
                  </td>  -->


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
            Carregando();
            mensagens = $(this).attr("selecionarChat");
            $.ajax({
                url:"financeira/clientes/wapp.php",
                type:"POST",
                data:{
                  mensagens
                },
                success:function(dados){
                  $(".LateralDireita").html(dados);
                }
            })

        })
 
    })
</script>