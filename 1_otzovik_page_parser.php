<?php
$config = require 'config.php';

$url = 'https://otzovik.com/reviews/sotoviy_operator_tele2/%PAGE_NUMBER%/';
$agent = $config['headers']['otzovik']['cookie'];

$ch = curl_init();


$count = 0;
for ($i = 1; $i <= 143; $i++) {
    $searchUrl = str_replace('%PAGE_NUMBER%', $i, $url);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_COOKIE, '');
    curl_setopt($ch, CURLOPT_URL, $searchUrl);

    $result = curl_exec($ch);
    file_put_contents('pages/otzovik/page_' . $i . '.html', $result);
    $count++;
    if ($count % 20 == 0) {
        sleep(15);
    }
}

curl_close($ch);