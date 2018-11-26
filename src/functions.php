<?php declare(strict_types=1);

namespace WyriHaximus\Rx;

use Rx\ObservableInterface;

function observableWhile(ObservableInterface $observable)
{
    return new ObservableWhile($observable);
}
