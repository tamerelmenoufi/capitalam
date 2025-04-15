<?php
    // exit();
    include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");
    
    $query = "select * from banco where migrado = '0' limit 1000";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
        set_time_limit(90);

        $tem = mysqli_fetch_object(mysqli_query($con, "select * from clientes where codigo = '{$d->codigo}'"));

        if(!$tem->codigo){
            mysqli_query($con, "insert into clientes set 
                                                        nome = '{$d->nome}', 
                                                        data_cadastro = NOW(),
                                                        ultimo_acesso = NOW(),
                                                        cpf = '{$d->cpf}', 
                                                        phoneNumber = '{$d->telefone}', 
                                                        simulacao_10 = '0', 
                                                        origem = 'BIQ'
                                                        
                                                        ");
        }
        mysqli_query($con, "update banco set migrado = '1' where codigo = '{$d->codigo}'");

    }
