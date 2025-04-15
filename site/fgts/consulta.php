<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");

    if($_POST['codUsr']) $_SESSION['codUsr'] = $_POST['codUsr'];

    function numero($v){
        $remove = [" ","/","-",".","(",")"];
        return str_replace($remove, false, $v);
    }

    function consulta_logs($dados){
        global $_SESSION;
        global $con;
        mysqli_query($con, "update consultas_log set ativo = '0' where cliente = '{$_SESSION['codUsr']}'");
        $query = "insert into consultas_log set 
                                            consulta = '{$dados['proposta']}',
                                            cliente = '{$_SESSION['codUsr']}',
                                            data = NOW(),
                                            sessoes = '".json_encode($_SESSION)."',
                                            log = '{$dados['consulta']}',
                                            log_unico = '".md5($dados['consulta'].$dados['proposta'])."',
                                            ativo = '1'";

        $result = sisLog( $query);

        mysqli_query($con, "update clientes set status_atual = '{$dados['consulta']}' where codigo = '{$_SESSION['codUsr']}'");
    }

    $vctex = new Vctex;

    $query = "select *, api_vctex_dados->>'$.token.accessToken' as token from configuracoes where codigo = '1'";
    $result = sisLog( $query);
    $d = mysqli_fetch_object($result);

    $token = $d->token;
    $tabela_padrao = $d->api_vctex_tabela_padrao;

    $agora = time();

    if($agora > $d->api_expira){
        $retorno = $vctex->Token();
        $dados = json_decode($retorno);
        if($dados->statusCode == 200){
            $tabelas = $vctex->Tabelas($dados->token->accessToken);
            $token = $dados->token->accessToken;
            sisLog( "update configuracoes set api_vctex_expira = '".($agora + $dados->token->expires)."', api_vctex_dados = '{$retorno}', api_vctex_tabelas = '{$tabelas}' where codigo = '1'");
        }else{
            $tabelas = 'error';
        }
    }


    if($_POST['acao'] == 'atualiza_proposta'){

        $consulta = $vctex->Conculta([
            'token' => $token,
            'proposalId' => $_POST['proposalId']
        ]);

        $retorno = json_decode($consulta);
        $status_cod = $retorno->proposalStatusId;
        $status_msg = $retorno->proposalStatusDisplayTitle;
        $verifica = mysqli_num_rows(mysqli_query($con, "select * from consultas_log where log_unico = '".md5($consulta.$_POST['atualiza_proposta'])."'"));
        if(!$verifica){
        consulta_logs([
            'proposta' => $_POST['atualiza_proposta'],
            'consulta' => $consulta
        ]);
        }

        $query = "update `consultas` set 
                                        proposta = JSON_SET(proposta, '$.statusCode', '{$status_cod}'),
                                        proposta = JSON_SET(proposta, '$.message', '{$status_msg}')
                        where codigo = '{$_POST['atualiza_proposta']}'";

        $result = sisLog( $query);

    }


    //if($_POST['acao'] == 'simulacao'){ //Simulação direta

        $query = "select * from clientes where codigo = '{$_SESSION['codUsr']}'";
        $result = sisLog( $query);
        $d = mysqli_fetch_object($result);

        //$tabela_padrao = $tabela_padrao;
        $tabela_escolhida = $tabela_padrao;

        $simulacao = $vctex->Simular([
            'token' => $token,
            'cpf' => str_replace(['-',' ','.'],false,trim($d->cpf)),
            'tabela' => $tabela_padrao
        ]);
        
        $verifica = json_decode($simulacao);
        // var_dump($verifica);
        if($verifica->data->isExponentialFeeScheduleAvailable == true and $verifica->statusCode == 200){

            $simulacao = $vctex->Simular([
                'token' => $token,
                'cpf' => str_replace(['-',' ','.'],false,trim($d->cpf)),
                'tabela' => 0
            ]);

            $tabela_padrao = 0;

        }


        $consulta = uniqid();


        $query = "insert into consultas set 
                                            consulta = '{$consulta}',
                                            operadora = 'VCTEX',
                                            cliente = '{$_SESSION['codUsr']}',
                                            data = NOW(),
                                            tabela_escolhida = '{$tabela_escolhida}',
                                            tabela = '{$tabela_padrao}',
                                            dados = '{$simulacao}'
                                            ";
        mysqli_query($con, $query);
        $proposta = mysqli_insert_id($con);
        $verifica = mysqli_num_rows(mysqli_query($con, "select * from consultas_log where log_unico = '".md5($simulacao.$proposta)."'"));
        if(!$verifica){
        consulta_logs([
            'proposta' => $proposta,
            'consulta' => $simulacao
        ]);
        }

        // exit();

    //} //Fim da simulação direta

    if($_POST['acao'] == 'proposta'){

        $query = "select 
                        b.*,
                        a.tabela,
                        a.dados->>'$.data.financialId' as financialId
                    from consultas a
                         left join clientes b on a.cliente = b.codigo
                    where a.codigo = '{$_POST['proposta']}'";
        $result = sisLog( $query);
        $d = mysqli_fetch_object($result);

        $proposta = $vctex->Credito([
            'token' => $token,
            'json' => "{
                            \"feeScheduleId\": {$d->tabela},
                            \"financialId\": \"{$d->financialId}\",
                            \"borrower\": {
                            \"name\": \"".trim($d->nome)."\",
                            \"cpf\": \"".numero($d->cpf)."\",
                            \"birthdate\": \"{$d->birthdate}\",
                            \"gender\": \"{$d->gender}\",
                            \"phoneNumber\": \"".numero($d->phoneNumber)."\",
                            \"email\": \"".trim($d->email)."\",
                            \"maritalStatus\": \"{$d->maritalStatus}\",
                            \"nationality\": \"".trim($d->nationality)."\",
                            \"naturalness\": \"".trim($d->naturalness)."\",
                            \"motherName\": \"".trim($d->motherName)."\",
                            \"fatherName\": \"".trim($d->fatherName)."\",
                            \"pep\": {$d->pep}
                            },
                            \"document\": {
                            \"type\": \"{$d->document_type}\",
                            \"number\": \"".numero($d->document_number)."\",
                            \"issuingState\": \"".trim($d->document_issuingState)."\",
                            \"issuingAuthority\": \"".trim($d->document_issuingAuthority)."\",
                            \"issueDate\": \"{$d->document_issueDate}\"
                            },
                            \"address\": {
                            \"zipCode\": \"".numero($d->address_zipCode)."\",
                            \"street\": \"".trim($d->address_street)."\",
                            \"number\": \"".trim($d->address_number)."\",
                            \"complement\": null,
                            \"neighborhood\": \"".trim($d->address_neighborhood)."\",
                            \"city\": \"".trim($d->address_city)."\",
                            \"state\": \"".trim($d->address_state)."\"
                            },
                            \"disbursementBankAccount\": {
                            \"bankCode\": \"".numero($d->bankCode)."\",
                            \"accountType\": \"".numero($d->accountType)."\",
                            \"accountNumber\": \"".numero($d->accountNumber)."\",
                            \"accountDigit\": \"".numero($d->accountDigit)."\",
                            \"branchNumber\": \"".numero($d->branchNumber)."\"
                            }
                        }"
        ]);
        $verifica = mysqli_num_rows(mysqli_query($con, "select * from consultas_log where log_unico = '".md5($proposta.$_POST['proposta'])."'"));
        if(!$verifica){
        consulta_logs([
            'proposta' => $_POST['proposta'],
            'consulta' => $proposta
        ]);
        }

        $query = "update consultas set 
                    proposta = '{$proposta}'
                    where codigo = '{$_POST['proposta']}'
                ";
        sisLog( $query);

    }


    $query = "select * from clientes where codigo = '{$_SESSION['codUsr']}'";
    $result = sisLog( $query);
    $cliente = mysqli_fetch_object($result);
    $dC = $cliente;

    $pendentes = json_decode($cliente->campos_pendentes);
    if($pendentes) $pendentes = "- ".implode("<br>- ", $pendentes);

