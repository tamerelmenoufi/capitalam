<style>
    .toast-container{
        position:fixed!important;
    }
    .toast-body{
        cursor:pointer;
    }
</style>

<script>
    $(function(){


        //conexão websocket
        const ws = new WebSocket("wss://ws.capitalsolucoesam.com.br/");

        ws.addEventListener('message', message => {
            // console.log(message)
            const dados = JSON.parse(message.data);
            dados.forEach(function(d){
                // console.log(d)
                if(d.type === 'chat' && d.de != '<?=$ConfWappNumero?>'){
                    // console.log(d.text);
                    if(d.text){
                        
                        if(d.tipo == 'text'){
                            texto = d.text;
                            layout = '<div class="d-flex flex-row">'+
                            '<div class="d-inline-flex flex-column m-1 p-2" style="max-width:60%; background-color:#ffffff; border:0; border-radius:10px;">'+
                            '<div class="text-start" style="border:solid 0px red;">'+d.text+'</div>' +
                            '<div class="text-end" style="color:#b6a29a; font-size:10px; border:solid 0px black;">'+d.data+'</div>' +
                            '</div>' +
                            '</div>';
                        }else if(d.tipo == 'audio'){
                            texto = d.documento;
                            layout = '<div class="d-flex flex-row">'+
                            '<div class="d-inline-flex flex-column m-1 p-2" style="max-width:60%; background-color:#ffffff; border:0; border-radius:10px;">'+
                            '<div class="text-start" style="border:solid 0px red;"><audio controls style="height:40px;" src="'+d.text+'"></audio></div>' +
                            '<div class="text-end" style="color:#b6a29a; font-size:10px; border:solid 0px black;">'+d.data+'</div>' +
                            '</div>' +
                            '</div>';                            
                        }else if(d.tipo == 'document'){
                            texto = d.documento;
                            layout = '<div class="d-flex flex-row">'+
                            '<div class="d-inline-flex flex-column m-1 p-2" style="max-width:60%; background-color:#ffffff; border:0; border-radius:10px;">'+
                            '<div class="text-start" style="border:solid 0px red;">'+
                            '<ul class="list-group">'+ 
                            '<a href="'+d.text+'" target="_blank" class="list-group-item d-flex justify-content-between align-items-center">'+ 
                            texto +
                            '<i class="fa-solid fa-up-right-from-square ms-3"></i>'+
                            '</a>'+
                            '</ul>'+
                            '</div>' +
                            '<div class="text-end" style="color:#b6a29a; font-size:10px; border:solid 0px black;">'+d.data+'</div>' +
                            '</div>' +
                            '</div>';                            
                        }else if(d.tipo == 'file'){
                            texto = d.documento;
                            layout = '<div class="d-flex flex-row">'+
                            '<div class="d-inline-flex flex-column m-1 p-2" style="max-width:60%; background-color:#ffffff; border:0; border-radius:10px;">'+
                            '<div class="text-start" style="border:solid 0px red;">'+
                            '<ul class="list-group">'+ 
                            '<a href="'+d.text+'" target="_blank" class="list-group-item d-flex justify-content-between align-items-center">'+ 
                            texto +
                            '<i class="fa-solid fa-up-right-from-square ms-3"></i>'+
                            '</a>'+
                            '</ul>'+
                            '</div>' +
                            '<div class="text-end" style="color:#b6a29a; font-size:10px; border:solid 0px black;">'+d.data+'</div>' +
                            '</div>' +
                            '</div>';                            
                        }else if(d.tipo == 'image'){
                            texto = d.documento;
                            layout = '<div class="d-flex flex-row">'+
                            '<div class="d-inline-flex flex-column m-1 p-2" style="max-width:60%; background-color:#ffffff; border:0; border-radius:10px;">'+
                            '<div class="text-start" style="border:solid 0px red;">'+
                            '<img src="'+d.text+'" style="width:100%" />'+
                            '</div>' +
                            '<div class="text-end" style="color:#b6a29a; font-size:10px; border:solid 0px black;">'+d.data+'</div>' +
                            '</div>' +
                            '</div>';                            
                        }

                        $(`div[up${d.de}]`).append(layout);
                        // $(`div[up${d.de}]`).html(layout);

                        altura = $(`div[up${d.de}]`).prop("scrollHeight");
                        div = $(`div[up${d.de}]`).height();
                        $(`div[up${d.de}]`).scrollTop((altura + div));    

                        //d.de == '92991886570' && 
                        if($("div[chatWindow]").attr("chatWindow") != "open"){
                            chatAtivo = $(`div[up${d.de}]`).attr("ativo");
                            listaUsuariosChat = $("tr[selecionarChat]").attr("selecionarChat");
                            if(!chatAtivo){
                                alerta = `<div popup${d.de} class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                                                <div class="toast-header">
                                                    <img src="img/icone.png" style="max-height:25px;" class="rounded me-2" alt="...">
                                                    <strong class="me-auto">${((d.nome)?d.nome:d.de)}</strong>
                                                    <small>11 mins ago</small>
                                                    <button close="${d.de}" type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                                </div>
                                                <div 
                                                    class="toast-body"
                                                    data-bs-toggle="offcanvas"
                                                    href="#offcanvasDireita"
                                                    role="button"
                                                    aria-controls="offcanvasDireita"
                                                    abrirMensagem="${d.codigo_cliente}"
                                                >
                                                    ${texto}
                                                </div>
                                            </div>`;
                                // $(".toast-container").append(alerta);
                                $(".toast-container").html(alerta);
                                $(`div[popup${d.de}]`).show();
                                
                                console.log(alerta)
                            }else if(listaUsuariosChat*1 > 0){
                                $.ajax({
                                    url:"financeira/clientes/wapp_lista.php",
                                    success:function(dados){
                                        $(".LateralDireita").html(dados);
                                    }
                                })                                
                            }else{
                                $.ajax({
                                    url:"financeira/clientes/wapp.php",
                                    type:"POST",
                                    data:{
                                        codigo_mensagem:d.codigo_mensagem,
                                        acao:'mensagem_lida'
                                    },
                                    success:function(dados){
                                       
                                    }
                                })
                            }

                            if($("div[listaClientesChat]").attr("listaClientesChat") == 'open'){
                                $(this).children("span").css("opacity",'1');
                            }


                            $.ajax({
                                url:"financeira/clientes/wapp_lista.php",
                                success:function(dados){
                                    $(".LateralDireita").html(dados);
                                }
                            })    
                        }
                    }
                }                    
            })

        });
        //websocked   


        $(document).on("click","button[close]",function() {
            janela = $(this).attr("close");
            // $(`div[popup${janela}]`).remove();
            $(this).parent("div").parent("div").remove();
        });


        $(document).on("click","div[abrirMensagem]",function() {
            mensagens = $(this).attr("abrirMensagem");
            $(".toast").remove();
            $.ajax({
                url:"financeira/clientes/wapp.php",
                type:"POST",
                data:{
                    mensagens
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })
        })



    })
</script>