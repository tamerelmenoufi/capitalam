<?php
    exit();
    include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");
    $dados = file_get_contents("dados.csv");

    $linhas = explode("\n",$dados);

    $query = "INSERT INTO banco (cpf, nome, telefone) VALUES ";
    $data = [];
    $i=0;
    $cpfs = [];
    foreach($linhas as $i => $colunas){
        set_time_limit(100);
        $cols = explode("	",$colunas);

        $cpf = preg_replace('/[^0-9]/', '', $cols[0]);
        $cpf = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);

        $fone = preg_replace('/[^0-9]/', '', $cols[2]);
        $fone = '(' . substr($fone, 0, 2) . ') ' . substr($fone, 3, 5) . '-' . substr($fone, 7);

        if(!in_array($cpf, $cpfs)){
            $data[] = "('{$cpf}', '{$cols[1]}', '{$fone}')";
            $i++;
        }
        $cpfs[$cpf] = $cpf;

        if($i%100 == 0 and $i > 0){
            $comando = $query.implode(", ",$data);
            mysqli_query($con, $comando);
            echo $i.", ";
            // exit();
        }
        

    }