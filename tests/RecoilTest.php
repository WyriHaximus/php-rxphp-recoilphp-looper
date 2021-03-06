<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Rx;

use ApiClients\Tools\TestUtilities\TestCase;
use React\EventLoop\Factory;
use function React\Promise\resolve;
use Recoil\React\ReactKernel;
use Throwable;

/**
 * @internal
 */
final class RecoilTest extends TestCase
{
    public function testNoMemoryLeakInRecoil(): void
    {
        \gc_collect_cycles();

        $loop = Factory::create();
        $recoil = ReactKernel::create($loop);
        $recoil->setExceptionHandler(function (Throwable $error): void {
            throw $error;
        });
        $recoil->execute(function () {
            yield resolve(true);
        });
        $loop->run();

        self::assertSame(0, \gc_collect_cycles());

        $loop->run();
    }
}
