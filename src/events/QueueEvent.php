<?php
declare(strict_types=1);

namespace devnullius\queue\addon\events;

interface QueueEvent
{
    public function getChannel(): string;
}
