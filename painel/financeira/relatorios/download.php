<?php

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="relatorio-'.date("YmdHis").'.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");

    if($_SESSION['data_inicial'] and $_SESSION['data_final']){
        $where = " and ultimo_acesso between '{$_SESSION['data_inicial']} 00:00:00' and '{$_SESSION['data_final']} 23:59:59'";
    }

    echo "Nome;CPF;Telefone;Bancos Autorizados;Último Status\n";
    $query = "select *, status_atual->>'$.message' as status_atual, bancos_autorizados->>'$[*].banco' as bancos  from clientes where 1 {$where}";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
        echo "{$d->nome};{$d->cpf};{$d->phoneNumber};{$d->bancos};{$d->status_atual}\n";
    }
?>