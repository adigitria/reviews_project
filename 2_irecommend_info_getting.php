<?php

$contentDir = 'pages/irecommend';
$reviewPattern = '/<!-- review block start -->([\s\S]+)<!-- review block end -->/';
$files = scandir($contentDir);

foreach ($files as $i => $file) {
    $filePath = $contentDir . '/' . $file;
    if (file_exists($filePath) && !in_array($file, ['.', '..'])) {
        echo $filePath . PHP_EOL;
        $content = file_get_contents($filePath);
        preg_match($reviewPattern, $content, $matches);
        if(isset($matches[1])){
            file_put_contents('reviews/irecommend/review_' . ($i - 1) . '.html', $matches[1]);
        }
    }
}