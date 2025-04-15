<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");

    if($_POST['acao'] == 'mensagem_lida'){
        $query = "update wapp_chat set recebida = '1' where codigo = '{$_POST['codigo_mensagem']}'";
        mysqli_query($con, $query);
        exit();
    }

    if($_POST['acao'] == 'enviarText'){
        $query = "insert into wapp_chat set 
                                            de = '{$_POST['de']}', 
                                            para = '{$_POST['para']}', 
                                            tipo = 'text', 
                                            mensagem = '{$_POST['mensagem']}', 
                                            usuario = '{$_SESSION['ProjectPainel']->codigo}', 
                                            data = NOW()";
        if(mysqli_query($con, $query)){
            $wgw = new wgw;
            $wgw->SendTxt([
              'mensagem'=>$_POST['mensagem'],
              'para'=>'55'.$_POST['para']
            ]);
        }
        exit();
    }

    if($_POST['acao'] == 'enviarAudio'){

        $base64 = explode("base64,", $_POST['mensagem']);

        if(!is_dir("{$_SERVER['DOCUMENT_ROOT']}/painel/src/volume/wappChat")) mkdir("{$_SERVER['DOCUMENT_ROOT']}/painel/src/volume/wappChat");
        if(!is_dir("{$_SERVER['DOCUMENT_ROOT']}/painel/src/volume/wappChat/".date("Y-m-d"))) mkdir("{$_SERVER['DOCUMENT_ROOT']}/painel/src/volume/wappChat/".date("Y-m-d"));
        $mensagem = date("Y-m-d")."/".md5($_POST['mensagem'].date("YmdHis")).".ogg";
        file_put_contents("{$_SERVER['DOCUMENT_ROOT']}/painel/src/volume/wappChat/{$mensagem}", base64_decode($base64[1]));

        $query = "insert into wapp_chat set de = '{$_POST['de']}', para = '{$_POST['para']}', tipo = 'audio', mensagem = '{$mensagem}', usuario = '{$_SESSION['ProjectPainel']->codigo}', data = NOW()";
        if(mysqli_query($con, $query)){
            $wgw = new wgw;
            $wgw->SendAudio([
              'mensagem'=>trim($base64[1]),
              'para'=>'55'.$_POST['para']
            ]);
        }
        exit();
    }


    if($_POST['acao'] == 'enviarAnexos'){

        //tipo, type, name, base64

        $base64 = explode("base64,", $_POST['base64']);

        $ext = explode(".",$_POST['name']);
        $ext = ".".$ext[count($ext)-1];

        if(!is_dir("{$_SERVER['DOCUMENT_ROOT']}/painel/src/volume/wappChat")) mkdir("{$_SERVER['DOCUMENT_ROOT']}/painel/src/volume/wappChat");
        if(!is_dir("{$_SERVER['DOCUMENT_ROOT']}/painel/src/volume/wappChat/".date("Y-m-d"))) mkdir("{$_SERVER['DOCUMENT_ROOT']}/painel/src/volume/wappChat/".date("Y-m-d"));
        $mensagem = date("Y-m-d")."/".md5($_POST['base64'].date("YmdHis")).$ext;
        file_put_contents("{$_SERVER['DOCUMENT_ROOT']}/painel/src/volume/wappChat/{$mensagem}", base64_decode($base64[1]));

        $query = "insert into wapp_chat set 
                                            de = '{$_POST['de']}', 
                                            para = '{$_POST['para']}', 
                                            tipo = '{$_POST['tipo']}', 
                                            documento = '{$_POST['name']}', 
                                            mensagem = '{$mensagem}', 
                                            usuario = '{$_SESSION['ProjectPainel']->codigo}', 
                                            data = NOW()";
        if(mysqli_query($con, $query)){
            $wgw = new wgw;
            $wgw->SendAnexo([
              'mensagem'=>"{$localPainel}/src/volume/wappChat/{$mensagem}",
              'tipo'=>$_POST['tipo'],
              'type'=>$_POST['type'],
              'name'=>$_POST['name'],
              'para'=>'55'.$_POST['para']
            ]);
        }
        
        switch($_POST['tipo']){
            case 'document':{
                $retorno = "<ul class='list-group'> 
                                <a href='{$localPainel}/src/volume/wappChat/{$mensagem}' target='_blank' class='list-group-item d-flex justify-content-between align-items-center'> 
                                    {$_POST['name']}
                                    <i class='fa-solid fa-up-right-from-square ms-3'></i>
                                </a>
                            </ul>";
                break;
            }
            case 'file':{
                $retorno = "<ul class='list-group'> 
                                <a href='{$localPainel}/src/volume/wappChat/{$mensagem}' target='_blank' class='list-group-item d-flex justify-content-between align-items-center'> 
                                    {$_POST['name']}
                                    <i class='fa-solid fa-up-right-from-square ms-3'></i>
                                </a>
                            </ul>";
                break;
            }
            case 'image':{
                $retorno = "<img src='{$localPainel}/src/volume/wappChat/{$mensagem}' style='width:100%' />";
                break;
            }
        }

        echo $retorno;
        exit();
    }

    // if($_POST['acao'] == 'receber'){
    //     $query = "select * from wapp_chat where de = '{$_POST['de']}' and para = '{$_POST['para']}' and data > '{$_POST['ultimo_acesso']}' order by data desc ";
    //     $result = mysqli_query($con, $query);
    //     $retorno = [];
    //     $update = [];
    //     while($d = mysqli_fetch_object($result)){
    //         $retorno[] = [
    //             'de'=>$d->de,
    //             'para'=>$d->para,
    //             'mensagem'=>$d->mensagem,
    //             'data'=>dataBr($d->data),
    //             'ultimo_acesso'=>$d->data
    //         ];
    //         $update[] = $d->codigo;
    //     }
    //     echo json_encode($retorno);
    //     if($update){
    //         mysqli_query($con, "update wapp_chat set recebida = '1' where codigo in(".implode(', ', $update).") and recebida != '1'");
    //     }
    //     exit();
    // }

    $c = mysqli_fetch_object(mysqli_query($con, "select * from clientes where codigo = '{$_POST['mensagens']}'"));

    $phoneNumber = str_replace([' ','-','(',')'],false,trim($c->phoneNumber));

