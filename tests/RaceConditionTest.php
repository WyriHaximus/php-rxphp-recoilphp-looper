<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Rx;

use ApiClients\Tools\TestUtilities\TestCase;
use React\EventLoop\Factory;
use Recoil\React\ReactKernel;
use Rx\Subject\Subject;
use Throwable;
use function WyriHaximus\Rx\observableWhile;

/**
 * @internal
 */
final class RaceConditionTest extends TestCase
{
    public function testRaceCondition(): void
    {
        $output = [];
        $subject = new Subject();

        $loop = Factory::create();
        $recoil = ReactKernel::create($loop);
        $recoil->setExceptionHandler(function (Throwable $error): void {
            echo (string)$error;
        });
        $recoil->execute(function () use ($subject, &$output, $loop) {
            $observableWhile = observableWhile($subject);
            while ($i = (yield $observableWhile->get())) {
                $i = $i();
                $output[] = $i;
            }
        });
        $loop->addTimer(0.5, function () use ($loop, $subject): void {
            $loop->futureTick(function () use ($subject): void {
                $subject->onNext(function () use ($subject) {
                    $subject->onNext(function () {
                        return 2;
                    });

                    return 1;
                });
            });
        });
        $loop->addTimer(1, function () use ($subject): void {
            $subject->onCompleted();
        });
        $loop->run();

        self::assertSame([1,2], $output);
    }
}
