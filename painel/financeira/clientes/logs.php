<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");

    $c = mysqli_fetch_object(mysqli_query($con, "select * from clientes where codigo = '{$_POST['cliente']}'"));

    if($_POST['retorno']) $_SESSION['retorno_dados'] = json_decode(base64_decode($_POST['retorno']), true);





    if($_POST['acao'] == 'movimentacao'){

        $status = [
            "message" => $_POST['situacao'],
            "statusCode" => $_POST['situacao_cod'],
            "statusCodigo" => $_POST['situacao_codigo'],
            "banco" => $_POST['banco']
        ];

        $dados = [
            'banco' => $_POST['banco'],
            'valor' => $_POST['valor'],
            'status' => $status,
            'descricao' => $_POST['descricao'],
        ];

        mysqli_query($con, "update consultas_log set ativo = '0' where cliente = '{$_POST['cliente']}'");
        
        $query = "insert into consultas_log set 
                                            cliente = '{$_POST['cliente']}',
                                            data = NOW(),
                                            sessoes = '".json_encode($_SESSION)."',
                                            log = '".addslashes(json_encode($dados))."',
                                            log_unico = '".md5(json_encode($_SESSION).json_encode($dados).date("YmdHis"))."',
                                            ativo = '1'";

        $result = mysqli_query($con, $query);

        mysqli_query($con, "update clientes set status_atual = '".addslashes(json_encode($status))."', ultimo_acesso = NOW() where codigo = '{$_POST['cliente']}'");

    }




    // if($_POST['detalhes']){
    //     $detalhes = json_decode(base64_decode($_POST['detalhes']));
    //     $detalhes = json_encode($detalhes, JSON_PRETTY_PRINT);
    //     echo "{$detalhes}";
    //     exit();
    // }
