<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");
?>
<style>
    .listaWapp{
        position:absolute; 
        left:0; 
        top:0; 
        bottom:0; 
        width:40%; 
        overflow:auto; 
    }
    .mensagensaWapp{
        position:absolute; 
        right:0; 
        top:0; 
        bottom:0; 
        width:60%; 
        overflow:auto; 
    }
</style>
<div class="row g-0">
    <div class="col-12">
        <div class="d-flex justify-content-end">
            <i class="fa-solid fa-xmark fechaPopupWapp m-3" style="font-size:20px; cursor:pointer"></i>
        </div>
    </div>
</div>
<div style="position:absolute; top:40px; left:0; right:0; bottom:0;">
    <div class="listaWapp"></div>
    <div class="mensagensaWapp"></div>
</div>

<script>
    $(function(){
        
        $(".fechaPopupWapp").click(function(){
            $(".popupWapp").css("display","none");
            $(".popupWappBg").css("display","none");
            $(".popupWapp").html('');
            $("body").css("overflow", "auto");
        })

        $("body").css("overflow", "hidden");
        
        $.ajax({
            url:"financeira/wapp/wapp_lista.php",
            success:function(dados){
                $(".listaWapp").html(dados);
            }
        })

        $.ajax({
            url:"financeira/wapp/wapp.php",
            success:function(dados){
                $(".mensagensWapp").html(dados);
            }
        })
    })
</script>