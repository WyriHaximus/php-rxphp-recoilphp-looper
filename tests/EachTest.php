<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Rx;

use ApiClients\Tools\TestUtilities\TestCase;
use function ApiClients\Tools\Rx\observableFromArray;
use function WyriHaximus\Rx\each as observableForEach;

final class EachTest extends TestCase
{
    public function testOne()
    {
        $input = range(0, 1000);
        $output = [];

        $observable = observableFromArray($input);
        foreach (yield from observableForEach($observable) as $i) {
            $output[] = $i;
        }

        self::assertSame($input, $output);
    }
}
