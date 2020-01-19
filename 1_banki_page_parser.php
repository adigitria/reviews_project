<?php

$basePage = 'https://www.banki.ru/telecom/responses/company/tele2/?page=';
$countPages = 67;

for ($i = 1; $i <= $countPages; $i++) {
    $pageContent = file_get_contents($basePage . $i);
    file_put_contents('pages/banki/page_' . $i . '.html', $pageContent);
    echo 'Download page - ' . $i . PHP_EOL;
}