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
        Capital Soluções - especialistas em antecipação de FGTS
    </div>
    <div class="card-body">
        <h5 class="card-title">VOCÊ JÁ ANTECIPOU SEU FGTS?</h5>
        <p class="card-text">Clique em uma das opções abaixo e vamos te explicar em detalhes do que precisa e como fazer.</p>
        
        <div class="mt-2">
            <button class="btn btn-primary w-100" local="fgts/explicacao.php"><i class="fa-solid fa-angles-right"></i> NÃO, é a primeira vez</button>
        </div>

        <div class="mt-2">
            <button class="btn btn-primary w-100" local="fgts/login.php"><i class="fa-solid fa-angles-right"></i> SIM, quero fazer nova consulta do saldo</button>
        </div>

    </div>
    </div>
</div>

<script>
    $(function(){

        $("button[local]").click(function(){
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