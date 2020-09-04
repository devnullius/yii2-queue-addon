<?php
declare(strict_types=1);

namespace devnullius\queue\addon\dispatchers;

use devnullius\queue\addon\events\QueueEvent;
use yii\base\InvalidConfigException;
use yii\di\Container;
use yii\di\NotInstantiableException;

final class SimpleEventDispatcher implements EventDispatcher
{
    private Container $container;
    private array $listeners;
    
    /**
     * SimpleEventDispatcher constructor.
     *
     * @param Container $container
     * @param array     $listeners
     */
    public function __construct(Container $container, array $listeners)
    {
        $this->container = $container;
        $this->listeners = $listeners;
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
        $eventName = get_class($event);
        if (array_key_exists($eventName, $this->listeners)) {
            foreach ($this->listeners[$eventName] as $listenerClass) {
                try {
                    $listener = $this->resolveListener($listenerClass);
                    $listener($event);
                } catch (NotInstantiableException $e) {
                } catch (InvalidConfigException $e) {
                }
            }
        }
    }
    
    /**
     * @param string $listenerClass
     *
     * @return callable
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    private function resolveListener(string $listenerClass): callable
    {
        return [$this->container->get($listenerClass), 'handle'];
    }
}