?>

<style>
    .Titulo<?=$md5?>{
        position:absolute;
        left:60px;
        top:8px;
        right:20px;
        z-index:0;
    }
    .topo<?=$md5?>{
        position:absolute;
        background:#eee;
        left:0;
        right:0;
        height:40px;
        top:50px;
        padding:10px;
    }
    .palco<?=$md5?>{
        position:absolute;
        left:0;
        top:90px;
        bottom:85px;
        right:0;
        background-color:#f7f7f7;
        overflow:auto;
        border-left:3px solid #e4e4e4;
        padding:10px;      
    }    
    .rodape<?=$md5?>{
        position:absolute;
        background:#eee;
        left:0;
        right:0;
        bottom:0px;    
        height:85px;    
    }
    /* div[listaClientesChat]{
        cursor:pointer;
    } */


    /* Estilos do microfone */
    .microfone {
    width: 50px;
    height: 50px;
    background-color: transparent;
    border-radius: 50%;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    }

    /* Estilos do ícone de microfone */
    .icon {
    z-index:1;
    }

    /* Estilos do "rádio luminoso" */
    .radio {
        width: 50px;
        height: 50px;
        background-color: red;
        border-radius: 50%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        animation: radio-pulse 1s ease-in-out infinite ;
        opacity:0;
    }

    /* Animação do "rádio luminoso" */
    @keyframes radio-pulse {
        0% {
            width: 5px;
            height: 5px;
        }
        100% {
            width: 50px;
            height: 50px;
            opacity: 0;
        }
    }
    /* Estilo do microfone */
    .microfone{
        cursor:pointer;
    }
    .exibe{
        display:block!important;
    }

    .oculta{
        display:none!important;
    }

    i[enviar]{
        cursor:pointer;
    }
    .anexos{
        position:absolute;
        left:10px;
        bottom:70px;
        width:auto;
        height:auto;
        border:solid 1px #ccc;
        border-radius:10px;
        padding:10px;
        background:#eee;
        cursor:pointer;
        display:none;
    }
    .botao_anexo{
        width:40px;
        height:40px;
        color:#fff;
        border-radius:100%;
        cursor:pointer;
    }
    .nome_botao_anexo{
        color:#a1a1a1;
        font-size:10px;
        text-align:center;
        width:100%;
        cursor:pointer;
    }
    input[type="file"]{
        position:absolute;
        left:0;
        right:0;
        top:0;
        bottom:0;
        opacity:0;
        cursor:pointer;
    }
