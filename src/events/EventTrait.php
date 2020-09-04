<?php
declare(strict_types=1);

namespace devnullius\queue\addon\events;

trait EventTrait
{
    private array $events = [];

    /**
     * @return array
     */
    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

    /**
     * @param QueueEvent $queueEvent
     */
    protected function recordEvent(QueueEvent $queueEvent): void
    {
        $this->events[] = $queueEvent;
    }
}
