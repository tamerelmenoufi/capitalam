<?php

include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");


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
        Código de Confirmação
    </div>
    <div class="card-body">
        <h5 class="card-title">Confirmação de acesso a sua área restrita</h5>
        <p class="card-text">Consulte no seu WhatsApp a mensagem envida diretamente de nossa plataforma a partir do número remetente <b>+1 (204) 410-6014</b>.</p>
        
        <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-wrapping"><i class="fa-solid fa-unlock"></i></span>
            <input type="text" id="codigo_acesso" class="form-control" inputmode="numeric" placeholder="Digite o código" aria-label="Código de acesso" aria-describedby="addon-wrapping" >
            <button class="btn btn-outline-secondary acessar" type="button" id="button-addon1">Confirmar</button>
        </div>
        <p style="color:#534ab3; font-size:12px">O seu código chega instantâneamente no seu WhatsApp. Se ainda não recebeu seu código, favor conferir o número informado se possui WhatsApp ou confira se digitou corretamente.</p>
        <button class="btn btn-warning btn-sm voltar"><i class="fa-regular fa-circle-left"></i> Retornas para a tela de Identificação</button>

    </div>
    </div>
</div>

<script>
    $(function(){
        $("#codigo_acesso").mask("9999");

        $(".acessar").click(function(){

            codigo_acesso = $("#codigo_acesso").val();

            if(!codigo_acesso){
                $.alert({
                    type:"red",
                    title:"Erro der Identificação",
                    content: 'Favor digite o código enviado para o seu WhatsApp!'
                })

                return false;
            }

            if($("#codigo_acesso").val().length != 4 || '<?=$_POST['codigo']?>' != $("#codigo_acesso").val()){
                $.alert({
                    type:"red",
                    title:"Erro der Identificação",
                    content: 'O Código informado não está correto!'
                })

                return false;                
            }
            
            $.ajax({
                url:"fgts/autorizacao.php",
                type:"POST",
                data:{
                    telefone:'<?=$_POST['telefone']?>'
                },
                success:function(dados){
                    $(".palco").html(dados);
                }
            })

        })


        $(".voltar").click(function(){
            
            $.ajax({
                url:"fgts/login.php",
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