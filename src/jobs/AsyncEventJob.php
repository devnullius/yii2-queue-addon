<?php
declare(strict_types=1);

namespace devnullius\queue\addon\jobs;

use devnullius\queue\addon\events\QueueEvent;

final class AsyncEventJob extends Job
{
    public QueueEvent $event;
    
    /**
     * AsyncEventJob constructor.
     *
     * @param QueueEvent $event
     */
    public function __construct(QueueEvent $event)
    {
        $this->event = $event;
    }
}
