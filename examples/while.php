<?php

use React\EventLoop\Factory;
use Recoil\React\ReactKernel;
use function ApiClients\Tools\Rx\observableFromArray;
use function WyriHaximus\Rx\observableWhile;

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$loop = Factory::create();

$recoil = ReactKernel::create($loop);
$recoil->setExceptionHandler(function (Throwable $error) {
    echo (string)$error;
});

$recoil->execute(function () {
    $observable = observableFromArray(range(1, 1000));
    $observableWhile = observableWhile($observable);
    while ($i = (yield $observableWhile->get())) {
        echo $i, PHP_EOL;
    }
});

$loop->run();
