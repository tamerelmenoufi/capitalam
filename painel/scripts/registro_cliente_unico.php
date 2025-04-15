<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/site/assets/lib/includes.php");

    exit();

    ///////////////////////// SIMULACAO SEM CLIENTE////////////////////

    $query = "SELECT a.*, b.nome, b.cpf FROM consultas a left join clientes b on a.cliente = b.codigo where b.cpf is null limit 1000";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
        $cods[] = $d->codigo;
    }
    if($cods) $codigos = implode(", ", $cods);
    if($codigos) mysqli_query($con, "delete from consultas where codigo in ({$codigos})");
    echo $codigos;

    exit();
    ///////////////////REGISTROS UNICOS /////////////////////////////

    $query = "select *, count(*) qt from clientes where origem = 'BIQ' group by cpf order by qt desc limit 10";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){

        set_time_limit(90);
        if($d->qt > 1){
            $duplicado = [];
            $delete = [];
            echo $q = "select codigo, cpf from clientes where cpf = '{$d->cpf}'";
            $r = mysqli_query($con, $q);
            while($d1 = mysqli_fetch_object($r)){
                if(!$duplicado[$d1->cpf]) {
                    $duplicado[$d1->cpf] = $d1->codigo;
                }else{
                    $delete[] = $d1->codigo;
                }
            }
            echo "<hr>";
            $delete = implode(",",$delete);
            $qdel = "delete from clientes where codigo in({$delete})";
            mysqli_query($con, $qdel);
        }

    }


    exit();
    echo $query = "SELECT a.codigo, a.cpf, a.cadastro_percentual, (select count(*) from clientes where cpf = a.cpf and origem = 'BIQ') as `qt` FROM clientes a where a.cpf != '' and a.origem = 'BLQ' group by a.cpf, a.codigo ORDER BY qt desc, a.cpf limit 1";
    $result = mysqli_query($con, $query);
    $duplicado = [];
    $delete = [];
    while($d = mysqli_fetch_object($result)){
        set_time_limit(30);
        if($d->qt > 1){
            if(!$duplicado[$d->cpf]) {
                $duplicado[$d->cpf] = $d->codigo;
            }else{
                $delete[] = $d->codigo;
            }
        }

    }
    $delete = implode(",",$delete);
    echo $query = "delete from clientes where codigo in({$delete})";