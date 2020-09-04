<?php
declare(strict_types=1);

namespace devnullius\queue\addon\dispatchers;

use devnullius\queue\addon\events\QueueEvent;

interface EventDispatcher
{
    /**
     * @param QueueEvent[] $events
     */
    public function dispatchAll(array $events): void;
    
    /**
     * @param QueueEvent $event
     */
    public function dispatch(QueueEvent $event): void;
}
