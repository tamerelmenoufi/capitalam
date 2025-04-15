<?php
        
    include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");

    if($_POST['acao'] == 'limpar'){
        $_SESSION['data_inicial'] = false;
        $_SESSION['data_final'] = false;
    }


    if($_POST['acao'] == 'busca'){
        $_SESSION['data_inicial'] = $_POST['data_inicial'];
        $_SESSION['data_final'] = $_POST['data_final'];
    }

    if($_SESSION['data_inicial'] and $_SESSION['data_final']){
        $where = " and ultimo_acesso between '{$_SESSION['data_inicial']} 00:00:00' and '{$_SESSION['data_final']} 23:59:59'";
    }


?>
<div class="m-3 p-3 bg-white">
    <h5>Relatório por Período</h5>
    <div class="input-group mb-3">
        <span class="input-group-text">Data Inicial</span>
        <input type="date" id="data_inicial" class="form-control" placeholder="Data Inicial" aria-label="Data Inicial" value='<?=$_SESSION['data_inicial']?>'>
        <span class="input-group-text">Data Final</span>
        <input type="date" id="data_final" class="form-control" placeholder="Data Inicial" aria-label="Data Inicial" value='<?=$_SESSION['data_final']?>'>
        <button class="btn btn-outline-secondary" type="button" id="busca">Listar</button>
        <button class="btn btn-outline-danger" type="button" id="limpar">Limpar</button>
    </div>
<?php
    if($where){


        $query = "select count(*) as qt from clientes where 1 {$where}";
        $result = mysqli_query($con, $query);
        $qt = mysqli_fetch_object($result);
        
        
?>
    <div class="input-group mb-3">
        <span class="input-group-text">Foram localizados <?=$qt->qt?> registros no total. Clique no botão ao lado para baixar </span>
        <a href='financeira/relatorios/download.php' class="btn btn-outline-success" type="button" id="baixar">Baixar</a>
    </div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>Bancos Autorizados</th>
                <th>Último Status</th>
            </tr>
        </thead>
        <tbody>
<?php

        $query = "select *, status_atual->>'$.message' as status_atual, bancos_autorizados->>'$[*].banco' as bancos  from clientes where 1 {$where} limit 100";
        $result = mysqli_query($con, $query);
        while($d = mysqli_fetch_object($result)){
?>
            <tr>
                <td><?=$d->nome?></td>
                <td><?=$d->cpf?></td>
                <td><?=$d->phoneNumber?></td>
                <td><?=$d->bancos?></td>
                <td><?=$d->status_atual?></td>
            </tr>
<?php
        }
?>
        </tbody>
    </table>
<?php
    }

?>
</div>

<script>
    $(function(){
        Carregando('none');

        $("#busca").click(function(){
            Carregando();
            data_inicial = $("#data_inicial").val();
            data_final = $("#data_final").val();
            $.ajax({
                url:"financeira/relatorios/index.php",
                type:"POST",
                data:{
                    data_inicial,
                    data_final,
                    acao:'busca'
                },
                success:function(dados){
                    $("#paginaHome").html(dados);
                }
            })
        })

        $("#limpar").click(function(){
            Carregando();
            $.ajax({
                url:"financeira/relatorios/index.php",
                type:"POST",
                data:{
                    acao:'limpar'
                },
                success:function(dados){
                    $("#paginaHome").html(dados);
                }
            })
        })
    })
</script>