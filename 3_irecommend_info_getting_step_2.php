<?php

$contentDir = 'reviews/irecommend';
$files = scandir($contentDir);

$titlePattern = '/<h2 class=\"reviewTitle\" [^>]+>\s*<a[^>]+>([\s\S]+?)<\/a>/';
$contentPattern = '/<div class="description hasinlineimage" itemprop="reviewBody">([\s\S]+?)<\/div>/';
$ratingPattern = '/<meta itemprop=\"ratingValue\" content=\"([0-9]+)" \/>/';
$datePattern = '/<meta itemprop=\"datePublished\" content=\"(.+)\" \/>/';

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

file_put_contents('results/tele2_irecommend_reviews.json', json_encode($result, JSON_UNESCAPED_UNICODE));