</style>
<h4 class="Titulo<?=$md5?>">
    <div class="d-flex justify-content-between align-items-center">
        <span>Mensagens WhatsApp</span>
        <!-- <div style="position:relative" listaClientesChat="open">
            <span style="position:absolute; background-color:#dcf8c6; border-radius:100%; width:10px; height:10px; right:0px; top:-5px; opacity:0;"></span>
            <i class="fa-solid fa-comments"></i>
        </div> -->
        
    </div>
</h4>
<div class="topo<?=$md5?>"><i class="fa-regular fa-comment-dots"></i> <?=$c->nome?></div>
<div chatWindow="open" class="palco<?=$md5?>" up<?=$phoneNumber?>>
    <?php
        $query = "select * from wapp_chat where (de = '{$ConfWappNumero}' and para = '{$phoneNumber}' or de = '{$phoneNumber}' and para = '{$ConfWappNumero}') order by data asc";
        $result = mysqli_query($con, $query);
        $update = [];
        while($m = mysqli_fetch_object($result)){


            switch($m->tipo){
                case 'text':{
                    $mensagem = $m->mensagem;
                    break;
                }
                case 'audio':{
                    $mensagem = "<audio controls style='height:40px;' src='{$localPainel}/src/volume/wappChat/{$m->mensagem}'></audio>";
                    break;
                }
                case 'document':{
                    //".(($m->de == $ConfWappNumero)?"Arquivo Enviado":"Arquivo Recebido")."
                    $mensagem = "<ul class='list-group'> 
                                    <a href='{$localPainel}/src/volume/wappChat/{$m->mensagem}' target='_blank' class='list-group-item d-flex justify-content-between align-items-center'> 
                                        {$m->documento}
                                        <i class='fa-solid fa-up-right-from-square ms-3'></i>
                                    </a>
                                </ul>";
                    break;
                }
                case 'file':{
                    //".(($m->de == $ConfWappNumero)?"Arquivo Enviado":"Arquivo Recebido")."
                    $mensagem = "<ul class='list-group'> 
                                    <a href='{$localPainel}/src/volume/wappChat/{$m->mensagem}' target='_blank' class='list-group-item d-flex justify-content-between align-items-center'> 
                                        {$m->documento}
                                        <i class='fa-solid fa-up-right-from-square ms-3'></i>
                                    </a>
                                </ul>";
                    break;
                }
                case 'image':{
                    $mensagem = "<img src='{$localPainel}/src/volume/wappChat/{$m->mensagem}' style='width:100%' />";
                    break;
                }
                default:{
                    $mensagem = false;
                }
            }


            if($m->de == $ConfWappNumero){
    ?>
            <div class="d-flex flex-row-reverse">
                <div class="d-inline-flex flex-column m-1 p-2" style="max-width:60%; background-color:#dcf8c6; border:0; border-radius:10px;">
                    <div class="text-start" style="border:solid 0px red;"><?=$mensagem?></div>
                    <div class="text-end" style="color:#b6a29a; font-size:10px; border:solid 0px black;"><?=dataBr($m->data)?></div>
                </div>
            </div>
    <?php
            }else{

                $update[] = $m->codigo;
    ?>
            <div class="d-flex flex-row">
                <div class="d-inline-flex flex-column m-1 p-2" style="max-width:60%; background-color:#ffffff; border:0; border-radius:10px;">
                    <div class="text-start" style="border:solid 0px red;"><?=$mensagem?></div>
                    <div class="text-end" style="color:#b6a29a; font-size:10px; border:solid 0px black;"><?=dataBr($m->data)?></div>
                </div>
            </div>
    <?php
            }
            $ultimo_acesso = $m->data;
        }

        if($update){
            mysqli_query($con, "update wapp_chat set recebida = '1' where codigo in(".implode(', ', $update).") and recebida != '1'");
        }

        $msgs = mysqli_fetch_object(mysqli_query($con, "select count(*) as qt from wapp_chat where recebida != '1'"));
    ?>
