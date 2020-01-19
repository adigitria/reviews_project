<?php

$config = require 'config.php';

$basePage = 'https://irecommend.ru';
$baseSearchPage = 'https://irecommend.ru/content/tele2?page=';
$countPages = 15;

$opts = [
    'http' => [
        'method' => "GET",
        'header' => $config['headers']['irecommend']
    ]
];

$context = stream_context_create($opts);
$linksPattern = '/<a href=\"([^"]+)\" class=\"more\"><\/a>/';

for ($i = 1; $i <= $countPages; $i++) {
    $pageContent = file_get_contents($baseSearchPage . $i, false, $context);
    preg_match_all($linksPattern, $pageContent, $matches);
    foreach ($matches[1] as $index => $reviewPage) {
        $content = file_get_contents($basePage . $reviewPage, false, $context);
        file_put_contents('pages/irecommend/review_' . $i . '_' . $index . '.html', $content);
    }
    echo 'Download page - ' . $i . PHP_EOL;
}