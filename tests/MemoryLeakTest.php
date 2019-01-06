<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Rx;

use ApiClients\Tools\TestUtilities\TestCase;
use React\EventLoop\Factory;
use Rx\Subject\Subject;
use function WyriHaximus\Rx\observableWhile;

/**
 * @internal
 */
final class MemoryLeakTest extends TestCase
{
    public function testBetweenOnNexts(): void
    {
        \gc_collect_cycles();

        $loop = Factory::create();
        $subject = new Subject();

        $observableWhile = observableWhile($subject);

        $promise = $observableWhile->get();
        $subject->onNext(true);
        $true = $this->await($promise, $loop);
        self::assertSame(0, \gc_collect_cycles());
        self::assertTrue($true);

        $promise = $observableWhile->get();
        $subject->onNext(false);
        $false = $this->await($promise, $loop);
        self::assertSame(0, \gc_collect_cycles());
        self::assertFalse($false);

        $promise = $observableWhile->get();
        $subject->onCompleted();
        $null = $this->await($promise, $loop);
        /**
         * Not testing for a leak here because at this mount the event loop, recoil,
         * and the observableWhile aren't referred to anymore and will be cleaned up.
         */
        self::assertNull($null);
    }
}