?>
<style>
    .card{
        border-color:#1c4a9b,
    }
    .card-header{
        background-color:#1c4a9b;
        color:#fff;
    }
    .card-title{
        font-weight:bold;
        color:#1c4a9b;
    }
    .card-text{
        color:#1c4a9b;
    }

    .coluna{
        margin-bottom:5px;
    }
    .coluna label{
        font-size:12px;
        color:#a1a1a1;
    }
    .coluna div{
        font-size:14px;
        color:#333;
    }

    .botao-capital{
    background: #ffffff;
                border: 0;
                padding: 13px 70px;
                color: #144397;
                transition: 0.4s;
                border-radius: 25px;
                border-left: 10px #144397 solid;
                border-right: #144397 10px solid;
                border-top: #144397 solid 1px;
                border-bottom: #144397 solid 1px;
                margin-top: 10px;
                margin-bottom:10px;
    }

    .botao-capital:hover{
    background: #144397;
    border: 0;
    padding: 13px 70px;
    color: #ffffff;
    transition: 0.4s;
    border-radius: 25px;
    margin-top: 10px;
    margin-bottom: 10px;
   
}

</style>
<div class="">
<div class=" col-md-6 offset-md-3 card border-0" style=" padding:10px">
  <h2 class="" style="text-align:center;font-family: 'Montserrat', sans-serif!important;">Consulta - FGTS</h2>
  <div class="card-body">
    <h5 class="card-title">
        <div class="d-flex justify-content-between">
            <span>Simulações /Propostas</span>
        </div>
        <!--

        <div class="row mt-3">
            <div class="col">
                <div class=""
                style="cursor: pointer;;border-radius: 15px;border: solid 1px #144397;padding:18px;background: #144397;color:#fff;text-align:center"
                 <?=(($cliente->cadastro_percentual < 100)?'pendentes':'simulacao')?>>
                    <div >
                        Clique aqui para consultar o seu saldo FGTS
                    </div>
                </button>
            </div>
        </div>
        
        -->
    </h5>
    <div class="card-text" style="min-height:300px;">

    <?php

    $query = "select *, dados->>'$.statusCode' as simulacao, proposta->>'$.statusCode' as status_proposta from consultas where cliente = '{$_SESSION['codUsr']}' order by codigo desc limit 1";
    $result = sisLog( $query);
    while($d = mysqli_fetch_object($result)){


        $dados = json_decode($d->dados);
        

        //Envio das mensagens para o WhatsApp
        $q1 = "select a.* from status_mensagens a left join status b on a.status = b.codigo where b.unico = '".md5($dados->statusCode.$dados->message)."' limit 1";
        $r1 = mysqli_query($con, $q1);
        while($d1 = mysqli_fetch_object($r1)){

            $msg = trim(str_replace(["\n","\r"],"\\n",$d1->mensagem));

            if($d1->tipo == 'img'){
                $wgw = new wgw;
                $wgw->SendAnexo([
                'mensagem'=>"https://painel.capitalsolucoesam.com.br/volume/wapp/status/{$d1->status}/{$d1->arquivo}",
                'tipo'=>'image',
                'type'=>"image/png",
                'name'=>'',
                'para'=>'55'.numero($dC->phoneNumber)
                ]);
            }else{
                $wgw = new wgw;
                $wgw->SendTxt([
                'mensagem'=>$msg,
                'para'=>'55'.numero($dC->phoneNumber)
                ]);
            }
        }
        //Fin dos envios das mensagens para o whatsapp



        

        $q = "select * from configuracoes where codigo = '1'";
        $r = sisLog( $q);
        $t = mysqli_fetch_object($r);
        $tab = json_decode($t->api_vctex_tabelas);
        foreach($tab->data as $i => $v){
            if($v->id == $d->tabela_escolhida) $tabela_sugerida = $v->name;
            if($v->id == $d->tabela) $tabela_resultado = $v->name;
        }

        if($dados->statusCode == 200 and $dados->data->simulationData->installments){
    ?>
        <iframe src="status.php?local=simulacao&status=true" style="position:fixed; left=-1000px; top:-1000px;"></iframe>

        <div class="card mb-2 border-<?=(($d->status_proposta and $d->status_proposta < 400)?'success':'primary')?>">
            <div class="card-header bg-<?=(($d->status_proposta and $d->status_proposta < 400)?'success':'primary')?> text-white">
            <?=(($d->status_proposta and $d->status_proposta < 400)?'PROPOSTA':'SIMULAÇÃO')?> - <b style='color: #ffffff;font-size:17px'> <?=strtoupper($d->consulta)?></b>
            </div>

            <div class="row m-1">
                <div class="col-md-4">
                    <div class="coluna">
                        <label>Tabela Sugerida</label>
                        <div><?=$tabela_sugerida?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="coluna">
                        <label>Resultado da Tabela</label>
                        <div><?=$tabela_resultado?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="coluna">
                        <label>Liberado</label>
                        <h1 class="text-primary">R$ <?=number_format($dados->data->simulationData->totalReleasedAmount,2,',','.')?></h1>
                    </div>
                </div>
            </div>

            <div class="row m-1">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between">
                        <div class="coluna"><label>Período</label></div>
                        <div class="coluna"><label>Valor</label></div>
                    </div>
                    <?php
                    foreach($dados->data->simulationData->installments as $periodo => $valor){
                    ?>
                    <div class="d-flex justify-content-between">
                        <div class="coluna"><div><?=dataBr($valor->dueDate)?></div></div>
                        <div class="coluna"><div>R$ <?=number_format($valor->amount,2,',','.')?></div></div>
                    </div>
                    <?php                       
                    }
                    ?>
                </div>
            </div>


            <div class="row m-1">
                <div class="col-md-3">
                    <div class="coluna">
                        <label>Data operação</label>
                        <div><?=dataBR($d->data)?></div>
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="coluna">
                        <label>IOF</label>
                        <div><?=$dados->data->simulationData->iofAmount?></div>
                    </div>
                </div>

                <!-- <div class="col-md-1">
                    <div class="coluna">
                        <label>Liberado</label>
                        <div><?=$dados->data->simulationData->totalReleasedAmount?></div>
                    </div>
                </div> -->

                <div class="col-md-1">
                    <div class="coluna">
                        <label>Emissão</label>
                        <div><?=$dados->data->simulationData->totalAmount?></div>
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="coluna">
                        <label>TAC</label>
                        <div><?=$dados->data->simulationData->contractTACAmount?></div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="coluna">
                        <label>CET anual</label>
                        <div><?=$dados->data->simulationData->contractCETRate?></div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="coluna">
                        <label>Taxa anual</label>
                        <div><?=$dados->data->simulationData->contractRate?></div>
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="coluna">
                        <label>Mínimo</label>
                        <div><?=$dados->data->simulationData->minDisbursedAmount?></div>
                    </div>
                </div>

            </div>



            
            <?php
                if(!$d->status_proposta or $d->status_proposta >= 400){
                /*
            ?>
            <button proposta="<?=$d->codigo?>" class="btn btn-warning btn-sm m-1">
                Solicitar proposta para esta simulação
            </button>
            <?php
            //*/
                if($d->status_proposta){
                    $proposta = json_decode($d->proposta);
            ?>
                <div class="alert alert-danger m-1" role="alert">
                    <?="{$proposta->statusCode} - {$proposta->message}"?>
                </div>
            <?php
                }

                }else{

                    $proposta = json_decode($d->proposta);
            ?>

            <div class="row m-1">
                <div class="col-md-6">
                    <div class="coluna">
                        <label>Número do Contrato</label>
                        <div><?=$proposta->data->proposalcontractNumber?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="coluna">
                        <label>Status</label>
                        <div><?="{$proposta->statusCode} - {$proposta->message}"?></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="m-1">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-link"></i></span>
                            <div class="form-control">
                                <div style="overflow: hidden; border:1px; white-space:nowrap;">
                                    <?=$proposta->data->formalizationLink?>
                                </div>
                            </div>
                            <button class="btn btn-outline-secondary" type="button" id="button-addon1" data-bs-toggle="tooltip" data-bs-placement="top" title="Copiar o link" copiar="<?=$proposta->data->formalizationLink?>"><i class="fa-solid fa-copy"></i></button>
                            <a href="<?=$proposta->data->formalizationLink?>" target="_blank" class="btn btn-outline-secondary" type="button" id="button-addon1" data-bs-toggle="tooltip" data-bs-placement="top" title="Enviar link por whatsApp" wapp="<?=$d->codigo?>"><i class="fa-solid fa-up-right-from-square"></i></a>
                            <!-- <button class="btn btn-outline-secondary" type="button" id="button-addon1" data-bs-toggle="tooltip" data-bs-placement="top" title="Enviar link por SMS" sms="<?=$d->codigo?>" disabled><i class="fa-solid fa-comment-sms"></i></button>
                            <button class="btn btn-outline-secondary" type="button" id="button-addon1" data-bs-toggle="tooltip" data-bs-placement="top" title="Enviar link por e-mail" email="<?=$d->codigo?>" disabled><i class="fa-solid fa-at"></i></button>
                            <button class="btn btn-outline-secondary" type="button" id="button-addon1" data-bs-toggle="tooltip" data-bs-placement="top" title="Atualizar Status da proposta" proposalId="<?=$proposta->data->proposalId?>" atualiza_proposta="<?=$d->codigo?>"><i class="fa-solid fa-rotate"></i></button> -->
                        </div> 
                        <p style="font-size:12px; text-align:center; color:#333;">Acesse o linque acima utilizando os botões de copiar o link (<i class="fa-solid fa-copy"></i>) ou abrir o link (<i class="fa-solid fa-up-right-from-square"></i>) para a finalização do seu contrato.</p>          
                    </div>         
                </div>
            </div>

            <?php
                }
            ?>
        </div>

    <?php
        }else{
    ?>
        <iframe src="status.php?local=simulacao&status=false" style="position:fixed; left=-1000px; top:-1000px;"></iframe>

    <div class="card mb-2 border-danger">
        <div class="card-header bg-danger text-white">
            SIMULAÇÃO - <?=strtoupper($d->consulta)?>
        </div>

        <div class="row m-1">
            <div class="col-md-4">
                <div class="coluna">
                    <label>Data da Operação</label>
                    <div><?=dataBR($d->data)?></div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="coluna">
                    <label>Erro - Descrição</label>
                    <div><?="{$dados->statusCode} - {$dados->message}"?></div>
                </div>                
            </div>
        </div>

    </div>
    <?php
        }
    }
    ?>
    </div>
  </div>

  <div class="mt-2" style="margin-bottom:15px;text-align:center">
        <a class=" botao-capital " style="width:100%" href="#" local="fgts/cadastro.php"><i class="fa-solid fa-angles-right"></i> Cadastrar Proposta</a>
    </div>


  <!-- <button class="btn btn-primary btn-sm atualiza">Atualizar</button> -->
    <div class="m-3 text-end">
        <a class="text-danger text-decoration-none sair" style="cursor:pointer"><i class="fa-solid fa-right-from-bracket"></i> Sair do login</a>
    </div>
