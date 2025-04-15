<?php
//verificação da autorização
if($dC->autorizacao_vctex > 0){
?>
    $(`i[etapa="fgts/home.php"], div[etapa="fgts/home.php"]`).attr("acao", "lib");
    $(`i[etapa="fgts/autorizacao.php"], div[etapa="fgts/autorizacao.php"]`).attr("acao", "lib");
    $(".linha").css("width","25%");
    $(`i[etapa="fgts/autorizacao.php"]`).removeClass("fa-regular");
    $(`i[etapa="fgts/autorizacao.php"]`).addClass("fa-solid");
    $(`i[etapa="fgts/autorizacao.php"]`).css("cursor", "pointer");
    $(`div[etapa="fgts/autorizacao.php"]`).css("cursor", "pointer");

    $(`i[etapa="fgts/home.php"]`).removeClass("fa-regular");
    $(`i[etapa="fgts/home.php"]`).addClass("fa-solid");
    $(`i[etapa="fgts/home.php"]`).css("cursor", "pointerd");
    $(`div[etapa="fgts/home.php"]`).css("cursor", "pointerd");

<?php
}else{
?>
    $(`i[etapa="fgts/home.php"], div[etapa="fgts/home.php"]`).attr("acao", "blq");
    $(`i[etapa="fgts/autorizacao.php"], div[etapa="fgts/autorizacao.php"]`).attr("acao", "blq");
    $(".linha").css("width","0%");
    $(`i[etapa="fgts/autorizacao.php"]`).removeClass("fa-solid");
    $(`i[etapa="fgts/autorizacao.php"]`).addClass("fa-regular");
    $(`i[etapa="fgts/autorizacao.php"]`).css("cursor", "not-allowed");
    $(`div[etapa="fgts/autorizacao.php"]`).css("cursor", "not-allowed");

    $(`i[etapa="fgts/home.php"]`).removeClass("fa-solid");
    $(`i[etapa="fgts/home.php"]`).addClass("fa-regular");
    $(`i[etapa="fgts/home.php"]`).css("cursor", "not-allowed");
    $(`div[etapa="fgts/home.php"]`).css("cursor", "not-allowed");


<?php
}

//verificação do pré-cadastro
if($dC->pre_cadastro > 0){
?>
    $(`i[etapa="fgts/saldo.php"], div[etapa="fgts/saldo.php"]`).attr("acao", "lib");
    $(`i[etapa="fgts/saldo.php"]`).removeClass("fa-regular");
    $(`i[etapa="fgts/saldo.php"]`).addClass("fa-solid");
    $(`i[etapa="fgts/saldo.php"]`).css("cursor", "pointer");
    $(`div[etapa="fgts/saldo.php"]`).css("cursor", "pointer");

    $(`i[etapa="fgts/cadastro.php"], div[etapa="fgts/cadastro.php"]`).attr("acao", "lib");
    $(".linha").css("width","75%");
    $(`i[etapa="fgts/cadastro.php"]`).removeClass("fa-regular");
    $(`i[etapa="fgts/cadastro.php"]`).addClass("fa-solid");
    $(`i[etapa="fgts/cadastro.php"]`).css("cursor", "pointer");
    $(`div[etapa="fgts/cadastro.php"]`).css("cursor", "pointer");

<?php
}else{
?>
    $(`i[etapa="fgts/saldo.php"], div[etapa="fgts/saldo.php"]`).attr("acao", "blq");
    $(".linha").css("width","25%");
    $(`i[etapa="fgts/saldo.php"]`).removeClass("fa-solid");
    $(`i[etapa="fgts/saldo.php"]`).addClass("fa-regular");
    $(`i[etapa="fgts/saldo.php"]`).css("cursor", "not-allowed");
    $(`div[etapa="fgts/saldo.php"]`).css("cursor", "not-allowed");

    $(`i[etapa="fgts/cadastro.php"], div[etapa="fgts/cadastro.php"]`).attr("acao", "blq");
    $(`i[etapa="fgts/cadastro.php"]`).removeClass("fa-solid");
    $(`i[etapa="fgts/cadastro.php"]`).addClass("fa-regular");
    $(`i[etapa="fgts/cadastro.php"]`).css("cursor", "not-allowed");
    $(`div[etapa="fgts/cadastro.php"]`).css("cursor", "not-allowed");

<?php
}

//verificação da autorização
if($dC->cadastro_percentual == 100){
?>
    $(`i[etapa="fgts/consulta.php"], div[etapa="fgts/consulta.php"]`).attr("acao", "lib");
    $(".linha").css("width","100%");
    $(`i[etapa="fgts/consulta.php"]`).removeClass("fa-regular");
    $(`i[etapa="fgts/consulta.php"]`).addClass("fa-solid");
    $(`i[etapa="fgts/consulta.php"]`).css("cursor", "pointer");
    $(`div[etapa="fgts/consulta.php"]`).css("cursor", "pointer");
<?php
}else{
?>
    $(`i[etapa="fgts/consulta.php"], div[etapa="fgts/consulta.php"]`).attr("acao", "blq");
    $(".linha").css("width","75%");
    $(`i[etapa="fgts/consulta.php"]`).removeClass("fa-solid");
    $(`i[etapa="fgts/consulta.php"]`).addClass("fa-regular");
    $(`i[etapa="fgts/consulta.php"]`).css("cursor", "not-allowed");
    $(`div[etapa="fgts/consulta.php"]`).css("cursor", "not-allowed");
<?php
}

?>