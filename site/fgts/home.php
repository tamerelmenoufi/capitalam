<?php

include("{$_SERVER['DOCUMENT_ROOT']}/capitalam/painel/lib/includes.php");

// if($_GET['wapp']){
    $_SESSION['wapp'] = $_GET['wapp'];
//   }
//   else{
//     $_SESSION['wapp'] = false;
//   }

if($_POST['s']) $_SESSION['codUsr'] = false;

if($_POST['acao'] == 'salvar'){

    $query = "select * from clientes where cpf = '{$_POST['cpf']}'";
    $result = sisLog( $query);
    if(!mysqli_num_rows($result)){


        $q = "insert into clientes set 
                                        cpf = '{$_POST['cpf']}', 
                                        ".(($_POST['nome'])?"nome='{$_POST['nome']}', ":false)."
                                        ".(($_POST['birthdate'])?"birthdate='{$_POST['birthdate']}', ":false)."
                                        ".(($_POST['phoneNumber'])?"phoneNumber='{$_POST['phoneNumber']}', ":false)."
                                        ultimo_acesso = NOW() ";
        $r = sisLog( $q);

        $n = mysqli_insert_id($con);

        sisLog("insert into clientes_origem set cliente = '{$n}', origem = 'midias', data = NOW()");

        echo $n;
        
    }else{

        $d = mysqli_fetch_object($result);
        $n = $d->codigo;

        $q = "update clientes set 
                                    cpf = '{$_POST['cpf']}', 
                                    ".(($_POST['nome'])?"nome='{$_POST['nome']}', ":false)."
                                    ".(($_POST['birthdate'])?"birthdate='{$_POST['birthdate']}', ":false)."
                                    ".(($_POST['phoneNumber'])?"phoneNumber='{$_POST['phoneNumber']}', ":false)."
                                    ultimo_acesso = NOW()
                where codigo = '{$n}'";
        $r = sisLog( $q);

        echo $n;

    }
    
    /*
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
    //*/
    exit();

}



$query = "select * from clientes where codigo = '{$_SESSION['codUsr']}'";
$result = sisLog( $query);
$d = mysqli_fetch_object($result);
$dC = $d;

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


<iframe src="status.php?local=form&status=false" style="position:fixed; left=-1000px; top:-1000px;"></iframe>


<div class="row g-0">

<div class=" col-md-4 offset-md-2 border-0">
    <p style="font-family: 'Montserrat', sans-serif!important;color: #8b8989; width:100%; text-align:left; padding:30px;">Em poucos passos você já terá sua simulação</p>
</div>
<div class=" col-md-6 border-0"></div>  
  
<div class=" col-md-2 border-0"></div>    
<!-- <div class=" col-md-2 card border-0" style="padding:10px;">
    <img src="assets/img/tg.jpeg" class="img-fluid" />
</div> -->
<!-- <div class=" col-md-2 card border-0" style="padding:10px;">
    <img src="assets/img/t2.jpeg" class="img-fluid" />
</div> -->

