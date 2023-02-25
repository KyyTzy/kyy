<?php

error_reporting(0);
require 'function.php';
system('clear');
echo $orange.$banner.$cln;
echo "\n\n";
echo $bold.$fgreen."[-] Bot eDot v2 by CatzBurry \n\n".$cln;
    echo $bold . $lblue . "Commands\n";
    echo "========\n\n";
    echo $bold . $fgreen . "[1]$cln Auto Register With AdaOTP$cln\n";
    echo $bold . $fgreen . "[2]$cln Manual Register With OTP$cln\n";
    echo $bold . $fgreen . "[3]$cln Withdraw$cln\n";
echo "\n";

web:
$pilihweb = "[-] Pilih Opsi ";
$webOTP = input("$bold$orange$pilihweb$cln");
echo "\n";
if($webOTP == 1) {
echo "------------ \e[1;36mAuto Regist With AdaOTP\e[0m -----------".PHP_EOL;
echo "\n";
if(!file_exists("alerts.txt")) {
    inputApikeyOTP:
    $upt = "[-] Apakah Sudah edit config.json? (y/N)";
    $updated = input("$bold$orange$upt$cln");
    if(strtolower($updated) == "y") {
        file_put_contents("alerts.txt", "off");
    } else if(strtolower($updated) == "n") {
        inputLicense:
	$ky = "[-] Apikey Kamu ";
        $key = input("$bold$orange$ky$cln");
        file_put_contents("config.json", json_encode(['apikey' => $key]));
    } else {
        echo $bold.$yellow."[-] Pilihan Tidak Tersedia".$cln.PHP_EOL;
        goto inputApikeyOTP;
    }
}

$readConfig  = json_decode(file_get_contents("config.json"), true);
$apikey = trim($readConfig['apikey']);

if ($apikey) {
    echo $bold.$fgreen."[-] Apikey Ditemukan ".$cln.PHP_EOL.PHP_EOL;
} else {
    echo  $bold.$red."[-] Apikey Tidak Ditemukan".$cln.PHP_EOL;
    echo  $bold.$orange."[-] Silakan Input Data Apikey ".$cln.PHP_EOL;
    goto inputLicense;
}

/** GET BALANCE **/
$getBalance = curl("https://adaotp.com/api/get-profile/".$apikey, 0, 0);
$balance = get_between($getBalance[1], '"saldo":"', '",');
$usernameOtp = get_between($getBalance[1], '"username":"', '",');
$messageOTP = get_between($getBalance[1], '{"messages":"', '"');
echo $bold.$orange."[-] Username ".$cln.": ".$usernameOtp.PHP_EOL;
echo $bold.$orange."[-] Saldo ".$cln.": Rp " . number_format($balance, 0, ",", ".").PHP_EOL.PHP_EOL;
if(strpos($getBalance[1], '"success":true,"')) {
    if ($balance < 1000) {
        echo $bold.$red."[-] Isi Saldo Terlebih Dahulu".$cln.PHP_EOL;
	die;
    }
} else {
    echo $bold.$red."[-] Message ".$cln.": ".$messageOTP.PHP_EOL;
}

/** END GET BALANCE **/

$loopp = "[-] Jumlah Reff ";
$kodereff = "[-] Kode Refferal ";
$refferal = input("$bold$orange$kodereff$cln");
$loop = input("$bold$orange$loopp$cln");

echo PHP_EOL;
for($ia=1; $ia <= $loop; $ia++){
    echo "--------------- \e[1;36m[ $ia/$loop ]\e[0m --------------- ".PHP_EOL;
	echo PHP_EOL;
    ulang:
    $deviceId = generateRandomString(36);
    $data = '{"name":"web-sso","secret_key":"3e53440178c568c4f32c170f","device_type":"web","device_id":"'.$deviceId.'"}';
    $lenght = strlen($data);
    $headers = [
        "Host: api-accounts.edot.id",
        "Content-Type: application/json",
        "Origin: https://accounts.edot.id",
        "Connection: keep-alive",
        "Accept: */*",
        "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
        "Content-Length: ".$lenght,
        "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
        "Accept-Encoding: gzip, deflate, br"
    ];

    $getToken = curl("https://api-accounts.edot.id/api/token/get", $data, $headers);
    $code = get_between($getToken[1], '"code":', ',"');
    $token_code = get_between($getToken[1], '"token_code":"', '",');
    if ($code == 200) {
        echo $bold.$fgreen."[-] Status Code 200".$cln.PHP_EOL;
        echo $bold.$orange."[-] Token_code ".$cln.": ".$token_code.PHP_EOL;
        $fullName = getName();
        $data = '{"fullname":"'.$fullName.'"}';
        $lenght = strlen($data);
        $headers = [
            "Host: api-accounts.edot.id",
            "Content-Type: application/json",
            "Origin: https://accounts.edot.id",
            "Accept-Encoding: gzip, deflate, br",
            "Connection: keep-alive",
            "Accept: */*",
            "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
            "Content-Length: ".$lenght,
            "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
            "sso-token: ".$token_code,
        ];
        $getUsername = curl("https://api-accounts.edot.id/api/user/get_suggestion_username", $data, $headers);
        $codeUsername = get_between($getUsername[1], '"code":', ',"');
        $username = get_between($getUsername[1], '"data":["', '",');
        if ($codeUsername == 200) {
	    echo PHP_EOL;
            echo $bold.$fgreen."[-] Status Code 200".$cln.PHP_EOL;
            echo $bold.$orange."[-] Username ".$cln.": ".$username.PHP_EOL;
            getnomor:
            $createOrder = curl("https://adaotp.com/api/set-orders/".$apikey."/440");
            $orderID = get_between($createOrder[1], '"order_id":"', '",');
            $messageOrder = get_between($createOrder[1], '"messages":"', '"');
            $nomor = get_between($createOrder[1], '"number":"', '","');
            if(strpos($createOrder[1], '"success":true,"')) {
                echo $bold.$fgreen."[-] Message ".$cln.$messageOrder.PHP_EOL.PHP_EOL;
		echo $bols.$orange."[-] Nomor ".$cln.": ".$nomor.PHP_EOL;
            } else {
                echo $bold.$red."[-] Message ".$cln.": ".$messageOrder.PHP_EOL;
		die;
            }
            $data = '{"phone_number":"'.$nomor.'","type":"verify_phone","send_type":"sms"}';
            $lenght = strlen($data);
            $headers = [
                "Host: api-accounts.edot.id",
                "Content-Type: application/json",
                "Origin: https://accounts.edot.id",
                "Accept-Encoding: gzip, deflate, br",
                "Connection: keep-alive",
                "Accept: */*",
                "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
                "Content-Length: ".$lenght,
                "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
                "sso-token: ".$token_code,
            ];
            $sendOTP = curl("https://api-accounts.edot.id/api/user/send_otp_phone", $data, $headers);
            $codesendOTP = get_between($sendOTP[1], '"code":', ',"');
            $msgotp = get_between($sendOTP[1], '"data":"', '"}');
            if ($codesendOTP == 200) {
                echo $bold.$fgreen."[-] Status Code 200".$cln.PHP_EOL;
                echo $bold.fgreen."[-] ".$msgotp.$cln.PHP_EOL;
                $time = time();
                CheckUlangOTP:
                $getOTP = curl("https://adaotp.com/api/get-orders/".$apikey."/".$orderID, 0, 0);
                $orderID = get_between($getOTP[1], '"order_id":"', '",');
                $messageOTP = get_between($getOTP[1], '"messages":"', '"');
                $successdataGet = get_between($getOTP[1], '"success":', ',"');
                $otp = get_between($getOTP[1], '"<#> ', 'adalah');
                $otpbos = trim($otp);
                if($otp) {
                    echo $bold.$fgreeb."[-] Message ".$cln.": ".$messageOTP.PHP_EOL;
		    echo $bold.$orange."[-] OTP ">$cln.": ".$otpbos.PHP_EOL;
                } else {
                    if (time()-$time > 30) {
                        echo $bold.$red."[-] Gagal Mendapatkan OTP".$cln.PHP_EOL;
                        $cancle = curl("https://adaotp.com/api/cancle-orders/".$apikey."/".$orderID, 0, 0);
                        goto getnomor;
                    } else {
                        goto CheckUlangOTP;
                    }
                }
                $data = '{"phone_number":"'.$nomor.'","otp":"'.$otpbos.'","description":"register"}';
                $lenght = strlen($data);
                $headers = [
                    "Host: api-accounts.edot.id",
                    "Content-Type: application/json",
                    "Origin: https://accounts.edot.id",
                    "Accept-Encoding: gzip, deflate, br",
                    "Connection: keep-alive",
                    "Accept: */*",
                    "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
                    "Content-Length: ".$lenght,
                    "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
                    "sso-token: ".$token_code,
                ];
                $verifotp = curl("https://api-accounts.edot.id/api/user/verify_otp_phone", $data, $headers);
                $codeverifOTP = get_between($verifotp[1], '"code":', ',"');
                $msgverif = get_between($verifotp[1], '"data":"', '"}');
                if ($codeverifOTP == 200) {
                    echo $bold.$fgreen."[-] Status Code 200".PHP_EOL;
                    echo "[-] ".$msgverif.$cln.PHP_EOL;
                    $data = '{"fullname":"'.$fullName.'","email":"","username":"'.$username.'","recovery_email":"","phone_number":"'.$nomor.'","password":"Jumadygantengnihbos11#$","date_of_birth":"2000-01-05","gender":"pria","security_question_id":"1","security_question_answer":"Sukinah","response_type":"code","client_id":"0c21679b392bc480c87c150303ab255d","referral_code":"'.strtoupper($refferal).'"}';
                    $lenght = strlen($data);
                    $headers = [
                        "Host: api-accounts.edot.id",
                        "Content-Type: application/json",
                        "Origin: https://accounts.edot.id",
                        "Accept-Encoding: gzip, deflate, br",
                        "Connection: keep-alive",
                        "Accept: */*",
                        "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
                        "Content-Length: ".$lenght,
                        "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
                        "sso-token: ".$token_code,
                    ];
                    $register = curl("https://api-accounts.edot.id/api/user/register", $data, $headers);
                    $registerfinal = get_between($register[1], '{"redirect_url":"', '"}');
                    if ($registerfinal) {
                        echo $bold.$fgreen."[-] Sukses Mendaftarkan $username Kode Reff: ".strtoupper($refferal).$cln.PHP_EOL;
                        echo $bold.$orange."[-] Redirect Url ".$registerfinal.$cln.PHP_EOL;
                        $finish = curl("https://adaotp.com/api/finish-orders/".$apikey."/".$orderID, 0, 0);
                        $getToken_login = curl($registerfinal, 0, 0);
                        sleep(10);
                    } else if (strpos($register[1], '"code":400,"')) {
                        $msg_registerfinal = get_between($register[1], '{"message":"', '","');
                        echo $bold.$red."[-] Gagal Mendaftarkan User".$cln.PHP_EOL;
			echo $bold.$oramge. "[-] Reason ".$cln.": ".$msg_registerfinal.PHP_EOL;
                        goto ulang;
                    } else {
                        echo $bold.$red."[-] Gagal Mendaftarkan User".$cln.PHP_EOL;
			echo $bold.$oramge. "[-] Reason ".$cln.": ".$msg_registerfinal.PHP_EOL;
                        goto ulang;
                    }
                } else {
                    echo $bold.$red."[-] Gagal Verifikasi OTP".$cln.PHP_EOL;
                    $finish = curl("https://adaotp.com/api/finish-orders/".$apikey."/".$orderID, 0, 0);
                    goto ulang;
                }
            } else {
                echo $bold.$red."[-] Gagal Mengirimkan OTP".$cln.PHP_EOL;
                $cancle = curl("https://adaotp.com/api/cancle-orders/".$apikey."/".$orderID, 0, 0);
                goto ulang;
            }
        } else {
            echo $bold.$orange."[-] Gagal Mendaptkan Username".$cln.PHP_EOL;
            goto ulang;
        }
    } else {
        echo $bold.$red."[-] Gagal Mendaftarkan Token".$cln.PHP_EOL;
        goto ulang;
    }
}
} elseif($webOTP == 2) {
echo "------------ \e[1;36mManual Regist With OTP\e[0m -----------".PHP_EOL.PHP_EOL;
$loopp = "[-] Jumlah Reff ";
$kodereff = $bold.$orange."[-] Kode Refferal ".$cln."( CATZBURR1 ) ";
$refferal = input("$kodereff");
$loop = input("$bold$orange$loopp$cln");
echo PHP_EOL;
for($ia=1; $ia <= $loop; $ia++){
    echo "--------------- \e[1;36m[ $ia/$loop ]\e[0m --------------- ".PHP_EOL;
    ulang1:
    $deviceId = generateRandomString(36);
    $data = '{"name":"web-sso","secret_key":"3e53440178c568c4f32c170f","device_type":"web","device_id":"'.$deviceId.'"}';
    $lenght = strlen($data);
    $headers = [
        "Host: api-accounts.edot.id",
        "Content-Type: application/json",
        "Origin: https://accounts.edot.id",
        "Connection: keep-alive",
        "Accept: */*",
        "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
        "Content-Length: ".$lenght,
        "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
        "Accept-Encoding: gzip, deflate, br"
    ];
    $getToken = curl("https://api-accounts.edot.id/api/token/get", $data, $headers);
    $code = get_between($getToken[1], '"code":', ',"');
    $token_code = get_between($getToken[1], '"token_code":"', '",');
    if ($code == 200) {
	echo PHP_EOL;
        echo $bold.$fgreen."[-] Status Code 200".$cln.PHP_EOL;
        echo $bold.$orange."[-] Token_code ".$cln.": ".$token_code.PHP_EOL;
        $fullName = getName();
        $data = '{"fullname":"'.$fullName.'"}';
        $lenght = strlen($data);
        $headers = [
            "Host: api-accounts.edot.id",
            "Content-Type: application/json",
            "Origin: https://accounts.edot.id",
            "Accept-Encoding: gzip, deflate, br",
            "Connection: keep-alive",
            "Accept: */*",
            "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
            "Content-Length: ".$lenght,
            "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
            "sso-token: ".$token_code,
        ];
        $getUsername = curl("https://api-accounts.edot.id/api/user/get_suggestion_username", $data, $headers);
        $codeUsername = get_between($getUsername[1], '"code":', ',"');
        $username = get_between($getUsername[1], '"data":["', '",');
        if ($codeUsername == 200) {
            echo $bold.$orange."[-] Username ".$cln.": ".$username.PHP_EOL;
            getnomor2:
	    $nmr = "[-] Nomor ";
            $nomor = input("$bold$orange$nmr$cln");
	    echo PHP_EOL;
            $data = '{"phone_number":"'.$nomor.'","type":"verify_phone","send_type":"sms"}';
            $lenght = strlen($data);
            $headers = [
                "Host: api-accounts.edot.id",
                "Content-Type: application/json",
                "Origin: https://accounts.edot.id",
                "Accept-Encoding: gzip, deflate, br",
                "Connection: keep-alive",
                "Accept: */*",
                "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
                "Content-Length: ".$lenght,
                "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
                "sso-token: ".$token_code,
            ];
            $sendOTP = curl("https://api-accounts.edot.id/api/user/send_otp_phone", $data, $headers);
            $codesendOTP = get_between($sendOTP[1], '"code":', ',"');
            $msgotp = get_between($sendOTP[1], '"message":"','",');
            $msgotpsc = get_between($sendOTP[1], '"message":"','"}');
            if ($codesendOTP == 200) {
                echo $bold.$fgreen."[-] ".$msgotpsc.$cln.PHP_EOL.PHP_EOL;
                $time = time();
                CheckUlangOTP1:
		$opt = "[-] OTP ";
                $otpbos = input("$bold$orange$opt$cln");
                $data = '{"phone_number":"'.$nomor.'","otp":"'.$otpbos.'","description":"register"}';
                $lenght = strlen($data);
                $headers = [
                    "Host: api-accounts.edot.id",
                    "Content-Type: application/json",
                    "Origin: https://accounts.edot.id",
                    "Accept-Encoding: gzip, deflate, br",
                    "Connection: keep-alive",
                    "Accept: */*",
                    "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
                    "Content-Length: ".$lenght,
                    "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
                    "sso-token: ".$token_code,
                ];
                $verifotp = curl("https://api-accounts.edot.id/api/user/verify_otp_phone", $data, $headers);
                $codeverifOTP = get_between($verifotp[1], '"code":', ',"');
   	        $msgverifsc = get_between($verifotp[1], '"data":"', '"}');
                $msgverif = get_between($verifotp[1], '"message":"','",');
                if ($codeverifOTP == 200) {
                    echo $bold.$fgreen."[-] ".$msgverifsc.$cln.PHP_EOL;
                    $data = '{"fullname":"'.$fullName.'","email":"","username":"'.$username.'","recovery_email":"","phone_number":"'.$nomor.'","password":"Jumadygantengnihbos11#$","date_of_birth":"2000-01-05","gender":"pria","security_question_id":"1","security_question_answer":"Sukinah","response_type":"code","client_id":"0c21679b392bc480c87c150303ab255d","referral_code":"'.strtoupper($refferal).'"}';
                    $lenght = strlen($data);
                    $headers = [
                        "Host: api-accounts.edot.id",
                        "Content-Type: application/json",
                        "Origin: https://accounts.edot.id",
                        "Accept-Encoding: gzip, deflate, br",
                        "Connection: keep-alive",
                        "Accept: */*",
                        "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
                        "Content-Length: ".$lenght,
                        "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
                        "sso-token: ".$token_code,
                    ];
                    $register = curl("https://api-accounts.edot.id/api/user/register", $data, $headers);
                    $registerfinal = get_between($register[1], '{"redirect_url":"', '"}');
                    if ($registerfinal) {
                        echo $bold.$fgreen."[-] Sukses Mendaftarkan ".$cln.$username.PHP_EOL;
			echo $bold.$orange."[-] Kode Reff ".$cln.": ".strtoupper($refferal).PHP_EOL;
                        echo $bold.$orange."[-] Redirect Url ".$cln.": ".$registerfinal.PHP_EOL;
			echo PHP_EOL;
                        $getToken_login = curl($registerfinal, 0, 0);
                    } else {
                        echo $bold.$red."[-] Gagal Mendaftarkan User".$cln.PHP_EOL;
                        goto ulang1;
                    }
                } else {
                    echo $bold.$red."[-] Gagal Verifikasi OTP".$cln.PHP_EOL;
			echo $bold.$orange."[-] Reason ".$cln.": ".$msgverif.PHP_EOL;
                    goto ulang1;
                }
            } else {
                echo $bold.$red."[-] Gagal Mengirimkan OTP".$cln.PHP_EOL;
                echo $bold.$orange."[-] Reason ".$cln.": ".$msgotp.PHP_EOL;
                sleep(3);
                goto ulang1;
            }
        } else {
            echo $bold.$red."[-] Gagal Mendaptkan Username".$cln.PHP_EOL;
            goto ulang1;
        }
    } else {
        echo $bold.$red."[-] Gagal Mendaftarkan Token".$cln.PHP_EOL;
        goto ulang1;
    }
}
}elseif($webOTP == 3) {
echo "------------ \e[1;36mWithdraw\e[0m -----------".PHP_EOL;
echo "\n";
$usern = "[-] Username ";
$passw = "[-] Password ";
$username = input("$bold$orange$usern$cln");
$password = input("$bold$orange$passw$cln");
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
    echo $bold.$orange."[-] Token_code ".$cln.": ".$token_code.PHP_EOL;
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
        echo $bold.$fgreen."[-] Login Sukses".$cln.PHP_EOL;
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
        echo $bold.$orange."[-] Refresh Token ".$cln.": ".$access_token.PHP_EOL;
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
            echo $bold.$orange."[-] No. Rek ".$cln.": ".$bank_no.PHP_EOL;
            echo $bold.$orange."[-] Nama ".$cln.": ".$account_name.PHP_EOL;
	    echo $bold.$orange."[-] Bank ".$cln.": ".$bank_name.PHP_EOL;
            $balance = get_between($check_saldo[1], '"desc_balance":"', '","');
            if (strpos($check_saldo[1], '"code":200,')) {
                echo $bold.$orange."[-] Saldo ".$cln.": ".$balance.PHP_EOL;
		$nowd = $bold.$orange."[-] Nomor ".$cln."(628xxx)";
		$jml = $bold.$orange."[-] Jumlah WD ".$cln."(Fee Rp 4.620)";
                $jumlah_wd = input("$jml");
                $nomor_hp = input("$nowd");
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
		    echo $bold.$fgreen."[-] OTP Berhasil Dikirim".$cln.PHP_EOL;
                    echo $bold.$orange."[-] Otp_token ".$cln.": ".$otp_token.PHP_EOL;
		    ulangotp:
		    $inpotp = "[-] Otp ";
                    $otp = input("$bold$orange$inpotp$cln");
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
                    if (strpos($val_otp[1], '"code":400,')) {
                    echo $bold.$red."[-] ".$mes_otp.$cln.PHP_EOL;
	            goto ulangotp;
		    }
                    if (strpos($val_otp[1], '"code":200,')) {
                    echo $bold.$fgreen."[-] ".$mes_otp.$cln.PHP_EOL;
                    ulang_wd:
                        echo $bold.$orange."[-] Mencoba Withdraw, Mohon Tunggu...".$cln.PHP_EOL.PHP_EOL;
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
                        if (strpos($req_wd[1], '"status":422,')) {
                        echo $bold.$red."[-] ".$cln.$mes_wd.PHP_EOL.PHP_EOL;
			die;
                        }
			if ($mes_wd == 'Kode OTP Salah, Mohon cek kembali kode OTP anda') {
			die;
			}
			if ($mes_wd == 'created') {
                        echo $bold.$fgreen."[-] Berhasil Withdraw".$cln.PHP_EOL;
			die;
			} else {
                        echo $bold.$red."[-] Too many requests, please try again later.".$cln.PHP_EOL.PHP_EOL;
          		goto ulang_wd;
			}
                    }
                } else {
                    echo $bold.$orange."[-] Gagal Validasi OTP".$cln.PHP_EOL;
                    goto dariawalaja;
                }
            } else {
                echo $bold.$red."[-] Gagal Check Saldo".$cln.PHP_EOL;
                goto dariawalaja;
            }
            } else {
	    echo $bold.$red."[-] Gagal Mendapatkan Auth Token".$cln.PHP_EOL;
            goto dariawalaja;
        }
    } else {
    echo $bold.$red."[-] Gagal Login".$cln.PHP_EOL;
    die;
    }
} else {
    echo $bold.$red."[-] Gagal Mendapatkan Token Kode".$cln.PHP_EOL;
    die;
}
}
