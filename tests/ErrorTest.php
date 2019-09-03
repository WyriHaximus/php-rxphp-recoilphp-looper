<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Rx;

use ApiClients\Tools\TestUtilities\TestCase;
use React\EventLoop\Factory;
use Recoil\Exception\StrandException;
use Recoil\React\ReactKernel;
use Rx\Subject\Subject;
use Throwable;
use function WyriHaximus\Rx\observableWhile;

/**
 * @internal
 */
final class ErrorTest extends TestCase
{
    public function testError(): void
    {
        $exception = new \Exception('oops');

        $loop = Factory::create();
        $recoil = ReactKernel::create($loop);

        /** @var StrandException|null $throwable */
        $throwable = null;
        $recoil->setExceptionHandler(function (Throwable $error) use (&$throwable): void {
            $throwable = $error;
        });
        $recoil->execute(function () use (&$output, $exception, $loop) {
            $observable = new Subject();
            $observableWhile = observableWhile($observable);
            $loop->futureTick(function () use ($observable, $exception): void {
                $observable->onError($exception);
            });
            while ($void = (yield $observableWhile->get())) {
                //
            }
        });

        $loop->run();

        self::assertNotNull($throwable);
        self::assertInstanceOf(StrandException::class, $throwable);
        self::assertSame($exception, $throwable->getPrevious());
    }

    public function testQueuedError(): void
    {
        $exception = new \Exception('oops');
        $output = [];

        $loop = Factory::create();
        $recoil = ReactKernel::create($loop);

        /** @var StrandException|null $throwable */
        $throwable = null;
        $recoil->setExceptionHandler(function (Throwable $error) use (&$throwable): void {
            $throwable = $error;
        });
        $recoil->execute(function () use (&$output, $exception) {
            $observable = new Subject();
            $observableWhile = observableWhile($observable);
            $observable->onNext(1);
            $observable->onError($exception);
            while ($i = (yield $observableWhile->get())) {
                $output[] = $i;
            }
        });

        $loop->run();

        self::assertNotNull($throwable);
        self::assertInstanceOf(StrandException::class, $throwable);
        self::assertSame($exception, $throwable->getPrevious());
        self::assertSame([1], $output);
    }
}
