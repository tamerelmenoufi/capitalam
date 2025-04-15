<?php
    header('Content-Type: application/json; charset=utf-8');

    include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");

    $inputData = file_get_contents('php://input');
    $data = json_decode($inputData, true);

    if($_GET){
        $data['cpf'] = $_GET['cpf'];
        $data['telefone'] = $_GET['telefone'];
    }



    function consulta_logs($dados){
        global $con;
        mysqli_query($con, "update consultas_log set ativo = '0' where cliente = '{$cliente}'");
        $query = "insert into consultas_log set 
                                            consulta = '{$dados['proposta']}',
                                            cliente = '{$cliente}',
                                            data = NOW(),
                                            sessoes = '{\"codUsr\":\"{$cliente}\"}',
                                            log = '{$dados['consulta']}',
                                            log_unico = '".md5($dados['consulta'].$dados['proposta'])."',
                                            ativo = '1'";

        $result = sisLog( $query);

        mysqli_query($con, "update clientes set status_atual = '{$dados['consulta']}' where codigo = '{$cliente}'");
    }


    
    //Verificando a validade do token (autenticação)
    $vctex = new Vctex;

    $query = "select *, api_vctex_dados->>'$.token.accessToken' as token from configuracoes where codigo = '1'";
    $result = sisLog( $query);
    $d = mysqli_fetch_object($result);

    $token = $d->token;
    $tabela_padrao = $d->api_vctex_tabela_padrao;

    $agora = time();

    if($agora > $d->api_expira){
        $retorno = $vctex->Token();
        $dados = json_decode($retorno);
        if($dados->statusCode == 200){
            $tabelas = $vctex->Tabelas($dados->token->accessToken);
            $token = $dados->token->accessToken;
            sisLog( "update configuracoes set api_vctex_expira = '".($agora + $dados->token->expires)."', api_vctex_dados = '{$retorno}', api_vctex_tabelas = '{$tabelas}' where codigo = '1'");
        }else{
            $tabelas = 'error';
        }
    }
    //Final da autenticação do token


    //Início da Simulação
    if($data['cpf']){

        #Cliente cadastrado?
        $query = "select * from clientes where REPLACE(REPLACE(cpf, '.', ''), '-', '') = '".str_replace(['.','-', ' '],false,$data['cpf'])."'";
        $result = sisLog( $query);
        if(!mysqli_num_rows($result)){
            $cpf = preg_replace('/[^0-9]/', '', $data['cpf']);
            $cpf = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);

            $telefone = preg_replace('/\D/', '', $data['telefone']);
            $telefone = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);

            $q = "insert into clientes set 
                                            cpf = '{$cpf}', 
                                            ".(($data['nome'])?"nome='{$data['nome']}', ":false)."
                                            ".(($data['birthdate'])?"birthdate='{$data['birthdate']}', ":false)."
                                            ".(($data['telefone'])?"phoneNumber='{$telefone}', ":false)."
                                            ultimo_acesso = NOW() ";
            $r = sisLog( $q);

            $n = mysqli_insert_id($con);

            sisLog("insert into clientes_origem set cliente = '{$n}', origem = 'midias', data = NOW()");

            
        }else{

            $d = mysqli_fetch_object($result);
            $n = $d->codigo;

            $telefone = preg_replace('/\D/', '', $data['telefone']);
            $telefone = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);

            $q = "update clientes set 
                                        ".(($data['nome'])?"nome='{$data['nome']}', ":false)."
                                        ".(($data['birthdate'])?"birthdate='{$data['birthdate']}', ":false)."
                                        ".(($data['telefone'])?"phoneNumber='{$telefone}', ":false)."
                                        ultimo_acesso = NOW()
                    where codigo = '{$n}'";
            $r = sisLog( $q);

        }
        ########FIM DO CLIENTE CADASTRADO


        $query = "select * from clientes where codigo = '{$n}'";
        $result = sisLog( $query);
        $d = mysqli_fetch_object($result);

        //$tabela_padrao = $tabela_padrao;
        $tabela_escolhida = $tabela_padrao;

        $simulacao = $vctex->Simular([
            'token' => $token,
            'cpf' => str_replace(['-',' ','.'],false,trim($d->cpf)),
            'tabela' => $tabela_padrao
        ]);
        
        $verifica = json_decode($simulacao);
        // var_dump($verifica);
        if($verifica->data->isExponentialFeeScheduleAvailable == true and $verifica->statusCode == 200){

            $simulacao = $vctex->Simular([
                'token' => $token,
                'cpf' => str_replace(['-',' ','.'],false,trim($d->cpf)),
                'tabela' => 0
            ]);

            $tabela_padrao = 0;

        }


        $consulta = uniqid();


        $query = "insert into consultas set 
                                            consulta = '{$consulta}',
                                            operadora = 'VCTEX',
                                            cliente = '{$n}',
                                            data = NOW(),
                                            tabela_escolhida = '{$tabela_escolhida}',
                                            tabela = '{$tabela_padrao}',
                                            dados = '{$simulacao}'
                                            ";
        mysqli_query($con, $query);
        $proposta = mysqli_insert_id($con);
        $verifica = mysqli_num_rows(mysqli_query($con, "select * from consultas_log where log_unico = '".md5($simulacao.$proposta)."'"));
        if(!$verifica){
            consulta_logs([
                'proposta' => $proposta,
                'consulta' => $simulacao,
                'cliente' => $n
            ]);
        }
        //Fim da simulação

        //Verificando os status e retornando ao bot

        $dados = json_decode($simulacao);

        if($dados->statusCode == 200 and $dados->data->simulationData->installments){
            $status = true;
            $valor = "R$ ".number_format($dados->data->simulationData->totalReleasedAmount,2,',','.');
            $mensagem = $verifica->descricao.$dados->message;
        }else{
            $status = false;
            $mensagem = (($verifica->descricao)?:$dados->message.' Ocorreu um erro não identificado, favor consulte o nosso atendimento');
        }
    }else{
        $status = false;
        $mensagem = 'Para prosseguir é necessário cadastrar um CPF válido';        
    }

    //########################################

    // Verifica se os dados necessários estão presentes
    if ($status) {
        // Prepara a resposta com os dados recebidos
        $response = [
            'status' => 'success',
            'mensagem' => $mensagem,
            'proposta' => $proposta,
            'valor' => number_format($valor,',','.')
        ];
    } else {
        // Resposta de erro se os dados não estiverem corretos
        $response = [
            'status' => 'error',
            'mensagem' => $mensagem,
            'proposta' => false,
            'valor' => false
        ];
    }

    $response = [
        'status' => 'success',
        'mensagem' => 'Saldo consultado com sucesso',
        'proposta' => '123456',
        'valor' => number_format(rand(100, 1000), 2,',','.')
    ];

    // Retorna o JSON de resposta
    echo trim(json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
?>