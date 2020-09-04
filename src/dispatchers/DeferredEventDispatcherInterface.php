<?php
declare(strict_types=1);

namespace devnullius\queue\addon\dispatchers;

interface DeferredEventDispatcherInterface extends EventDispatcher
{
    public function defer(): void;

    public function clean(): void;

    public function release(): void;
}
