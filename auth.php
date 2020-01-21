<?php

$url = 'https://otzovik.com/reviews/sotoviy_operator_tele2/4/';

$customHeaders = [
    'Accept: application/json, text/javascript, */*; q=0.01',
    'Accept-language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
    'Content-type: application/x-www-form-urlencoded; charset=UTF-8',
    'Origin: https://otzovik.com',
    'X-requested-with: XMLHttpRequest',
];

$initHeaders = [
    CURLOPT_ACCEPT_ENCODING => 'accept-encoding: gzip, deflate, br',
    CURLOPT_COOKIE          => 'cookie: refreg=https%3A%2F%2Fwww.google.com%2F; ROBINBOBIN=qdd0i42085fjnh229qu7u05p00; ssid=299821778',
    CURLOPT_REFERER         => 'referer: https://otzovik.com/loginnew.php',
    CURLOPT_USERAGENT       => 'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36',
    CURLOPT_HTTPHEADER      => $customHeaders,
];

//$params   = [
//    'mypostаn' => '1',
//    'ulоgin'   => 'ffczepArtff',
//    'pass'     => 'avgust06',
//];
//$defaults = [
//    CURLOPT_POST       => true,
//    CURLOPT_POSTFIELDS => $params,
//];

$ch = curl_init();

curl_setopt_array($ch, $initHeaders);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_PROXY, '188.163.170.130');
curl_setopt($ch, CURLOPT_PROXYPORT, '41209');

$result = curl_exec($ch);
if (curl_error($ch)) {
    var_dump(curl_error($ch));
}
var_dump($result);

curl_close($ch);
