<?php
declare(strict_types=1);

namespace devnullius\queue\addon\bootstrap;

use devnullius\queue\addon\dispatchers\AsyncEventDispatcher;
use devnullius\queue\addon\dispatchers\DeferredEventDispatcher;
use devnullius\queue\addon\dispatchers\EventDispatcher;
use devnullius\queue\addon\dispatchers\SimpleEventDispatcher;
use devnullius\queue\addon\jobs\AsyncEventJobHandler;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\di\Container;
use yii\di\Instance;
use yii\queue\Queue;

class SetUp implements BootstrapInterface
{
    /**
     * @param Application $app
     */
    final public function bootstrap($app): void
    {
        $container = Yii::$container;

        $container->setSingleton(Queue::class, static function () use ($app) {
            return $app->get('queue');
        });

        $container->setSingleton(EventDispatcher::class, DeferredEventDispatcher::class);

        $container->setSingleton(DeferredEventDispatcher::class, static function (Container $container) {
            /**
             * @var $queue Queue
             */
            $queue = $container->get(Queue::class);

            return new DeferredEventDispatcher(new AsyncEventDispatcher($queue));
        });

        $container->setSingleton(AsyncEventJobHandler::class, [], [
            Instance::of(SimpleEventDispatcher::class),
        ]);
    }
}
