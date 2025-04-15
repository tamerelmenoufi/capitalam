<?php

// exit();

$dados = [
    'from_name' => 'Mailgun - MOHATRON',
    'from_email' => 'mailgun@moh1.com.br',
    'subject' => 'Envio html API\'Mailgun da Capital Financeira'. date("d/m/Y H:i:s"),
    'html' => '<html><h2>Título da html de teste</h2><p>Descrição da html de teste da API</p></html>',
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
            ['to_name' => 'Tamer Elmenoufi', 'to_email' => 'tamer.menoufi@gmail.com'],
            // ['to_name' => 'Tamer Mohamed', 'to_email' => 'tamer@mohatron.com.br'],
    ]
];



        #############################################################################
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


        var_dump($result);