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


    if($_POST['acao'] == 'simulacao'){

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

    }


    if($_POST['acao'] == 'proposta'){

        $estadoCivil = [
            'Solteiro' => 'single',
            'Casado' => 'married',
            'Uniao Estavel' => 'married',
            'Divorciado' => 'divorced',
            'Separado' => 'divorced',
            'Viúvo' => 'widower'
        ];

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
                            \"gender\": \"".((strtolower($d->gender) == 'm')?'male':'female')."\",
                            \"phoneNumber\": \"".numero($d->phoneNumber)."\",
                            \"email\": \"".trim($d->email)."\",
                            \"maritalStatus\": \"".$estadoCivil[$d->maritalStatus]."\",
                            \"nationality\": \"".((trim($d->nationality))?'brazilian':'brazilian')."\",
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
                            ".((numero($d->pixKey))?
                            "\"pixKey\": \"".$d->pixKey."\",
                            \"pixKeyType\": \"".$d->pixKeyType."\"":
                            "\"bankCode\": \"".numero($d->bankCode)."\",
                            \"accountType\": \"".numero($d->accountType)."\",
                            \"accountNumber\": \"".numero($d->accountNumber)."\",
                            \"accountDigit\": \"".numero($d->accountDigit)."\",
                            \"branchNumber\": \"".numero($d->branchNumber)."\""                         
                            )."
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

        $retorno = json_decode($proposta);

        mysqli_query($con, "insert into status set status = '{$retorno->statusCode}', descricao = '{$retorno->message}', unico = '".md5($retorno->statusCode.$retorno->message)."'");

        $r = mysqli_fetch_object(mysqli_query($con, "select * from status where unico = '".md5($retorno->statusCode.$retorno->message)."'"));

        //Envio das mensagens para o WhatsApp
        $q1 = "select * from status_mensagens where status = '{$r->codigo}' and situacao = '1' order by codigo asc";
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
                'para'=>'55'.numero($d->phoneNumber)
                ]);
            }else{
                $wgw = new wgw;
                $wgw->SendTxt([
                'mensagem'=>$msg,
                'para'=>'55'.numero($d->phoneNumber)
                ]);
            }
        }
        //Fin dos envios das mensagens para o whatsapp

        if($r->descricao){
            $retorno = $r->descricao;
        }else{
            $retorno = $retorno->message;
        }

?>

    <div class="alert alert-warning text-center m-3" role="alert">
        <b><?=$retorno?></b>
        <?php
        if($retorno->statusCode == 200){
        ?>
        <p>Em alguns minutos você receberá um link para fazer a foto e anexar seu documento.</p>
        <?php
        }else{
        ?>
        <p>
            <button class="btn btn-primary w-100 retornoCadastro mt-3">
                Retornar ao Formulário de Cadastro
            </button>
        </p>
        <?php
        }
        ?>
    </div>

<?php


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
</style>
<div class="card m-1">
  <h5 class="card-header">Consulta de Saldo - FGTS</h5>
  <div class="card-body">
    <h5 class="card-title">
        <div class="d-flex justify-content-between">
            <span>Simulações</span>
        </div>
        <!-- <div class="row mt-3">
            <div class="col">
                <button class="btn btn-success btn-lg w-100" <?=(($cliente->pre_cadastro == 0)?'pendentes':'simulacao')?>>
                    <div class="d-flex justify-content-between align-items-center">
                        <i class="fa-regular fa-hand-pointer" style="font-size:60px;"></i>
                        Clique aqui para consultar o seu saldo FGTS
                    </div>
                </button>
            </div>
        </div> -->
    </h5>
    <div class="card-text" style="min-height:300px;">

    <?php

    $query = "select *, dados->>'$.statusCode' as simulacao, proposta->>'$.statusCode' as status_proposta from consultas where cliente = '{$_SESSION['codUsr']}' order by codigo desc limit 1";
    $result = sisLog( $query);
    if(mysqli_num_rows($result)){
        while($d = mysqli_fetch_object($result)){
            $dados = json_decode($d->dados);

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
            <div class="card mb-2 border-<?=(($d->status_proposta and $d->status_proposta < 400)?'success':'primary')?>">
                <div class="card-header bg-<?=(($d->status_proposta and $d->status_proposta < 400)?'success':'primary')?> text-white">
                <?=(($d->status_proposta and $d->status_proposta < 400)?'PROPOSTA':'SIMULAÇÃO')?> - <?=strtoupper($d->consulta)?>
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
                            <label>Valor Liberado</label>
                            <div><h1 class="text-primary">R$ <?=number_format($dados->data->simulationData->totalReleasedAmount,2,',','.')?></h1></div>
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

                    <div class="col-md-2">
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
                ?>
                <button proposta='<?=$d->codigo?>' class="btn btn-warning btn-lg m-1">
                    CLIQUE AQUI PARA FINALIZAR SUA CONTRATAÇÃO
                </button>
                <?php
                    if($d->status_proposta){
                        $proposta = json_decode($d->proposta);
                ?>
                    <div class="alert alert-danger m-1" role="alert">
                        <?="{$proposta->statusCode} - {$proposta->message}"?>
                    </div>
                <?php
                    }

                    }
                ?>
            </div>

        <?php
            }else{
        ?>
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
    }else{
    ?>
    <div class="d-flex justify-content-center align-items-center">
        <h3 style="margin-top:100px; color:#a1a1a1; text-align:center"><i class="fa-regular fa-face-meh" style="font-size:100px;"></i><br>Você ainda não possui nenhuma consulta de saldo</h3>
    </div>
    <?php
    }
    ?>
    </div>
  </div>
  <!-- <button class="btn btn-primary btn-sm atualiza">Atualizar</button> -->

    <!-- <div class="mt-2">
        <a class="btn btn-primary btn-lg " style="width:100%" href="#" local="fgts/cadastro.php"><i class="fa-solid fa-angles-right"></i> Preencher o formulário de cadastro completo</a>
    </div> -->

    <div class="m-3 text-end">
        <a class="text-danger text-decoration-none sair" style="cursor:pointer"><i class="fa-solid fa-right-from-bracket"></i> Sair do login</a>
    </div>
</div>



<script>
    $(function(){

        Carregando('none')

        $("button[simulacao]").click(function(){

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
                                url:"fgts/saldo.php",
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


        $("button[pendentes]").click(function(){

            $.alert({
                title:"Pendência no Pré-Cadastro",
                content:"Favor retornar a tela de Pré-Cadastro e preencha os dados necessários para consultar o seu saldo:<br><br>Os dados obrigatórios são:<p style='color:red'>- Nome Completo<br>- Número CPF</p>",
                type:"orange",
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

        $("button[proposta]").click(function(){
            proposta = $(this).attr("proposta");
            Carregando();
            $.ajax({
                url:"fgts/saldo.php",
                type:"POST",
                data:{
                    acao:"proposta",
                    proposta
                },
                success:function(dados){
                    $(".palco").html(dados);
                }
            })
            
        })
        
        $(".retornoCadastro").click(function(){
            Carregando();
            $.ajax({
                url:"fgts/cadastro.php",
                type:"POST",
                data:{
                    acao:"proposta",
                    proposta
                },
                success:function(dados){
                    $(".palco").html(dados);
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

        $.ajax({
            url:"assets/lib/log_acessos.php",
            success:function(dados){
            //Retorno da função
            // console.log(dados);
            }
        });


    })
</script>