</div>
<div class="rodape<?=$md5?>">
    <div class="d-flex justify-content-between align-items-center m-3">
        <div class="mensagem_texto exibe w-100">
            <div class="d-flex justify-content-between align-items-center w-100">
                <i class="fa-solid fa-paperclip p-3 grupo_anexos" status="close" style="cursor:pointer"></i>
                <input type="text" autocomplete="off" class="form-control p-3" id="chatMensagem" ultimo_acesso="<?=$ultimo_acesso?>" aria-describedby="chatMensagem">
            </div>
        </div>
        <div class="mensagem_audio oculta w-100">
            <div class="d-flex justify-content-between align-items-center w-100">
                <i statusGravacao="gravando" class="fa-solid fa-microphone p-2 text-danger"></i>
                <div class="form-control">  <audio controls id="audioPlayer" style="display:none; height:40px;"></audio> <span class="m-2" gravando>Gravando ...</span></div>
            </div>
        </div>        
        <div class="microfone" acao="normal">
            <div class="radio"></div>
            <i class="fa-solid fa-microphone p-3 icon"></i>
        </div>
        <i enviar class="fa-regular fa-paper-plane p-3"></i>
    </div>
</div>

<div class="anexos">
    <div class="row">
        <div class="col" style="position:relative;">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <input type="file" tipo="document" accept=".pdf, application/pdf">
                <div class="d-flex justify-content-center align-items-center botao_anexo" style="background-color:rgba(var(--bs-success-rgb), 1)">
                    <i class="fa-solid fa-file"></i>
                </div>
                <div class="nome_botao_anexo">PDF</div>
            </div>
        </div>

        <div class="col" style="position:relative;">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <input type="file" tipo="image" accept="image/png, image/jpeg, image/jpg, image/gif" >
                <div class="d-flex justify-content-center align-items-center botao_anexo" style="background-color:rgba(var(--bs-warning-rgb), 1)">
                    <i class="fa-regular fa-image"></i>
                </div>
                <div class="nome_botao_anexo">Imagem</div>
            </div>
        </div>

        <!-- <div class="col">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <div class="d-flex justify-content-center align-items-center botao_anexo" style="background-color:rgb(var(--bs-primary-rgb), 1)">
                    <i class="fa-solid fa-file-audio"></i>
                </div>
                <div class="nome_botao_anexo">Áudio</div>
            </div>
        </div>

        <div class="col">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <div class="d-flex justify-content-center align-items-center botao_anexo" style="background-color:rgba(var(--bs-danger-rgb), 1)">
                    <i class="fa-solid fa-file-video"></i>
                </div>
                <div class="nome_botao_anexo">Vídeo</div>
            </div>
        </div> -->
    </div>
</div>


