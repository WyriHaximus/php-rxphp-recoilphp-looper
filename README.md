# Make observables iterable

[![Build Status](https://travis-ci.com/WyriHaximus/php-rxphp-recoilphp-looper.svg?branch=master)](https://travis-ci.com/WyriHaximus/php-rxphp-recoilphp-looper)
[![Latest Stable Version](https://poser.pugx.org/WyriHaximus/rx-recoil-looper/v/stable.png)](https://packagist.org/packages/WyriHaximus/rx-recoil-looper)
[![Total Downloads](https://poser.pugx.org/WyriHaximus/rx-recoil-looper/downloads.png)](https://packagist.org/packages/WyriHaximus/rx-recoil-looper)
[![Code Coverage](https://scrutinizer-ci.com/g/WyriHaximus/php-rxphp-recoilphp-looper/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/WyriHaximus/php-rxphp-recoilphp-looper/?branch=master)
[![License](https://poser.pugx.org/WyriHaximus/rx-recoil-looper/license.png)](https://packagist.org/packages/WyriHaximus/rx-recoil-looper)
[![PHP 7 ready](http://php7ready.timesplinter.ch/WyriHaximus/reactphp-http-middleware-clear-body/badge.svg)](https://travis-ci.org/WyriHaximus/reactphp-http-middleware-clear-body)

# Install

To install via [Composer](http://getcomposer.org/), use the command below, it will automatically detect the latest version and bind it with `^`.

```
composer require wyrihaximus/rx-recoil-looper
```

This middleware removes the raw body from the request. Best used after the request body has been parsed.

# Usage

Iterate over a RxPHP observable using while:

```php
$recoil->execute(function () {
    $observable = observableFromArray(range(1, 1000));
    $observableWhile = observableWhile($observable);
    while ($i = (yield $observableWhile->get())) {
        echo $i, PHP_EOL;
    }
});
```

# License

The MIT License (MIT)

Copyright (c) 2018 Cees-Jan Kiewiet

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