<div class=" col-md-8 card border-0" style="padding:30px;">
  <h2 class="m-0 align-middle" style="font-weight:bold;font-family: 'Montserrat', sans-serif!important;">Já autorizou os dois bancos?</h2>
  <!-- <h4 style="font-family: 'Montserrat', sans-serif!important;">Saque aniversário FGTS</h4>  -->
        
    <p style="font-family: 'Montserrat', sans-serif!important;color: #8b8989;margin-top:25px"> Para continuar, informe-nos os dados abaixo:</p>
    <div class="">
        <!--
        <h5 style="text-align:center" class="card-title">Formulário de Identificação</h5>
        <p class="card-text">Estamos quase finalizando, nesta tela vamos precisar apenas de algumas informações que serão necessárias para consultar o seu saldo de antecipação do FGTS.</p>
        -->
        <!-- <div class="mb-3">
            <label for="nome" class="form-label">Nome Completo</label>
            <input acao type="text" id="nome" class="form-control" placeholder="Nome Completo" value="<?=$d->nome?>"
                    style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;background: #fff;padding:12px" >                     
        </div> -->
        
    
        <div class="form-group col-md-12">
             <label for="cpf" class="form-label">CPF</label>
            <input acao type="text" inputmode="numeric" id="cpf" class="form-control" placeholder="Número CPF" value="<?=$d->cpf?>"
                    style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;background: #fff;padding:12px;margin-bottom:10px" >           
        </div>

        <!-- <div class="form-group col-md-12">
            <label for="birthdate" class="form-label">Data de Nascimento</label>
            <input acao type="date" id="birthdate" class="form-control" placeholder="Data de Nascimento" value="<?=$d->birthdate?>"
            style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;background: #fff;padding:12px;margin-bottom:10px" >
            
        </div> -->


        <?php
        if(!$_SESSION['wapp']){
        ?>
        <div class="mb-3">
            <label class="form-label" for="phoneNumber">Telefone*</label>
            <input acao type="text" inputmode="numeric" id="phoneNumber" class="form-control" placeholder="Telefone (WhatsApp)" value="<?=$d->phoneNumber?>"
            style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;background: #fff;padding:12px" >
        </div>
        <?php
        }
        ?>

        <p class="" style="font-family: 'Montserrat', sans-serif!important;font-weight:bold;">Todos os campos acima são obrigatórios</p>

        <div class="form-check mt-4">
            <input class="form-check-input" type="checkbox" value="" id="term">
            <label class="form-check-label" for="term">
                Ao preencher e enviar este formulário, você autoriza que <strong>CAPITAL SOLUÇÕES</strong>
                entre em contato com você por celular, e-mail ou WhatsApp.
            </label>
        </div>

        <!-- <div class="mb-3">
            <label class="form-label">Telefone de Contato</label>
            <div class="form-control"><?=$d->phoneNumber?></div>
            <div class="form-text">Telefone confirmado no login</div>
        </div> -->
        <div class="mt-2" style="text-align:center">
            <a class="btn btn-primary btn-lg botao-capital"  style="" href="#" local="fgts/consulta.php"><i class="fa-solid fa-angles-right"></i> Consultar saldo FGTS</a>
        </div>
        <?php
        if($_SESSION['codUsr']){
        ?>
        <div class="mt-3 text-end">
            <a class="text-danger text-decoration-none sair" style="cursor:pointer"><i class="fa-solid fa-right-from-bracket"></i> Sair do login</a>
        </div>        
        <?php
        }
        ?>
    </div>
    </div>
</div>
 </div>
<div class=" col-md-2 border-0"></div>    
</div>

<script>
    $(function(){

        $("#cpf").mask("999.999.999-99");
        <?php
        if(!$_SESSION['wapp']){
        ?>
        $("#phoneNumber").mask("(99) 99999-9999");
        <?php
        }
        ?>
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

        $("input[acaoXXX]").blur(function(){
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
                                        url:"fgts/home.php?wapp=<?=$_SESSION['wapp']?>",
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

            urlx = $(this).attr("local");
            // nome = $("#nome").val();
            cpf = $("#cpf").val();
            // birthdate = $("#birthdate").val();
            <?php
            if(!$_SESSION['wapp']){
            ?>
            phoneNumber = $("#phoneNumber").val();
            <?php
            }
            ?>
            termos = $("#term").prop("checked");

            
            // if(!nome || !cpf || !birthdate || !phoneNumber){
            <?php
            if(!$_SESSION['wapp']){
            ?>
            if( !cpf || !phoneNumber){
            <?php
            }else{
            ?>
            if( !cpf ){
            <?php
            }
            ?>
                $.alert({
                    title:'Dados Incompletos',
                    content:"Para prosseguir é necessáro preencher os dados completos do formulário.",
                    type:'red'
                });

                return false;
            }

            if(!validarCPF(cpf)){
                $.alert({
                    title:"Erro CPF",
                    content:"O CPF Informado não é válido!",
                    type:'red'
                })
                return false;
            }

            // if(birthdate.length != 10){
            //     $.alert({
            //         title:"Erro Data Nascimento",
            //         content:"Favor preencher corretamente a data de nascimento!",
            //         type:'red'
            //     })
            //     return false;                
            // }
            <?php
            if(!$_SESSION['wapp']){
            ?>
            if(phoneNumber.length != 15){
                $.alert({
                    title:"Erro Telefone",
                    content:"Favor preencher corretamente o número do telefone (WhatsApp)!",
                    type:'red'
                })
                return false;                
            }
            <?php
            }
            ?>
            if(!termos){
                $.alert({
                    title:"Confirmação dos termos",
                    content:"Para prosseguir é necessário aceitar os termos da comunição da capital por telefone / WhatsApp!",
                    type:'red'
                })
                return false;  
            }

            Carregando();
            $.ajax({
                url:"fgts/home.php",
                type:"POST",
                data:{
                    // nome,
                    cpf,
                    // birthdate,
                    <?php
                    if(!$_SESSION['wapp']){
                    ?>
                    phoneNumber,
                    <?php
                    }
                    ?>
                    acao:'salvar'
                },
                success:function(dados){
                    localStorage.setItem("codUsr", dados);
                    $.ajax({
                        url:urlx,
                        type:'POST',
                        data:{
                            codUsr:dados
                        },
                        success:function(dados){
                            $(".palco").html(dados);
                            Carregando('none');
                        }
                    })

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