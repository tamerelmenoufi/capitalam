<?php
    header('Content-Type: application/json; charset=utf-8');

    include("{$_SERVER['DOCUMENT_ROOT']}/capitalam/painel/lib/includes.php");

    $inputData = file_get_contents('php://input');
    $data = json_decode($inputData, true);

    file_put_contents('teste.txt', $inputData);

    $retorno = [
        "status" => 'success',
        "contrato" => '123456',
        $data
    ];

    echo json_encode($retorno);
