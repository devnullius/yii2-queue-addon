<?php
declare(strict_types=1);

namespace devnullius\queue\addon\dispatchers;

use devnullius\queue\addon\events\QueueEvent;
use devnullius\queue\addon\jobs\AsyncEventJob;
use yii\queue\Queue;

final class AsyncEventDispatcher implements EventDispatcher
{
    /**
     * @var Queue[]
     */
    private array $channels;
    private ?string $lastJobId;

    public function __construct(array $channels)
    {
        // todo: find a way to throw errors if necessary only
        foreach ($channels as $channel => $instance) {
            assert($instance instanceof Queue);
        }
        $this->channels = $channels;
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
        if (array_key_exists($event->getChannel(), $this->channels)) {
            $this->lastJobId = $this->channels[$event->getChannel()]->push(new AsyncEventJob($event));
        }
    }

    /**
     * @return string|null
     */
    public function getLastJobId(): ?string
    {
        return $this->lastJobId;
    }
}
