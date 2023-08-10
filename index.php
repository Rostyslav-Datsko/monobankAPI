<?php

$url = "https://api.monobank.ua";
$token = "uCHemtgK-giK8yoMt05wqh_pD2yjajvxkH48TVyONOrE";
$headers = array();

function getClientInfo ($token, $url) :array
{
    $addedUrl = "/personal/client-info";
    $url .= $addedUrl;
    $headers[] = "X-Token: $token";
    $state_ch = curl_init();
    curl_setopt($state_ch, CURLOPT_URL,$url);
    curl_setopt($state_ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($state_ch, CURLOPT_HTTPHEADER, $headers);
    $state_result = curl_exec ($state_ch);
    $err = curl_error($state_ch);


    if ($err) {
        echo "cURL Error #:" . $err;
        exit;
    }
    $state_result = json_decode($state_result, true);
    return $state_result;
}

function setCorrectUnixtime($from, $to) {
    if($from < $to){
        $tmp = $from;
        $from = $to;
        $to = $tmp;
    }
    if ($from > 31) {
        $from = strtotime("-31 days - 1 hour");
    } else {
        $from = strtotime("-{$from} days");
    }

    if ($to < 0) {
        $to  = time();
    } else {
        $to = strtotime("-{$to} days");
    }

    return array(
        "from" => $from,
        "to" => $to,
    );
}

function getAccountTransactionsInfo ($accountId, $token, $url, int $from, int $to)
{
    $limiterArray = array();
    $limiterArray = setCorrectUnixtime($from, $to);

    $addedUrl = "/personal/statement/{$accountId}/{$limiterArray["from"]}/{$limiterArray["to"]}";
    $url .= $addedUrl;
    $headers[] = "X-Token: $token";
    $state_ch = curl_init();
    curl_setopt($state_ch, CURLOPT_URL,$url);
    curl_setopt($state_ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($state_ch, CURLOPT_HTTPHEADER, $headers);
    $state_result = curl_exec ($state_ch);
    $err = curl_error($state_ch);


    if ($err) {
        echo "cURL Error #:" . $err;
        exit;
    }
    $state_result = json_decode($state_result, true);
    return $state_result;
}

function getAccountsTransactionsInfo ($userAccounts, $token, $url, int $from, int $to)
{
    $result = array();
    $elementsNumber = count($userAccounts);
    for ($i = 0; $i <= ($elementsNumber-1); $i++) {
        $tmpArray = array();
        $tmpArray = getAccountTransactionsInfo($i, $token, $url, $from, $to);
        array_push($result, $tmpArray);
    }
//    for ($userAccounts as $k)
//    {
//
//    }
    var_dump($result);
    return $result;
}

$user = getClientInfo($token, $url);
getAccountsTransactionsInfo ($user["accounts"], $token, $url, 31, 0);
//getAccountTransactionsInfo(1, $token, $url, 31, 0);
//getClientInfo($token, $url);

/////////////////////
//$url = "https://api.monobank.ua/personal/client-info";
//$token = "uCHemtgK-giK8yoMt05wqh_pD2yjajvxkH48TVyONOrE";
//
//$headers = array();
//$headers[] = "X-Token: $token";
//$state_ch = curl_init();
//curl_setopt($state_ch, CURLOPT_URL,$url);
//curl_setopt($state_ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($state_ch, CURLOPT_HTTPHEADER, $headers);
//$state_result = curl_exec ($state_ch);
//$err = curl_error($state_ch);
//
//
//if ($err) {
//    echo "cURL Error #:" . $err;
//    exit;
//}
//$state_result = json_decode($state_result);
//
//var_dump($state_result);
//
//// 2 метод
//$url = "https://api.monobank.ua/personal/webhook";
//$token = "uCHemtgK-giK8yoMt05wqh_pD2yjajvxkH48TVyONOrE";
//
//$headers = array();
//$headers[] = "X-Token: $token";
//$state_ch = curl_init();
//curl_setopt($state_ch, CURLOPT_URL,$url);
//curl_setopt($state_ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($state_ch, CURLOPT_HTTPHEADER, $headers);
//$state_result = curl_exec ($state_ch);
//$err = curl_error($state_ch);
//
//
//if ($err) {
//    echo "cURL Error #:" . $err;
//    exit;
//}
//$state_result = json_decode($state_result);
//
//var_dump($state_result);
//
//// 3 метод
//
//$account = 0;
//$to = time();
//$from = strtotime("-31 days - 1 hour");
//
//$url = "https://api.monobank.ua/personal/statement/{$account}/{$from}/{$to}";
//$token = "uCHemtgK-giK8yoMt05wqh_pD2yjajvxkH48TVyONOrE";
//
//$headers = array();
//$headers[] = "X-Token: $token";
//$state_ch = curl_init();
//curl_setopt($state_ch, CURLOPT_URL,$url);
//curl_setopt($state_ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($state_ch, CURLOPT_HTTPHEADER, $headers);
//$state_result = curl_exec ($state_ch);
//$err = curl_error($state_ch);
//
//
//if ($err) {
//    echo "cURL Error #:" . $err;
//    exit;
//}
//$state_result = json_decode($state_result);
//
//var_dump($state_result);