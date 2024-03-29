<?php
declare(strict_types=1);

namespace devnullius\queue\addon\events;

interface DomainEvent
{
    /**
     * @return array
     */
    public function releaseEvents(): array;
}
