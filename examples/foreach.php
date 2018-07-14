<?php

use function ApiClients\Tools\Rx\observableFromArray;
use function WyriHaximus\Rx\each as observableForEach;

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

(function () {
    $observable = observableFromArray(range(0, 1000));
    foreach (yield from observableForEach($observable) as $i) {
        echo $i, PHP_EOL;
    }
})();
