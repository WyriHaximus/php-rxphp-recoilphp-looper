<?php declare(strict_types=1);

namespace WyriHaximus\Rx;

use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use function React\Promise\resolve;
use Rx\ObservableInterface;

final class ObservableWhile
{
    /**
     * @var \SplQueue
     */
    private $queue;

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
        $this->queue = new \SplQueue();
        $observable->subscribe(function ($item): void {
            if ($this->deferred instanceof Deferred) {
                $this->deferred->resolve($item);
                $this->deferred = null;

                return;
            }

            $this->queue->enqueue($item);
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
        if ($this->queue->count() === 0 && $this->done === true) {
            return resolve();
        }

        if ($this->queue->count() === 0) {
            $this->deferred = new Deferred();

            return $this->deferred->promise();
        }

        return resolve($this->queue->dequeue());
    }
}
