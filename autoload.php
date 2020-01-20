<?php
declare(strict_types=1);

spl_autoload_register(
    function ($class) {
        $class = str_replace(['\\', 'ReviewParser/'], ['/', ''], $class);
        include 'src/' . $class . '.php';
    }
);
