<?php
    function TwoCaptchaV2($license_key,$url,$key_Captcha){
        /**
         * ! Esse é quebra recaptcha do TwoCaptchaV2
         */
        $config_captcha = "https://2captcha.com/in.php?key=$license_key&method=userrecaptcha&googlekey=$key_Captcha&pageurl=$url";
        $curl = curl_init();
        curl_setopt_array($curl,[
            CURLOPT_URL => $config_captcha,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => -1,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_CUSTOMREQUEST => "GET",
        ]);
        $result = curl_exec($curl);
        $id_captcha = explode("|", $result);
        $id = $id_captcha[1];
        curl_close($curl);
        if($id){
            $checking = true;
            while($checking){
                $resolve_captcha = "https://2captcha.com/res.php?key=$license_key&action=get&id=$id&json=true";
                $curl = curl_init();
                curl_setopt_array($curl,[
                    CURLOPT_URL => $resolve_captcha,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_MAXREDIRS => -1,
                    CURLOPT_TIMEOUT => 20,
                    CURLOPT_CUSTOMREQUEST => "GET",
                ]);
                $result = curl_exec($curl);
                $result = json_decode($result, true);
                curl_close($curl);
                if($result["status"]==1){
                    $checking = false;
                    echo "RECHAPT: ".$result["request"]."\n";
                    return $result["request"];
                }else{
                    echo "ESTAMOS RESOLVENDO O CAPTCHA\n";
                }
                sleep(15);
            }
        }
    }
    function ZigNet($boleto){
        $key = '6Ley_1UeAAAAALZvXpRdHLWWpU5JugGiqndnnKki';
        $url = "https://www.zignet.com.br/en/parcele-boletos";
        $licence = "";
        $TwoCaptcha = TwoCaptchaV2($licence, $url,$key);
        $url = 'https://nfbti53lkd.execute-api.us-east-2.amazonaws.com/dev/comunix/consult?email=';
        $header = [
            'Host: nfbti53lkd.execute-api.us-east-2.amazonaws.com',
            'Sec-Ch-Ua: "Not(A:Brand";v="24", "Chromium";v="122"',
            'Accept: application/json, text/plain, */*',
            'Content-Type: application/json;charset=UTF-8',
            'Sec-Ch-Ua-Mobile: ?0',
            'Terminal: 4068707027',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.6261.112 Safari/537.36',
            'Sec-Ch-Ua-Platform: "Windows"',
            'Origin: https://www.zignet.com.br',
            'Sec-Fetch-Site: cross-site',
            'Sec-Fetch-Mode: cors',
            'Sec-Fetch-Dest: empty',
            'Referer: https://www.zignet.com.br/',
            'Priority: u=1, i'
        ];
        $post = '{"codigoCliente":31,"campos":[],"tokenReCaptcha":"'.$TwoCaptcha.'"}';
        $curl = curl_init();
        curl_setopt_array($curl,[
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => -1,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_CUSTOMREQUEST => "POST",
        ]);
        $result = curl_exec($curl);
        $result = json_decode($result);
        print_r($result);
        $codigo =  $result["data"]["codigo"];
        curl_close($curl);
        /** */
        $config_zignet = "https://www.zignet.com.br/api/validateOtherDebt?codigo=$codigo&billsCode=23792790099407300000518004132504396560000113807";
        $curl = curl_init();
        curl_setopt_array($curl,[
            CURLOPT_URL => $config_zignet,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => -1,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_CUSTOMREQUEST => "GET",
        ]);
        $result = curl_exec($curl);
        $result = json_decode($result,true);
        print_r($result);
    }
    ZigNet("sadasd")
?>