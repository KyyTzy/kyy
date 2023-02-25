<?php

error_reporting(0);

$username = input("[?] 085861184371 ");

$password = input("[?] Asd12345* ");

echo PHP_EOL;

dariawalaja:

$deviceId = generateRandomString(36);

$data = '{"name":"web-sso","secret_key":"3e53440178c568c4f32c170f","device_type":"web","device_id":"'.$deviceId.'"}';

$headers = [

    "Host: api-accounts.edot.id",

    "Content-Type: application/json",

    "Origin: https://accounts.edot.id",

    "Connection: keep-alive",

    "Accept: */*",

    "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",

    "Content-Length: ".strlen($data),

    "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",

    "Accept-Encoding: gzip, deflate, br"

];

        

$getToken = curl("https://api-accounts.edot.id/api/token/get", $data, $headers);

$token_code = get_between($getToken[1], '"token_code":"', '",');

if (strpos($getToken[1], '"code":200,')) {

    echo "[-] Status Code 200";

    echo " -> token_code: ".$token_code.PHP_EOL;

    $data = '{"client_id":"22234a71a7e278535be79b4c5a390e97","response_type":"code","state":"0jArvjbr2NJLbKD85mc1rjT","username":"'.$username.'","password":"'.$password.'"}';

    $headers = [

        "Host: api-accounts.edot.id",

        "Content-Type: application/json",

        "Origin: https://accounts.edot.id",

        "Accept-Encoding: gzip, deflate, br",

        "Connection: keep-alive",

        "Accept: */*",

        "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148",

        "Content-Length: ".strlen($data),

        "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",

        "sso-token: ".$token_code

    ];

    $request_login = curl("https://api-accounts.edot.id/api/user/login", $data, $headers);

    $aksestoken = get_between($request_login[1], '?code=', '&state');

    if (strpos($request_login[1], '"redirect_url":')) {

        echo "[-] Login Sukses";

        $data = '{"code":"'.$aksestoken.'","client_id":"22234a71a7e278535be79b4c5a390e97","client_secret":"82abe1a8e0afe8f9b28754b3","grant_type":"authorization_code"}';

        $headers = [

            "Host: api-accounts.edot.id",

            "Content-Type: application/json",

            "lang: en",

            "Connection: keep-alive",

            "platform: ios",

            "Accept: */*",

            "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",

            "Content-Length: ".strlen($data),

            "Accept-Encoding: gzip, deflate, br",

            "User-Agent: ehashtag/1 CFNetwork/1399 Darwin/22.1.0"

        ];

        $ouath_token = curl("https://api-accounts.edot.id/api/oauth/token", $data, $headers);

        $access_token = get_between($ouath_token[1], '"access_token":"', '","');

        echo " -> Refresh Token: ".$access_token.PHP_EOL;

        if (strpos($ouath_token[1], '"access_token":"')) {

            $headers = [

                "Host: shop-api.edot.id",

                "Accept: application/json",

                "Content-Type: application/json",

                "Connection: keep-alive",

                "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",

                "Authorization: Bearer ".$access_token,

                "Accept-Encoding: gzip, deflate, br",

                "User-Agent: ehashtag/1 CFNetwork/1399 Darwin/22.1.0"

            ];

            $check_rek = curl("https://shop-api.edot.id/api/bank/list_account?type_rekening=personal&id_store_sso=", null, $headers);

            $check_saldo = curl("https://shop-api.edot.id/api/disburse/balance?type_rekening=personal", null, $headers);

            $acc_id = get_between($check_rek[1], '"id":', ',"');

            $bank_no = get_between($check_rek[1], '"bank_no":"', '"');

            $bank_name = get_between($check_rek[1], '"bank_name":"', '"');

            $account_name = get_between($check_rek[1], '"account_name":"', '"');

            echo "[-] No. Rek: ".$bank_no." -> Nama: ".$account_name." -> Bank: ".$account_name.PHP_EOL;

            $balance = get_between($check_saldo[1], '"desc_balance":"', '","');

            if (strpos($check_saldo[1], '"code":200,')) {

                echo "[-] Saldo: ".$balance.PHP_EOL;

                $jumlah_wd = input("[?] WD Berapa (Fee: Rp4.620) ");

                $nomor_hp = input("[?] Nomor (628xxxx) ");

                $data = '{"telephone":"'.$nomor_hp.'","type":1}';

                $headers = [

                    "Host: shop-api.edot.id",

                    "Accept: application/json",

                    "Content-Type: application/json",

                    "User-Agent: ehashtag/1 CFNetwork/1399 Darwin/22.1.0",

                    "Connection: keep-alive",

                    "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",

                    "Authorization: Bearer ".$access_token,

                    "Content-Length: ".strlen($data),

                    "Accept-Encoding: gzip, deflate, br"

                ];

                $request_otp = curl("https://shop-api.edot.id/api/otp/request", $data, $headers);

                $otp_token = get_between($request_otp[1], '"otp_token":"', '",');

                if (strpos($request_otp[1], '"code":200,')) {

                    echo "[-] Status Code 200";

                    echo " -> otp_token: ".$otp_token.PHP_EOL;

                    $otp = input("[?] OTP ");

                    $data = '{"otp_code":"'.$otp.'","otp_token":"'.$otp_token.'"}';

                    $headers = [

                        "Host: shop-api.edot.id",

                        "Accept: application/json",

                        "Content-Type: application/json",

                        "User-Agent: ehashtag/1 CFNetwork/1399 Darwin/22.1.0",

                        "Connection: keep-alive",

                        "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",

                        "Authorization: Bearer ".$access_token,

                        "Content-Length: ".strlen($data),

                        "Accept-Encoding: gzip, deflate, br"

                    ];

                    $val_otp = curl("https://shop-api.edot.id/api/otp/validate", $data, $headers);

                    $mes_otp = get_between($val_otp[1], '"message":"', '"');

                    echo "[-] ".$mes_otp.PHP_EOL;

                    ulang_wd:

                    if (strpos($val_otp[1], '"code":200,')) {

                        echo "[-] Mencoba Withdraw, Mohon Tunggu...".PHP_EOL;

                        $data = '{"account_id":'.$acc_id.',"amount":'.$jumlah_wd.',"otp_code":"'.$otp.'","otp_token":"'.$otp_token.'","type_rekening":"personal","id_store_sso":""}';

                        $headers = [

                            "Host: shop-api.edot.id",

                            "Accept: application/json",

                            "Content-Type: application/json",

                            "User-Agent: ehashtag/1 CFNetwork/1399 Darwin/22.1.0",

                            "Connection: keep-alive",

                            "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",

                            "Authorization: Bearer ".$access_token,

                            "Content-Length: ".strlen($data),

                            "Accept-Encoding: gzip, deflate, br"

                        ];

                        $req_wd = curl("https://shop-api.edot.id/api/disburse/withdraw", $data, $headers);

                        $mes_wd = get_between($req_wd[1], '"message":"', '"');

                        if (strpos($req_wd[1], '"code":200,')) {

                            echo "[-] Berhasil Withdraw".PHP_EOL;

                            echo "[-] Saldo Terakhir: ".$balance.PHP_EOL;

                        } else {

                            echo "[!] ".$mes_wd.PHP_EOL;

                            echo "[-] Saldo Terakhir: ".$balance.PHP_EOL;

                            goto ulang_wd;

                        }

                    }

                } else {

                    echo "[!] Gagal Validasi OTP".PHP_EOL;

                    goto dariawalaja;

                }

            } else {

                echo "[!] Gagal Check Saldo".PHP_EOL;

                goto dariawalaja;

            }

        

        } else {

            echo "[!] Gagal Mendapatkan Auth Token".PHP_EOL;

            goto dariawalaja;

        }

    } else {

        die("[!] Gagal Login").PHP_EOL;

    }

} else {

    die("[!] Gagal Mendapatkan Token Kode").PHP_EOL;

}

