<?php
declare(strict_types=1);

namespace devnullius\queue\addon\jobs;

use devnullius\queue\addon\dispatchers\EventDispatcher;

final class AsyncEventJobHandler
{
    private EventDispatcher $dispatcher;
    
    /**
     * AsyncEventJobHandler constructor.
     *
     * @param EventDispatcher $dispatcher
     */
    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
    
    /**
     * @param AsyncEventJob $job
     */
    public function handle(AsyncEventJob $job): void
    {
        $this->dispatcher->dispatch($job->event);
    }
}
