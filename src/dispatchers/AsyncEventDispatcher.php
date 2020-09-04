<?php
declare(strict_types=1);

namespace devnullius\queue\addon\dispatchers;

use devnullius\queue\addon\events\QueueEvent;
use devnullius\queue\addon\jobs\AsyncEventJob;
use yii\queue\Queue;

final class AsyncEventDispatcher implements EventDispatcher
{
    private Queue $queue;
    private ?string $lastJobId;
    
    /**
     * AsyncEventDispatcher constructor.
     *
     * @param Queue $queue
     */
    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }
    
    /**
     * @param QueueEvent[] $events
     */
    public function dispatchAll(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }
    
    /**
     * @param QueueEvent $event
     */
    public function dispatch(QueueEvent $event): void
    {
        $this->lastJobId = $this->queue->push(new AsyncEventJob($event));
    }
    
    /**
     * @return string|null
     */
    public function getLastJobId(): ?string
    {
        return $this->lastJobId;
    }
}
