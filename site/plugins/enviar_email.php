<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/site/assets/lib/includes.php");

    $query = "select * from configuracoes where codigo = '1'";
    $result = sisLog( $query);
    $d = mysqli_fetch_object($result);

// exit();

    $dados_retorno = [
        'from_name' => addslashes($d->email_assinatura),
        'from_email' => trim($d->email),
        'subject' => addslashes('RE: Contato Site: '.addslashes($_POST['name'])),
        'html' => $d->email_resposta,
    /*    'attachment' => [
                './img_bk.png',
                './cliente-mohatron.xls',
                './formulario_prato_cheio.pdf',
        ],
        'inline' => [
                './img_bk.png',
        ],
    */
        'to' => [
                ['to_name' => addslashes($_POST['name']), 'to_email' => trim($_POST['email'])],
                // ['to_name' => 'Tamer Mohamed', 'to_email' => 'tamer@mohatron.com.br'],
        ]
    ];



    $dados = [
        'from_name' => addslashes($_POST['name']),
        'from_email' => trim($_POST['email']),
        'subject' => addslashes('Contato Site: '.addslashes($_POST['name'])),
        'html' => $_POST['message'],
    /*    'attachment' => [
                './img_bk.png',
                './cliente-mohatron.xls',
                './formulario_prato_cheio.pdf',
        ],
        'inline' => [
                './img_bk.png',
        ],
    */
        'to' => [
                ['to_name' => addslashes($d->email_assinatura), 'to_email' => trim($d->email)],
                // ['to_name' => 'Tamer Mohamed', 'to_email' => 'tamer@mohatron.com.br'],
        ]
    ];


        #############################################################################
        $content = http_build_query($dados);

        $context = stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'content' => $content,
            )
        ));

        $result = file_get_contents("http://mailgun.mohatron.com/send.php", null, $context);
        #############################################################################

        #############################################################################
        $content = http_build_query($dados_retorno);

        $context = stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'content' => $content,
            )
        ));

        $result = file_get_contents("http://mailgun.mohatron.com/send.php", null, $context);
        #############################################################################
        $result = json_decode($result);

        echo "Mensagem enviada com sucesso!";



        // if($result->status == 'success'){
        //     echo "Mensagem enviada com sucesso!";
        // }else{
        //     echo "Ocorreu um erro, favor tente mais tarde!";
        // }