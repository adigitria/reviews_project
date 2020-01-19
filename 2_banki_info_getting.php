<?php

$contentDir = 'pages/banki';
$reviewPattern = '/<article class=\"responses__item\"([\S|\s]+?)<\/article>/';
$files = scandir($contentDir);

foreach ($files as $i => $file) {
    $filePath = $contentDir . '/' . $file;
    if (file_exists($filePath) && !in_array($file, ['.', '..'])) {
        echo $filePath . PHP_EOL;
        $content = file_get_contents($filePath);
        preg_match_all($reviewPattern, $content, $matches);
        foreach ($matches[0] as $index => $match) {
            file_put_contents('reviews/banki/review_' . ($i - 1) . '_' . $index . '.html', $match);
        }
    }
}