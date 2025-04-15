<?php

include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");

if($_POST['acao'] == 'salvar'){

    if($_POST['campo'] == 'cpf'){
        $query = "select * from clientes where cpf = '{$_POST['valor']}' and codigo != '{$_SESSION['codUsr']}'";
        $result = sisLog( $query);
        if(mysqli_num_rows($result)){
            echo 'error';
            exit();
        }
    }
    $valor = addslashes($_POST['valor']);
    $query = "update clientes set {$_POST['campo']} = '{$valor}' where codigo = '{$_SESSION['codUsr']}'";
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
        Pré-Cadastro
    </div>
    <div class="card-body">
        <h5 class="card-title">Formulário de Identificação</h5>
        <p class="card-text">Estamos quase finalizando, nesta tela vamos precisar apenas de algumas informações que serão necessárias para consultar o seu saldo de antecipação do FGTS.</p>
        
        <div class="mb-3">
            <label for="nome" class="form-label">Nome Completo</label>
            <input acao type="text" class="form-control" id="nome" aria-describedby="nome" value="<?=$d->nome?>">
            <div id="nome" class="form-text">Digite seu nome completo conforme seu documento de identificação</div>
        </div>

        <div class="mb-3">
            <label for="cpf" class="form-label">Número CPF</label>
            <input acao type="text" class="form-control" inputmode="numeric" id="cpf" aria-describedby="cpf" value="<?=$d->cpf?>">
            <div id="cpf" class="form-text">Digite seu CPF confira o número antes de confirmar</div>
        </div>

        <!-- <div class="mb-3">
            <label class="form-label">Telefone de Contato</label>
            <div class="form-control"><?=$d->phoneNumber?></div>
            <div class="form-text">Telefone confirmado no login</div>
        </div> -->
        <div class="mt-2">
            <a class="btn btn-primary btn-sm" href="#" local="fgts/saldo.php"><i class="fa-solid fa-angles-right"></i> Consultar saldo FGTS</a>
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

            url = $(this).attr("local");
            nome = $("#nome").val();
            cpf = $("#cpf").val();

            

            if(!nome || !cpf){
                $.alert({
                    title:'Dados Incompletos',
                    content:"Para prosseguir é necessáro preencher os dados completos do formulário.",
                    type:'red'
                });

                return false;
            }

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