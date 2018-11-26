<?php declare(strict_types=1);

namespace WyriHaximus\Rx;

use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use Rx\ObservableInterface;
use function React\Promise\resolve;

final class ObservableWhile
{
    /**
     * @var array
     */
    private $queue = [];

    /**
     * @var Deferred
     */
    private $deferred;

    /**
     * @var bool
     */
    private $done = false;

    /**
     * @param ObservableInterface $observable
     */
    public function __construct(ObservableInterface $observable)
    {
        $observable->subscribe(function ($item): void {
            if ($this->deferred instanceof Deferred) {
                $this->deferred->resolve($item);
                $this->deferred = null;

                return;
            }

            $this->queue[] = $item;
        }, null, function (): void {
            $this->done = true;

            if ($this->deferred instanceof Deferred) {
                $this->deferred->resolve();
                $this->deferred = null;
            }
        });
    }

    public function get(): PromiseInterface
    {
        if (\count($this->queue) === 0 && $this->done === true) {
            return resolve();
        }

        if (\count($this->queue) === 0) {
            $this->deferred = new Deferred();

            return $this->deferred->promise();
        }

        return resolve(\array_shift($this->queue));
    }
}