<script>
    $(function(){

        Carregando('none');
        
        altura = $(".palco<?=$md5?>").prop("scrollHeight");
        div = $(".palco<?=$md5?>").height();
        $(".palco<?=$md5?>").scrollTop(altura + div);
        $(".toast").remove();
        

        // if($("div[listaClientesChat]").attr("listaClientesChat") == 'open'){
        //     $(this).children("span").css("opacity",'<?=(($msgs->qt)?'1':'0')?>');
        // }

        $(".grupo_anexos").click(function(){
            status = $(".grupo_anexos").attr("status");
            if(status == 'open'){
                $(".anexos").hide();
                $(".grupo_anexos").attr("status","close");
            }else{
                $(".anexos").show();
                $(".grupo_anexos").attr("status","open");
            }
        })

        function fecharGrupoAnexos(){
            status = $(".grupo_anexos").attr("status");
            if(status == 'open'){
                $(".anexos").hide();
                $(".grupo_anexos").attr("status","close");
            }            
        }


        $(".microfone, #chatMensagem").click(function(){
            fecharGrupoAnexos()
        })

        ///////////////////////////////////////FUNCAO DO AUDIO//////////////////////////////////
        var mediaRecorder;
        var chunks = [];
        $(".microfone").click(function(){
            acao = $(this).attr("acao");

            if(acao == "normal"){
                
                chunks = [];
                $(".radio").css("opacity","1");
                $(".mensagem_texto").removeClass("exibe");
                $(".mensagem_texto").addClass("oculta");
                
                $(".mensagem_audio").removeClass("oculta");
                $(".mensagem_audio").addClass("exibe");

                $("#audioPlayer").css("display","none");
                $("span[gravando]").css("display","block");

                $("i[statusGravacao]").addClass("fa-microphone");
                $("i[statusGravacao]").removeClass("fa-trash-can");
                $("i[statusGravacao]").attr("statusGravacao","gravando");
                $("i[statusGravacao]").css("cursor","normal");

                $("i[enviar]").css("display","none");

                $("#chatMensagem").val('');

                /////////////Gravação/////////////////////
                console.log('audio iniciado')
                navigator.mediaDevices.getUserMedia({audio: true})
                .then(function(stream) {
                    mediaRecorder = new MediaRecorder(stream);
                    mediaRecorder.ondataavailable = function(e) {
                        chunks.push(e.data);
                    };
                    mediaRecorder.start();
                })
                .catch(function(err) {
                    console.error('Erro ao acessar o microfone: ', err);
                });
                ////////Fim da gravação////////////////////////

                
                $(this).attr("acao","gravando");
            }else{
                $(".radio").css("opacity","0");
                $('#audioPlayer').attr('src', '');
                $("i[enviar]").css("display","block");

                ///////////////Iniçio da açao de gravação/////////////////////////
                console.log('audio finalizado')
                if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                console.log('audio acao')
                    mediaRecorder.stop();
                    mediaRecorder.onstop = function() {
                        var blob = new Blob(chunks, { 'type' : 'audio/ogg; codecs=opus' });
                        // var audioURL = URL.createObjectURL(blob);

                        var reader = new FileReader();
                        reader.onload = function(event) {
                            var base64Data = event.target.result;
                            // Use a base64Data conforme necessário, por exemplo, envie para o servidor
                            $('#audioPlayer').attr('src', base64Data);
                        };
                        reader.readAsDataURL(blob);

                        // $('#audioPlayer').attr('src', audioURL);
                        // $('#audioPlayer').show();
                        $("#audioPlayer").css("display","block");
                        $("span[gravando]").css("display","none");

                        $("i[statusGravacao]").removeClass("fa-microphone");
                        $("i[statusGravacao]").addClass("fa-trash-can");
                        $("i[statusGravacao]").attr("statusGravacao","play");
                        $("i[statusGravacao]").css("cursor","pointer");
                    };
                }  
                /////////////////Fim da ação//////////////////////////////////////


                
                $(this).attr("acao","normal");
            }
        })


        function removePlayer(){
            var audioPlayer = $('#audioPlayer')[0];
            $(".mensagem_texto").removeClass("oculta");
            $(".mensagem_texto").addClass("exibe");
            
            $(".mensagem_audio").removeClass("exibe");
            $(".mensagem_audio").addClass("oculta"); 

            if (!audioPlayer.paused) {
                audioPlayer.pause();
            }

            $('#audioPlayer').removeAttr("src");
        }

        $("i[statusGravacao]").click(function(){
            acao = $(this).attr("statusGravacao");
            if(acao == 'play'){
                removePlayer()
            }

        })

        //////////////////////////////////////FUNCAO DO AUDIO//////////////////////////////////


        function EnviaMensagemText(val){
            
            layout = '<div class="d-flex flex-row-reverse">'+
                     '<div class="d-inline-flex flex-column m-1 p-2" style="max-width:60%; background-color:#dcf8c6; border:0; border-radius:10px;">'+
                     '<div class="text-start" style="border:solid 0px red;">'+val+'</div>' +
                     '<div class="text-end" style="color:#b6a29a; font-size:10px; border:solid 0px black;">'+formatarDataHora()+' </div>' +
                     '</div>' +
                     '</div>';

            $(".palco<?=$md5?>").append(layout);

            $("#chatMensagem").val('');

            altura = $(".palco<?=$md5?>").prop("scrollHeight");
            div = $(".palco<?=$md5?>").height();
            $(".palco<?=$md5?>").scrollTop(altura + div);

            $.ajax({
                url:"financeira/wapp/wapp.php",
                type:"POST",
                data:{
                    mensagem:val,
                    de:'<?=$ConfWappNumero?>',
                    para:'<?=$phoneNumber?>',
                    acao:'enviarText'
                },
                success:function(dados){

                }
            })
        }


        function EnviaMensagemAudio(val){

            base64 = [];
            
            layout = '<div class="d-flex flex-row-reverse">'+
                     '<div class="d-inline-flex flex-column m-1 p-2" style="max-width:60%; background-color:#dcf8c6; border:0; border-radius:10px;">'+
                     '<div class="text-start" style="border:solid 0px red;"><audio controls style="height:40px;" src="'+val+'"></audio></div>' +
                     '<div class="text-end" style="color:#b6a29a; font-size:10px; border:solid 0px black;">'+formatarDataHora()+' </div>' +
                     '</div>' +
                     '</div>';

            $(".palco<?=$md5?>").append(layout);

            altura = $(".palco<?=$md5?>").prop("scrollHeight");
            div = $(".palco<?=$md5?>").height();
            $(".palco<?=$md5?>").scrollTop(altura + div);

            $.ajax({
                url:"financeira/wapp/wapp.php",
                type:"POST",
                data:{
                    mensagem:val,
                    de:'<?=$ConfWappNumero?>',
                    para:'<?=$phoneNumber?>',
                    acao:'enviarAudio'
                },
                success:function(dados){

                }
            })
        }


        function EnviaMensagemAnexos(tipo, type, name, base64 ){

            $.ajax({
                url:"financeira/wapp/wapp.php",
                type:"POST",
                data:{
                    tipo,
                    type,
                    name,
                    base64,
                    de:'<?=$ConfWappNumero?>',
                    para:'<?=$phoneNumber?>',
                    acao:'enviarAnexos'
                },
                success:function(dados){

                    layout = '<div class="d-flex flex-row-reverse">'+
                    '<div class="d-inline-flex flex-column m-1 p-2" style="max-width:60%; background-color:#dcf8c6; border:0; border-radius:10px;">'+
                    '<div class="text-start" style="border:solid 0px red;">'+dados+'</div>' +
                    '<div class="text-end" style="color:#b6a29a; font-size:10px; border:solid 0px black;">'+formatarDataHora()+' </div>' +
                    '</div>' +
                    '</div>';

                    fecharGrupoAnexos();

                    $(".palco<?=$md5?>").append(layout);

                    altura = $(".palco<?=$md5?>").prop("scrollHeight");
                    div = $(".palco<?=$md5?>").height();
                    $(".palco<?=$md5?>").scrollTop(altura + div);

                }
            })
        }
        
        
        $("#chatMensagem").keypress(function(e){
            val = $(this).val();
            if(e.which == 13 && val) {
                EnviaMensagemText(val);
            }
        });

        // $("div[listaClientesChat]").click(function(){
        //     Carregando();
        //     $.ajax({
        //             url:"financeira/wapp/wapp_lista.php",
        //             success:function(dados){
        //                 $(".mensagensaWapp").html(dados);
        //             }
        //         })
        // })

        $("i[enviar]").off('click').on('click', function(){
            fecharGrupoAnexos()
            audio = '';
            val = '';
            audio = $('#audioPlayer').attr("src");
            val = $("#chatMensagem").val();

            if(audio){
                /// acao de envio
                EnviaMensagemAudio(audio);
                removePlayer();
            }else if(val) {
                EnviaMensagemText(val);
                $("#chatMensagem").val('');
            }

        });

        

        if (window.File && window.FileList && window.FileReader) {

            $('input[type="file"]').change(function () {

                tipo = $(this).attr("tipo");

                if ($(this).val()) {
                    var files = $(this).prop("files");
                    for (var i = 0; i < files.length; i++) {
                        (function (file) {
                            var fileReader = new FileReader();
                            fileReader.onload = function (f) {


                                var type = file.type;
                                var name = file.name;

                                if(tipo == 'image'){
                                //*
                                //////////////////////////////////////////////////////////////////

                                var img = new Image();
                                img.src = f.target.result;

                                img.onload = function () {

                                    // CREATE A CANVAS ELEMENT AND ASSIGN THE IMAGES TO IT.
                                    var canvas = document.createElement("canvas");

                                    var value = 50;

                                    // RESIZE THE IMAGES ONE BY ONE.
                                    w = img.width;
                                    h = img.height;
                                    img.width = 800 //(800 * 100)/img.width // (img.width * value) / 100
                                    img.height = (800 * h / w) //(img.height/100)*img.width // (img.height * value) / 100

                                    var ctx = canvas.getContext("2d");
                                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                                    canvas.width = img.width;
                                    canvas.height = img.height;
                                    ctx.drawImage(img, 0, 0, img.width, img.height);

                                    var Base64 = canvas.toDataURL(file.type); //f.target.result;

                                    // $("#encode_file").val(Base64);
                                    // $("#encode_file").attr("nome", name);
                                    // $("#encode_file").attr("tipo", type);

                                    EnviaMensagemAnexos(tipo, type, name, Base64 )



                                }

                                //////////////////////////////////////////////////////////////////
                                //*/
                                }else{

                                    var Base64 = f.target.result;

                                    // $("#base64").val(Base64);
                                    // $("#imagem_tipo").val(type);
                                    // $("#imagem_nome").val(name);

                                    EnviaMensagemAnexos(tipo, type, name, Base64 )


                                }

                            };
                            fileReader.readAsDataURL(file);
                        })(files[i]);
                    }
                }
            });
        } else {
            alert('Nao suporta HTML5');
        }

    })
</script>