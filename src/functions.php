<?php declare(strict_types=1);

namespace WyriHaximus\Rx;

use Generator;
use Rx\ObservableInterface;

function each(ObservableInterface $observable): Generator
{
    yield 1;
}
