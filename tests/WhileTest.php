<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Rx;

use ApiClients\Tools\TestUtilities\TestCase;
use React\EventLoop\Factory;
use Recoil\React\ReactKernel;
use Throwable;
use function ApiClients\Tools\Rx\observableFromArray;
use function WyriHaximus\Rx\observableWhile;

final class WhileTest extends TestCase
{
    public function testOne()
    {
        $input = range(1, 1000);
        $output = [];

        $loop = Factory::create();
        $recoil = ReactKernel::create($loop);
        $recoil->setExceptionHandler(function (Throwable $error) {
            throw $error;
        });
        $recoil->execute(function () use ($input, &$output) {
            $observable = observableFromArray($input);
            $observableWhile = observableWhile($observable);
            while ($i = (yield $observableWhile->get())) {
                $output[] = $i;
            }
        });
        $loop->run();

        self::assertSame($input, $output);
    }
}