function input($text) {

    echo $text.": ";

    $a = trim(fgets(STDIN));

    return $a;

}

function get_between($string, $start, $end) 

    {

        $string = " ".$string;

        $ini = strpos($string,$start);

        if ($ini == 0) return "";

        $ini += strlen($start);

        $len = strpos($string,$end,$ini) - $ini;

        return substr($string,$ini,$len);

    }

function generateRandomString($length = 10) {

    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';

    $charactersLength = strlen($characters);

    $randomString = '';

    for ($i = 0; $i < $length; $i++) {

        $randomString .= $characters[rand(0, $charactersLength - 1)];

    }

    return $randomString;

}

function nama() {

	$ch = curl_init();	curl_setopt($ch, CURLOPT_URL, "https://api.namefake.com/indonesian-indonesia");

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

	$ex = curl_exec($ch);

	return $ex;

}

function curl($url, $post = 0, $httpheader = 0, $proxy = 0){ // url, postdata, http headers, proxy, uagent

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        if($post){

            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        }

        if($httpheader){

            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);

        }

        if($proxy){

            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);

            curl_setopt($ch, CURLOPT_PROXY, $proxy);

            // curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);

        }

        curl_setopt($ch, CURLOPT_HEADER, true);

        $response = curl_exec($ch);

        $httpcode = curl_getinfo($ch);

        if(!$httpcode) return "Curl Error : ".curl_error($ch); else{

            $header = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));

            $body = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));

            curl_close($ch);

            return array($header, $body);

        }

    }
