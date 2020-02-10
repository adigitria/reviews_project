<?php

include 'db_worker.php';

$testeeMatcher = [
    'banki'      => [
        'test1' => 'mts',
        'test2' => 'tinkoff',
        'test3' => 'sber',
        'test4' => 'qiwi',
    ],
    'irecommend' => [
        'test2' => 'tinkoff',
        'test3' => 'sber',
        'test4' => 'qiwi',
    ],
    'otzovik'    => [
        'test1' => 'mts',
        'test2' => 'tinkoff',
        'test3' => 'sber',
    ],
];

function tree($path, $exclude = ['db_worker.php', 'loader.php', '.', '..'])
{
    $files = [];
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $splfileinfo) {
        if (!in_array(basename($splfileinfo), $exclude)) {
            $files[] = str_replace($path, '', '' . $splfileinfo);
        }
    }

    return $files;
}

$files  = tree('../from_amazon');
$host   = '192.168.99.129';
$port   = '1432';
$user   = 'partner';
$pass   = 'partner';
$dbname = 'partner';
$dsn    = createConnection($host, $port, $user, $pass, $dbname);
$table  = 'reviews';

foreach ($files as $file) {
    $updFile = trim($file, '/');
    $reviews = json_decode(file_get_contents($updFile), JSON_UNESCAPED_UNICODE);
    $irarchy = explode('/', $updFile);
    foreach ($reviews as $review) {
        $review['resource'] = $irarchy[0];
        $review['testee']   = $testeeMatcher[$irarchy[0]][$irarchy[1]];
        addData($dsn, $table, $review);
    }
}

