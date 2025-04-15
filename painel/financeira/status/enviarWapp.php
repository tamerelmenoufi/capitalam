<?php
        include("{$_SERVER['DOCUMENT_ROOT']}/capitalam/painel/lib/includes.php");

        $query = "SELECT * FROM `wapp_config` where codigo = '1'";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);

        $numeros = explode(",", $d->telefones_teste);

        $query = "select * from status_mensagens where codigo = '{$_POST['envio']}'";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);

        $msg = trim(str_replace(["\n","\r"],"\\n",$d->mensagem));

        $wgw = new wgw;

        if($d->tipo == 'img'){
            foreach($numeros as $i => $n){
                $wgw->SendAnexo([
                'mensagem'=>"https://painel.capitalsolucoesam.com.br/volume/wapp/status/{$d->status}/{$d->arquivo}",
                'tipo'=>'image',
                'type'=>"image/png",
                'name'=>'',
                'para'=>$n
                ]);
            }
        }else{
            $wgw = new wgw;
            foreach($numeros as $i => $n){
                $wgw->SendTxt([
                'mensagem'=>$msg,
                'para'=>$n
                ]);
            }            
        }



?>