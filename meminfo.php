<?php

use function Clue\React\Block\await;
use React\EventLoop\Factory;
use Rx\Subject\Subject;
use function WyriHaximus\Rx\observableWhile;

unset(
    $_SERVER,
    $_ENV,
    $_POST,
    $_GET,
    $_PUT,
    $_FILES
);

require 'vendor/autoload.php';

meminfo_dump(fopen('meminfo_loop.json', 'w'));
$loop = Factory::create();
meminfo_dump(fopen('meminfo_subject.json', 'w'));
$subject = new Subject();
meminfo_dump(fopen('meminfo_futureTick.json', 'w'));
$loop->addTimer(function () use ($subject): void {
    $subject->onCompleted();
});
meminfo_dump(fopen('meminfo_observableWhile.json', 'w'));
$observableWhile = observableWhile($subject);
meminfo_dump(fopen('meminfo_promise.json', 'w'));
$promise = $observableWhile->get();
meminfo_dump(fopen('meminfo_await.json', 'w'));
$null = await($promise, $loop);

meminfo_dump(fopen('meminfo_before_gc_collect_cycles.json', 'w'));
echo \gc_collect_cycles(), PHP_EOL;
meminfo_dump(fopen('meminfo_after_gc_collect_cycles.json', 'w'));

