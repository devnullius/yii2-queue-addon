<?php
declare(strict_types=1);

namespace devnullius\queue\addon\listeners;

use devnullius\queue\addon\events\QueueEvent;

interface Listener
{
    public function handle(QueueEvent $queueEvent): void;
}
