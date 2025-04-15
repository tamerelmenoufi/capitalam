<?php

include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");

if($_POST['telefone']){

    $query = "select * from clientes where phoneNumber = '{$_POST['telefone']}'";
    $result = sisLog( $query);
    $d = mysqli_fetch_object($result);
    if(!$d->codigo){
        $query = "insert into clientes set phoneNumber = '{$_POST['telefone']}', data_cadastro = NOW(), ultimo_acesso = NOW(), validar_telefone = NOW()";
        $result = mysqli_query($con, $query);
        $_SESSION['codUsr'] = mysqli_insert_id($con); 
    }else{
        $_SESSION['codUsr'] = $d->codigo; 
    }

}

if($_POST['acao'] == 'autorizacao'){
    if($_POST['autorizacao'] == 'marcar'){
        $autorizacao_vctex = "NOW()";
    }else{
        $autorizacao_vctex = "0";
    }
    sisLog( "update clientes set autorizacao_vctex = {$autorizacao_vctex} where codigo = '{$_SESSION['codUsr']}'");

}

mysqli_query($con, "update clientes set pre_cadastro = NOW() where pre_cadastro = 0 and codigo = '{$_SESSION['codUsr']}'");
mysqli_query($con, "update clientes set ultimo_acesso = NOW() where codigo = '{$_SESSION['codUsr']}'");

if($_POST['acao'] == 'salvar'){

    if($_POST['campo'] == 'cpf'){
        $query = "select * from clientes where cpf = '{$_POST['valor']}' and codigo != '{$_SESSION['codUsr']}'";
        $result = sisLog( $query);
        if(mysqli_num_rows($result)){
            echo 'error';
            exit();
        }
    }

    $query = "update clientes set {$_POST['campo']} = '{$_POST['valor']}' where codigo = '{$_SESSION['codUsr']}'";
    sisLog( $query);
    echo 'success';
    exit();

}




$query = "select * from clientes where codigo = '{$_SESSION['codUsr']}'";
$result = sisLog( $query);
$d = mysqli_fetch_object($result);
$dC = $d;

?>

<style>

    .card{
        border-color:#534ab3,
    }
    .card-header{
        background-color:#534ab3;
        color:#fff;
    }
    .card-title{
        font-weight:bold;
        color:#534ab3;
    }
    .card-text{
        color:#534ab3;
    }

</style>

<div class="card" data-aos="zoom-in" data-aos-delay="200">
    <div class="card-header">
        Autorização saldo FGTS
    </div>
    <div class="card-body">
        <h5 class="card-title">Tela de Autorização</h5>
        <p class="card-text">Antes de fazer a suas simulações e contratação de antecipação do FGTS, é necessário que autorize os bancos parceiros que possibilitam o acessos do seu saldo de antecipação do FGTS na CAIXA.</p>
        <p class="card-text">Preparamos um passo a passo pra você seguir e realizar a autorização de forma simples e prática.</p>
        
        <div class="row">
            <div class="col-md-6">
                <p class="card-text">1. Baixe e instale o aplicativo FGTS.</p>
                <p>
                <img src="fgts/img/app_fgts.png?1" class="img-fluid" alt="Passo a Passo"></p>
            </div>
            <div class="col-md-6">
                <p class="card-text">2. Autorize essas duas instituições:</p>
                <img src="fgts/img/passo_a_passo.png?1" class="img-fluid" alt="Passo a Passo">
                <p class="card-text">QI SOCIEDADE DE CREDITO DIRETO S.A.</p>
                <p class="card-text">CDC SOCEDADE DE CREDITO AO MICROEMPREENDEDOR X E A EMPRESA DE PEQUENO PORTE LTDA</p>
            </div>
        </div>
        
        <p class="card-text">3. Logo em seguida clique em CONFIRMAR</p>
        
        <div class="mb-3 form-check">
            <input autorizacao type="checkbox" class="form-check-input" <?=(($d->autorizacao_vctex > 0)?'checked disabled':' id="autorizacao"')?>>
            <label class="form-check-label" for="autorizacao">Marque aqui, caso tenha realizado a autorização dos parceiros do banco.</label>
        </div>

        <div class="mt-2">
            <a class="btn btn-primary btn-sm" href="#" local="fgts/home.php"><i class="fa-solid fa-angles-right"></i> Realizar o pré-cadastro</a>
        </div>

        <div class="mt-3 text-end">
            <a class="text-danger text-decoration-none sair" style="cursor:pointer"><i class="fa-solid fa-right-from-bracket"></i> Sair do login</a>
        </div>

    </div>
    </div>
</div>

<script>
    $(function(){

        $("#cpf").mask("999.999.999-99");

        $("#autorizacao").click(function(){
            opc = (($(this).prop("checked") == true)?'marcar':'desmarcar');
            $.ajax({
            url:"fgts/autorizacao.php",
            type:"POST",
            data:{
                autorizacao:opc,
                acao:'autorizacao'
            },
            success:function(dados){
                // $(".palco").html(dados);
                console.log(dados)
            }
        })
        })


        <?php

        include("barra_status.php");

        if($_SESSION['codUsr']){
        ?>
        localStorage.setItem("codUsr", '<?=$_SESSION['codUsr']?>');
        $.ajax({
            url:"fgts/sessao.php",
            type:"POST",
            data:{
                codUsr:'<?=$_SESSION['codUsr']?>'
            },
            success:function(dados){
                // $(".palco").html(dados);
                console.log(dados)
            }
        })
        <?php
        }
        ?>

        $("input[acao]").blur(function(){
            campo = $(this).attr("id");
            valor = $(this).val();
            if(campo == 'cpf'){

                if(!validarCPF(valor)){
                    $.alert({
                        title:"Erro CPF",
                        content:"O CPF Informado não é válido!",
                        type:'red'
                    })
                    $("#cpf").val('');
                    return false;
                }
            }   


            $.ajax({
                url:"fgts/home.php",
                type:"POST",
                data:{
                    campo,
                    valor,
                    acao:'salvar'
                },
                success:function(dados){
                    
                    if(dados == 'error'){
                        $.alert({
                            title: "Erro CPF",
                            content:"O CPF Informado Já encontra-se cadastrado!",
                            type:'red'
                        })
                        $("#cpf").val('');
                    }
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

        $("a[local]").click(function(){

            autorizacao = $("input[autorizacao]").prop("checked")
            if(!autorizacao){
                $.alert({
                    type:"red",
                    title:"Autorização",
                    content:"Você precisa realizar a autorização dos bancos parceiros antes de prosseguir.<br>Marque a opção de autorização na caixinha do formulário."
                })
                return false;
            }

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