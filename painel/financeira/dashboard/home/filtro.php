<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/capitalam/painel/lib/includes.php");

        $periodo = explode("-",$_POST['periodo']);
        if($periodo[2]) $periodo = "{$periodo[2]}/{$periodo[1]}/{$periodo[0]}";
        else $periodo = "{$periodo[1]}/{$periodo[0]}";

        $dicionario = [
            'NC' => 'Novos Cadastros',
            'SR' => 'Simulações Realizadas',
            'SS' => 'Simulações bem Sucedidas',
            'SN' => 'Simulações Negadas',
            'PR' => 'Propostas Realizadas',
            'AP' => 'Antecipação Paga',
            'PP' => 'Propostas com Pendências',
            'PN' => 'Propostas Negadas'
        ];

        $querys = [
            'NC' => "select a.*, a.codigo as cod_cliente, (select log from consultas_log where cliente = a.codigo order by codigo desc limit 1) as log from clientes a where a.data_cadastro like '{$_POST['periodo']}%' order by a.nome asc",
            'SR' => "select a.dados as log, a.data, a.cliente as cod_cliente, b.* from consultas a left join clientes b on a.cliente = b.codigo where a.data like '{$_POST['periodo']}%' order by b.nome asc",
            'SS' => "select a.dados as log, a.data, a.cliente as cod_cliente, b.* from consultas a left join clientes b on a.cliente = b.codigo where a.data like '{$_POST['periodo']}%' and a.dados->>'$.statusCode' = '200' order by b.nome asc",
            'SN' => "select a.dados as log, a.data, a.cliente as cod_cliente, b.* from consultas a left join clientes b on a.cliente = b.codigo where a.data like '{$_POST['periodo']}%' and a.dados->>'$.statusCode' != '200' order by b.nome asc",
            'PR' => "select a.proposta as log, a.data, a.cliente as cod_cliente, b.* from consultas a left join clientes b on a.cliente = b.codigo where a.data like '{$_POST['periodo']}%' and proposta->>'$.statusCode' order by b.nome asc",
            'AP' => "select a.proposta as log, a.data, a.cliente as cod_cliente, b.* from consultas a left join clientes b on a.cliente = b.codigo where a.data like '{$_POST['periodo']}%' and proposta->>'$.statusCode' and proposta->>'$.statusCode' = '130' order by b.nome asc",
            'PP' => "select a.proposta as log, a.data, a.cliente as cod_cliente, b.* from consultas a left join clientes b on a.cliente = b.codigo where a.data like '{$_POST['periodo']}%' and proposta->>'$.statusCode' and proposta->>'$.statusCode' in ('200', '95', '60', '61') order by b.nome asc",
            'PN' => "select a.proposta as log, a.data, a.cliente as cod_cliente, b.* from consultas a left join clientes b on a.cliente = b.codigo where a.data like '{$_POST['periodo']}%' and proposta->>'$.statusCode' not in ('200', '130', '95', '60', '61') order by b.nome asc"
        ];


        if($_POST['download']){

          header('Content-Type: text/csv; charset=UTF-8');
          header('Content-Disposition: attachment; filename="relatorio-'.date("YmdHis").'.csv"');
          header('Pragma: no-cache');
          header('Expires: 0');
      
      
          if($_SESSION['data_inicial'] and $_SESSION['data_final']){
              $where = " and ultimo_acesso between '{$_SESSION['data_inicial']} 00:00:00' and '{$_SESSION['data_final']} 23:59:59'";
          }
      
          echo "Nome;CPF;Telefone;Bancos Autorizados;Último Status\n";
          $query = "select *, status_atual->>'$.message' as status_atual, bancos_autorizados->>'$[*].banco' as bancos  from clientes where 1 {$where}";
          $query = $querys[$_POST['filtro']]." limit 100";
          $result = mysqli_query($con, $query);
          while($d = mysqli_fetch_object($result)){
              echo "{$d->nome};{$d->cpf};{$d->phoneNumber};{$d->bancos};{$d->status_atual}\n";
          }
          
          exit();

        }


?>
<style>
  .legenda_status{
    border-left:5px solid;
    border-left-color:green;
  }
  .Titulo<?=$md5?>{
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }

</style>

<h4 class="Titulo<?=$md5?>"><?=$dicionario[$_POST['filtro']]?></h4>


