<?php
declare(strict_types=1);

namespace devnullius\queue\addon\dispatchers;

use devnullius\queue\addon\events\QueueEvent;

final class DeferredEventDispatcher implements DeferredEventDispatcherInterface
{
    private bool $defer = false;
    private array $queue = [];
    private EventDispatcher $next;

    /**
     * DeferredEventDispatcher constructor.
     *
     * @param EventDispatcher $next
     */
    public function __construct(EventDispatcher $next)
    {
        $this->next = $next;
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
        if ($this->defer) {
            $this->queue[] = $event;
        } else {
            $this->next->dispatch($event);
        }
    }

    public function defer(): void
    {
        $this->defer = true;
    }

    public function clean(): void
    {
        $this->queue = [];
        $this->defer = false;
    }

    public function release(): void
    {
        foreach ($this->queue as $i => $event) {
            $this->next->dispatch($event);
            unset($this->queue[$i]);
        }
        $this->defer = false;
    }
}
