<?php


class wgw {

    public function key(){
        global $Conf;
        return $Conf['wgw-key'];
    }

    public function acao($dados = false){
        global $ConfWappNumero;
        $acao = [
            'd' => 'SendChatStateComposing',
            'g' => 'SendChatStateRecording',
            'p' => 'SendChatStatePaused'
        ];


        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://app.whatsgw.com.br/api/WhatsGw/'.$acao[$dados['acao']],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'apikey='.$this->key().'&phone_number='.$ConfWappNumero.'&contact_phone_number='.$dados['para'],
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // return $response;

    }

    public function SendTxt($dados = false){

      global $ConfWappNumero;

        $this->acao(['acao'=>'d','para'=>$dados['para']]);

        // sleep(20);

        $this->acao(['acao'=>'d','para'=>$dados['para']]);

        // sleep(1);

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://app.whatsgw.com.br/api/WhatsGw/Send',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
        "apikey" : "'.$this->key().'",
        "phone_number" : "'.$ConfWappNumero.'",
        "contact_phone_number" : "'.$dados['para'].'",
        "message_custom_id" : "'.date("YmdHis").'",
        "message_type" : "text",
        "message_body" : "'.$dados['mensagem'].'"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return $response;

    }

    public function SendAudio($dados = false){

      global $ConfWappNumero;

        $this->acao(['acao'=>'g','para'=>$dados['para']]);

        // sleep(20);

        $this->acao(['acao'=>'p','para'=>$dados['para']]);
        
        // sleep(1);

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://app.whatsgw.com.br/api/WhatsGw/Send',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
        "apikey" : "'.$this->key().'",
        "phone_number" : "'.$ConfWappNumero.'",
        "contact_phone_number" : "'.$dados['para'].'",
        "message_custom_id" : "'.date("YmdHis").'",
        "message_type" : "audio",
        "check_status" : "1",
        "message_body_mimetype" : "audio/mpeg",
        "message_body_filename" : "audio_'.date('YmdHis').'.mp3",
        "message_body" : "'.$dados['mensagem'].'"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        echo $response;

    }

    public function SendAnexo($dados = false){

      global $ConfWappNumero;

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://app.whatsgw.com.br/api/WhatsGw/Send',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
        "apikey" : "'.$this->key().'",
        "phone_number" : "'.$ConfWappNumero.'",
        "contact_phone_number" : "'.$dados['para'].'",
        "message_custom_id" : "'.date("YmdHis").'",
        "message_type" : "'.$dados['tipo'].'",
        "check_status" : "1",
        "message_body_mimetype" : "'.$dados['type'].'",
        "message_body_filename" : "'.$dados['name'].'",
        "message_body" : "'.$dados['mensagem'].'",
        "download":1
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);

    }
    
}