<div class="col">
  <div class="m-3">

    <div class="row">
      <div class="col">
        <div class="card">
            <h5 class="card-header">
              <div class="d-flex justify-content-between">
                Período de <?=$periodo?>
                <span>D</span>
              </div> 
            </h5>
          </div>
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
                  $query = $querys[$_POST['filtro']]." limit 100";
                  $result = mysqli_query($con, $query);
                  $k = 1;
                  while($d = mysqli_fetch_object($result)){

                    $log = json_decode($d->log);

                    if($log->statusCode and $log->message){
                      $situacao = "{$log->statusCode} - {$log->message}";
                    }else{
                      $situacao = "Situação detalhada não identificada";
                    }
                    

                    if($log->statusCode and $_POST['filtro'] == 'NC'){
                      $situacao = "{$log->statusCode} - {$log->message}";
                      $cor="orange";
                    }else if($log->proposalStatusId and $_POST['filtro'] == 'NC'){
                      $situacao = "{$log->proposalStatusId} - {$log->proposalStatusDisplayTitle}";
                      if($log->proposalStatusId == 130){
                        $cor="green";
                      }else{
                        $cor="red";
                      }
                    }else if($_POST['filtro'] == 'NC'){
                      $situacao = "000 - Cliente sem movimentação";
                      $cor="#ccc";
                    }else if(in_array($log->statusCode, ['200'])){
                      $cor="orange";
                    }else if(!in_array($log->statusCode, ['200']) and $_POST['filtro'] == 'SN'){
                      $cor="red";
                    }else if(in_array($log->statusCode, ['130'])){
                      $cor="green";
                    }else if(in_array($log->statusCode, ['200', '95', '60', '61'])){
                      $cor="orange";
                    }else if(in_array($log->statusCode, ['200', '130', '95', '60', '61'])){
                      $cor="red";
                    }else{
                      $cor="red";
                    }

                ?>
                <tr>

                  <td>
                    <div class="d-flex justify-content-between">
                      <div class="p-2" style="font-size:12px;"><i class="fa-solid fa-user"></i> <?=(($d->nome)?:"<span class='text-danger'>Sem Identificação</span>")?></div>
                      <div class="p-2" style="font-size:12px;"><i class="fa-solid fa-id-card"></i> <?=(($d->cpf)?:"<span class='text-danger'>000.000.000-00</span>")?> (<?=$d->cod_cliente?>)</div>
                    </div>
                    <div class="d-flex justify-content-between">
                      <div class="legenda_status p-2" style="border-left-color:<?=$cor?>; font-size:12px; color:#a1a1a1;">
                        <?=$situacao?><br><?=dataBr($d->data)?>
                        <?=((in_array($log->statusCode, ['200']) and $_POST['filtro'] == 'SS')?"<br><span class='text-success'>Saldo encontrado R$ ".number_format($log->data->simulationData->totalReleasedAmount,2,',','.')."</span>":false)?>
                      </div>
                      <div>
                        <div style="width:70px;">
                          <button eventos="<?=$d->codigo?>" class="btn btn-warning btn-sm me-1"><i class="fa-solid fa-clipboard-list"></i></button>
                          <!-- <button chat="<?=$d->codigo?>" class="btn btn-success btn-sm"><i class="fa-brands fa-whatsapp"></i></button> -->
                        </div>
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


<?php

      $retorno = [
        'url' => 'financeira/dashboard/home/filtro.php',
        'local' => '.LateralDireita',
        'data' => ['filtro' => $_POST['filtro'], 'periodo' => $_POST['periodo']]
      ];

      $retorno = json_encode($retorno);
      $retorno = base64_encode($retorno);

?>

<script>
    $(function(){
        Carregando('none');

        $("button[eventos]").click(function(){
            cliente = $(this).attr("eventos");
            $.ajax({
                url:"financeira/clientes/logs.php",
                type:"POST",
                data:{
                  cliente,
                  retorno:'<?=$retorno?>',
                },
                success:function(dados){
                  $(".LateralDireita").html(dados);
                }
            })
        })

        $("button[chat]").click(function(){
            mensagens = $(this).attr("chat");
            $.ajax({
                url:"financeira/clientes/wapp.php",
                type:"POST",
                data:{
                  mensagens,
                  retorno:'<?=$retorno?>',
                },
                success:function(dados){
                  $(".LateralDireita").html(dados);
                }
            })
        })
 
    })
</script>