?>
<style>
    .Titulo<?=$md5?>{
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }
    .identificacao<?=$md5?>{
        position:absolute;
        left:20px;
        top:60px;
        z-index:0;        
    }
    <?php
    $h = 260;
    ?>
    .logs<?=$md5?>{
        position:absolute;
        left:0;
        width:100%;
        top:130px;
        padding:10px;
        bottom:<?=$h?>px;
        overflow:auto;
        z-index:0;
        retorno_url
    .form<?=$md5?>{
        position:absolute;
        padding-top:10px;
        width:100%;
        left:0;
        bottom:0;
        height:<?=$h?>px;
        padding:10px;
        z-index:0;
        overflow:auto;
        background:#eee;
    }    
</style>
<h4 class="Titulo<?=$md5?>">Eventos</h4>

<?php

    $query = "select a.*, b.banco from consultas_log a left join bancos b on a.log->>'$.status.banco' = b.codigo where a.cliente = '{$_POST['cliente']}' order by data desc";
    $result = mysqli_query($con, $query);

    if(!mysqli_num_rows($result)){
?>

<center>
    <h1 style="color:#a1a1a1; margin-top:100px; text-align:center">Cliente sem Eventos</h1>
</center>

<?php
    }

    while($d = mysqli_fetch_object($result)){
        $sessoes =  json_decode($d->sessoes);
        $log = json_decode($d->log);
        $usuario = false;
        if($sessoes->acao == 'cron'){
            $titulo = "Sistema - Operação automática (Tarefas)";
        }else if($sessoes->ProjectPainel){
             $titulo = "Manual - usuário / Consultores (Painel)";
             $usuario = $sessoes->ProjectPainel->nome;
             $descricao = "Banco {$d->banco}<br>{$log->status->statusCode} - {$log->status->message}".(($log->valor)?" (R$".number_format($log->valor,2,",","."):false).")"; 
        }else if($sessoes->codUsr){
             $titulo = "Cliente - Realizada pela aplicação (Site)";
        }
        if($log->statusCode){
            $descricao = "{$log->statusCode} - {$log->message}".(($log->valor)?number_format($log->valor,2,",","."):false);
            $detalhes = $d->sessoes; //base64_encode($d->log);
        }else if($log->proposalStatusId){
            $descricao = "{$log->proposalStatusId} - {$log->proposalStatusDisplayTitle}".(($log->valor)?number_format($log->valor,2,",","."):false);
            $detalhes = $d->sessoes; //base64_encode($d->log);
        }   
        // echo $d->sessoes;
        // echo $d->log;

?>
    <div class="card mb-3">
    <div class="card-header">
        <?=$titulo?>
    </div>
    <div class="card-body">
        <p class="card-text"><?=$descricao?></p>
        <?php
        if($log->descricao){
        ?>
        <span style="color:#333; font-size:12px;"><?=$log->descricao?></span><br>
        <?php
        }
        if($usuario){
        ?>
        <span style="color:#a1a1a1; font-size:12px;">Atendente: <?=$usuario?></span><br>
        <?php
        }
        ?>
        <span style="color:#a1a1a1; font-size:12px;">Processada em: <?=dataBr($d->data)?></span>
        <?php
        if($d->ativo){
        ?>
        <span class="text-success" style="font-size:12px; margin-left:20px;"><i class="fa-solid fa-check"></i> status atual</span>
        <?php
        }
        ?>
        <!-- <a detalhes="<?=$detalhes?>" class="btn btn-warning btn-sm">Log</a> -->
    </div>
    </div>
<?php
    }
?>
</div>

<div class="form<?=$md5?>">
    <div class="input-group mb-3">
        <label class="input-group-text" for="banco">Banco</label>
        <select class="form-select" id="banco">
            <option value=''>Selecione...</option>
            <?php
            $q = "select * from bancos order by banco asc";
            $r = mysqli_query($con, $q);
            while($s = mysqli_fetch_object($r)){
            ?>
            <option value="<?=$s->codigo?>"><?=$s->banco?></option>
            <?php
            }
            ?>
        </select>

        <label class="input-group-text" for="situacao">Status</label>
        <select class="form-select" id="situacao">
            <option value=''>Selecione...</option>
            <?php
            $q = "select * from status_geral order by descricao asc";
            $r = mysqli_query($con, $q);
            while($s = mysqli_fetch_object($r)){
            ?>
            <option status
                value="<?=$s->codigo?>"
                cod="<?=$s->codigo?>"
                descricao="<?=$s->descricao?>"
            ><?=$s->codigo?> - <?=$s->descricao?></option>
            <?php
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="descricao" class="form-label">Valor</label>
        <input type="text" class="form-control" id="valor" name="valor" />
    </div>


    <div class="mb-3">
        <label for="descricao" class="form-label">Observações</label>
        <textarea class="form-control" id="descricao" rows="3"></textarea>
    </div>

    <?php
    /*
    ?>
    <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Default checkbox
                </label>
            </div>
        </span>
        <input type="date" class="form-control" id="data_agenda" aria-describedby="basic-addon3">
    </div>
    <?php
    //*/
    ?>
    <button salvar type="submit" class="btn btn-primary">Salvar</button>

    <?php
    if($_SESSION['retorno_dados']){
    ?>
    <button retornoUrl type="submit" class="btn btn-primary">Voltar</button>
    <?php
    }
    ?>

</div>

<script>
    $(function(){

        Carregando('none');

          $("button[retornoUrl]").click(function(){
            $.ajax({
                type:"POST",
                data:<?=((json_encode($_SESSION['retorno_dados']['data']))?:'{}')?>,
                url:'<?=$_SESSION['retorno_dados']['url']?>',
                success:function(dados){
                    $('<?=$_SESSION['retorno_dados']['local']?>').html(dados);
                }
            })
        })      


        $("a[detalhes]").click(function(){
            detalhes = $(this).attr("detalhes");
            $.ajax({
                type:"POST",
                data:{
                    detalhes,
                },
                url:"financeira/clientes/logs.php",
                success:function(dados){
                    $.alert({
                        content:dados,
                        title:"Log",
                        type:"blue",
                        columnClass:"col-md-8"
                    })
                }
            })
        })


        $("button[salvar]").click(function(){
            
            banco = $("#banco").val();
            valor = $("#valor").val();
            situacao_codigo = $("#situacao").val();
            situacao_cod = $(`option[status][value="${situacao_codigo}"]`).attr("cod");
            situacao = $(`option[status][value="${situacao_codigo}"]`).attr("descricao");
            descricao = $("#descricao").val();

            if(!banco || !situacao_codigo){

                $.alert({
                    title:"Dados Incompletos",
                    content:"Favor preencher todos os dados obrigatórios (*)!",
                    type:"red"
                })

                return false;
            }

            // console.log(`Situacao Codigo:${situacao_codigo}`)
            // console.log(`Situacao Cod:${situacao_cod}`)
            // console.log(`Situacao:${situacao}`)
            // return false;
            Carregando();
            $.ajax({
                url:"financeira/clientes/logs.php",
                type:"POST",
                data:{
                  cliente:'<?=$_POST['cliente']?>',
                  banco,
                  valor,
                  situacao,
                  situacao_cod,
                  situacao_codigo,
                  descricao,
                  acao:'movimentacao'
                },
                success:function(dados){
                  $(".LateralDireita").html(dados);
                }
            })

        })


    })
</script>