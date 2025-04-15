<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/capitalam/site/assets/lib/includes.php");
    header("location:./fgts.php");
    exit();

    $componentes = [
        
        // 'banner_principal',
        //  'banner_principal_scroll',
        //  'video',
        // 'banner_principal2',
        // 'banner_principal3',
        // 'sobre',
        // 'servicos',
        // 'time',
        // 'noticias',
        //'galeria',
        // 'banner_depoimentos',


        // 'pagina_interna',
        // 'pagina_interna2',
        // 'mais_noticias',
        // 'noticias_detalhes',
        // 'produtos_servicos',
        // 'clientes',
        // 'destaque',
        // 'video',
        // 'solucoes',
        // 'produtos_servicos2',
        // 'planos',
        // 'faq',
        'banner-reels'
        // 'contato',
        
    ];

    foreach($componentes as $i => $v){
        include("components/{$v}.php");
    }



?>