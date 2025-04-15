<?php

include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");

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

</style>

<div class="card" data-aos="zoom-in" data-aos-delay="200">
    <div class="card-header">
        Capital Soluções - especialistas em antecipação de FGTS
    </div>
    <div class="card-body">
        <h5 class="card-title">VOCÊ JÁ ANTECIPOU SEU FGTS?</h5>
        <p class="card-text">Clique no botão abaixo e faça sua consulta e cadastro.</p>
        
        <!--<div class="mt-2">
            <button class="btn btn-primary w-100" local="fgts/explicacao.php"><i class="fa-solid fa-angles-right"></i> NÃO, é a primeira vez</button>
        </div>-->

        <div class="mt-2">
            <button class="btn btn-primary w-100 btn-lg" style="width:100%" local="fgts/login.php"><i class="fa-solid fa-angles-right"></i>   Faça sua consulta e cadastro.</button>
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