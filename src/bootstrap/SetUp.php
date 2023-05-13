<?php
declare(strict_types=1);

namespace devnullius\queue\addon\bootstrap;

use devnullius\queue\addon\channels\QueueChannelManager;
use devnullius\queue\addon\dispatchers\AsyncEventDispatcher;
use devnullius\queue\addon\dispatchers\DeferredEventDispatcher;
use devnullius\queue\addon\dispatchers\DeferredEventDispatcherInterface;
use devnullius\queue\addon\dispatchers\EventDispatcher;
use devnullius\queue\addon\dispatchers\SimpleEventDispatcher;
use devnullius\queue\addon\jobs\AsyncEventJobHandler;
use devnullius\queue\addon\wrappers\transaction\Transaction;
use devnullius\queue\addon\wrappers\transaction\YiiDbTransaction;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\di\Container;
use yii\di\Instance;

class SetUp implements BootstrapInterface
{
    /**
     * @param Application $app
     */
    final public function bootstrap($app): void
    {
        $container = Yii::$container;

        $container->setSingleton(EventDispatcher::class, DeferredEventDispatcher::class);

        $container->setSingleton(DeferredEventDispatcher::class, static function (Container $container) use ($app) {

            $channels = ($container->get(QueueChannelManager::class))::getInstance($app, $container)->getActiveChannelList();

            return new DeferredEventDispatcher(new AsyncEventDispatcher($channels));
        });

        $container->setSingleton(AsyncEventJobHandler::class, [], [
            Instance::of(SimpleEventDispatcher::class),
        ]);

        $container->setSingleton(DeferredEventDispatcherInterface::class, DeferredEventDispatcher::class);
        $container->setSingleton(YiiDbTransaction::class, static function () use ($app) {
            return new YiiDbTransaction($app->db);
        });
        $container->setSingleton(Transaction::class, YiiDbTransaction::class);

        // need to have something like this in your app set-up also must have configuration file for multichannel queue-s
        /**
         * $container->setSingleton(SimpleEventDispatcher::class, static function (Container $container) {
         * return new SimpleEventDispatcher($container, [
         * TestExampleEvent::class => [TestExampleListener::class],
         * ]);
         * });
         *
         * $container->setSingleton(QueueChannelManager::class, static function (Container $container) use ($app) {
         * return QueueChannelManager::getInstance($app, $container);
         * });
         */
    }
}