</div>
</div>

<script>
    $(function(){

        <?php
            include("barra_status.php");
        ?>

        $(".atualiza").click(function(){
            $.ajax({
                url:"fgts/consulta.php",
                success:function(dados){
                    $(".palco").html(dados);
                }
            })
        })

        // Carregando('none');

        // var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        // var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        //     return new bootstrap.Tooltip(tooltipTriggerEl)
        // })

        $("button[copiar]").click(function(){
            obj = $(this);
            texto = $(this).attr("copiar");
            CopyMemory(texto);
            obj.removeClass('btn-outline-secondary');
            obj.addClass('btn-outline-success');
        });
      

        $("button[atualiza_proposta]").click(function(){

            proposalId = $(this).attr("proposalId");
            atualiza_proposta = $(this).attr("atualiza_proposta");

            // Carregando();

            $.ajax({
                url:"fgts/consulta.php",
                type:"POST",
                data:{
                    acao:'atualiza_proposta',
                    proposalId,
                    atualiza_proposta
                },
                success:function(dados){
                    $(".palco").html(dados);
                }
            })            
            

        })    





        $("div[simulacao]").click(function(){

            $.confirm({
                title:"Simulação",
                content:"Confirma a solicitação para simulação?",
                type:"orange",
                buttons:{
                    'sim':{
                        text:'Sim',
                        btnClass:'btn btn-success btn-sm',
                        action:function(){
                            // Carregando();

                            $.ajax({
                                url:"fgts/consulta.php",
                                type:"POST",
                                data:{
                                    acao:'simulacao'
                                },
                                success:function(dados){
                                    $(".palco").html(dados);
                                },
                                error:function(){
                                    alert('Erro')
                                }
                            })  
                        }
                    },
                    'nao':{
                        text:'Não',
                        btnClass:'btn btn-danger btn-sm',
                        action:function(){
                            
                        }
                    }
                }
            })

        })  


        $("div[pendentes]").click(function(){

            $.alert({
                title:"Pendência no Cadastro",
                content:"Favor retornar a tela de cadastro e completar os dados pendentes:<br><br><p style='color:red'><?=$pendentes?></p>",
                type:"orange",
            })

        }) 


        $("button[proposta]").click(function(){

            proposta = $(this).attr("proposta");

            $.confirm({
                title:"Proposta",
                content:"Confirma a solicitação de proposta?",
                type:"orange",
                buttons:{
                    'sim':{
                        text:'Sim',
                        btnClass:'btn btn-success btn-sm',
                        action:function(){
                            // Carregando();

                            $.ajax({
                                url:"fgts/consulta.php",
                                type:"POST",
                                data:{
                                    acao:'proposta',
                                    proposta
                                },
                                success:function(dados){
                                    $(".palco").html(dados);
                                },
                                error:function(){
                                    alert('Erro')
                                }
                            })  
                        }
                    },
                    'nao':{
                        text:'Não',
                        btnClass:'btn btn-danger btn-sm',
                        action:function(){
                            
                        }
                    }
                }
            })

        })

        $("a[local]").click(function(){

        url = $(this).attr("local");

            $.ajax({
                url,
                success:function(dados){
                    $(".palco").html(dados);
                }
            })

        })

        $(".sair").click(function(){
            telefone = $("#telefone").val();
            
            $.confirm({
                title:"Sair do Login",
                content:'Deseja realmente sair do login da sua área restrita?',
                type:'orange',
                buttons:{
                    'Sim':{
                        text:'SIM',
                        btnClass:'btn btn-danger btn-sm',
                        action:function(){
                            localStorage.removeItem("codUsr");
                            $.ajax({
                                url:"fgts/sessao.php",
                                data:{
                                    codUsr:''
                                },
                                type:"POST",
                                success:function(dados){

                                    $.ajax({
                                        url:"fgts/home.php",
                                        success:function(dados){
                                            $(".palco").html(dados);
                                        }
                                    })

                                }
                            });


                        }
                    },
                    'não':{
                        text:'NÃO',
                        btnClass:'btn btn-primary btn-sm',
                        action:function(){

                        }
                    },
                    
                }
            })

            


        })

        $.ajax({
            url:"assets/lib/log_acessos.php",
            success:function(dados){
            //Retorno da função
            // console.log(dados);
            }
        });
    })
</script>