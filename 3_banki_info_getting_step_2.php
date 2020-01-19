<?php

$contentDir = 'reviews/banki';
$files = scandir($contentDir);

$titlePattern = '/<a class=\"header-h3\"[^<]+>(.+)<\/a>/';
$contentPattern = '/<div class=\"responses__item__message markup-inside-small markup-inside-small--bullet\"[^>]+>([\S|\s]+?)<\/div>/';
$ratingPattern = '/itemprop=\"ratingValue\"[^>]+>([\S|\s]+?)<\/span>/';
$datePattern = '/datetime=\"([^"]+)\"/';

$patterns = [
    'title' => $titlePattern,
    'content' => $contentPattern,
    'rating' => $ratingPattern,
    'date' => $datePattern,
];

$result = [];
foreach ($files as $i => $file) {
    $filePath = $contentDir . '/' . $file;
    if (file_exists($filePath) && !in_array($file, ['.', '..'])) {
        echo $filePath . PHP_EOL;
        $content = file_get_contents($filePath);

        foreach ($patterns as $key => $pattern) {
            unset($matches);
            preg_match($pattern, $content, $matches);
            if (isset($matches[1])) {
                $result[$i][$key] = trim($matches[1]);
            }
        }
    }
}

file_put_contents('results/tele2_banki_reviews.json', json_encode($result, JSON_UNESCAPED_UNICODE));