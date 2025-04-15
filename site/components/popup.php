<?php
    // include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");

    $query = "select * from configuracoes where codigo = '1'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidade - Capital Soluções</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
        h1, h2 { color: #2c3e50; }
        p { margin: 10px 0; }
        ul { margin: 10px 0; padding-left: 20px; }
        .container { max-width: 800px; margin: auto; }
    </style>
</head>
<body>
    <div class="container">
        <p>
            <img src="../assets/img/logonovacp.png" style="height:75px;">
        </p>
        <h1>TODOS OS DIREITOS RESERVADOS</h1>
        <p>A Capital Soluções não é uma instituição financeira e não realiza operações de crédito diretamente.</p> 
        <p>A Capital Soluções é uma plataforma digital e presencial que atua como correspondente bancário para facilitar o processo de contratação de empréstimos.</p> 
        <p>Toda avaliação de crédito será realizada conforme a política de crédito da Instituição Financeira escolhida pelo usuário. </p> 
        <p>Antes da contratação de qualquer serviço através de nossos parceiros, você receberá todas as condições e informações relativas ao produto a ser contrato,de forma completa e transparente. </p> 
        <p>As taxas de juros, margem consignável e prazo de pagamento praticados nos empréstimos com consignação em pagamentodos Governos Federais, Estaduais e Municipais, Forças armadas e INSS observam as determinações de cada convênio, bem como a política de créditoda instituição financeira a ser utilizada. </p> 
        <p><b>Capital Soluções - CNPJ 27.652.302/0001-09 | Endereço: R. Prof. Félix Valois, 61 - 69095-010 - Manaus, Amazonas.</b></p> 
    </div>
</body>
</html>