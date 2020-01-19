<?php

$config = require 'config.php';

$basePage = 'https://otzovik.com';
$baseSearchPage = 'https://otzovik.com/reviews/sotoviy_operator_tele2/%s/';
$countPages = 140;

$opts = [
    'http' => [
        'method' => 'GET',
        'authority' => 'otzovik.com',
        'scheme' => 'https',
        'header' => $config['headers']['otzovik']
    ]
];
$context = stream_context_create($opts);

$linksPattern = '/<a href=\"([^"]+)\" class=\"more\"><\/a>/';

for ($i = 1; $i <= $countPages; $i++) {

    $pageContent = file_get_contents(sprintf($baseSearchPage, (string)$i), false, $context);
    file_put_contents('pages/otzovik/page_' . $i . '.html', $pageContent);
    echo 'Download page - ' . $i . PHP_EOL;
}