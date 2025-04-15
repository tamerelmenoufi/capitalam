<?php

header('Content-Type: application/json; charset=utf-8');
include("{$_SERVER['DOCUMENT_ROOT']}/capitalam/painel/lib/includes.php");
$inputData = file_get_contents('php://input');
$data = json_decode($inputData, true);

file_put_contents('teste.txt', $inputData);

function AppValidarCPF($cpf) {
    $cpf = preg_replace('/\D/', '', $cpf);
    if (strlen($cpf) !== 11 || preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    for ($j = 9; $j < 11; $j++) {
        $soma = 0;
        for ($i = 0; $i < $j; $i++) {
            $soma += $cpf[$i] * (($j + 1) - $i);
        }
        $digitoVerificador = (10 * $soma) % 11;
        $digitoVerificador = ($digitoVerificador == 10 || $digitoVerificador == 11) ? 0 : $digitoVerificador;

        if ($cpf[$j] != $digitoVerificador) {
            return false;
        }
    }
    return true;
}

if (AppValidarCPF($data['cpf'])) {
    echo "{\"status\":\"true\"}";
} else {
    echo "{\"status\":false}";
}
?>
