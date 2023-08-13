<?php
declare(strict_types=1);

namespace devnullius\queue\addon\channels;

use devnullius\queue\addon\debug\panel\QueueDebugPanel;
use Yii;
use yii\base\Application;
use yii\base\InvalidConfigException;
use yii\di\Container;
use yii\queue\LogBehavior;
use yii\queue\redis\Queue;

/**
 * need to have config file separated from others, with following content
 *  'queueConfig' => [
 *      'queue' => [
 *      'class' => Queue::class,
 *      'redis' => 'redisQueue',
 *      'channel' => 'queue',
 *      'attempts' => 10,
 *      'as log' => LogBehavior::class,
 *      ],
 * 'queue1' => [
 *      'class' => Queue::class,
 *      'redis' => 'redisQueue1',
 *      'channel' => 'queue1',
 *      'attempts' => 10,
 *      'as log' => LogBehavior::class,
 *      ],
 * ]
 */
final class QueueChannelManager
{
    public static string $configurationKey = 'queueConfig';
    public static string $configurationFilePath = '@common/config/queue.php';
    private static ?self $instance = null;
    private static array $configuration = [];
    /**
     * @var string[]
     */
    private array $channels = [];
    private Application $app;
    private Container $container;

    private function __construct(Application $app, Container $container)
    {
        $this->app = $app;
        $this->container = $container;
        $this->init();
    }

    protected function init(): void
    {
        foreach (array_keys(self::getConfigFile()[self::$configurationKey]) as $channel) {
            $this->add($channel);
        }
    }

    private static function getConfigFile(): array
    {
        if (empty(self::$configuration)) {
            $path = Yii::getAlias(self::$configurationFilePath);
            if (is_file($path)) {
                self::$configuration = require $path;

                return self::$configuration;
            }
            self::$configuration = [
                'queueConfig' => [
                    'queue' => [
                        'class' => Queue::class,
                        'redis' => 'redisQueue',
                        'channel' => 'queue',
                        'attempts' => 10,
                        'as log' => LogBehavior::class,
                    ],
                ],
            ];
        }

        return self::$configuration;
    }

    protected function add(string $channel): void
    {
        $this->channels[] = $channel;
    }

    public static function getInstance(Application $app, Container $container): self
    {
        if (self::$instance === null) {
            self::$instance = new QueueChannelManager($app, $container);

            return self::$instance;
        }

        return self::$instance;
    }

    public static function getChannelsFromConfig(): array
    {

        return array_keys(self::getConfigFile()[self::$configurationKey]);
    }

    public static function getChannelsFromConfigForDebugPanel(): array
    {
        $channels = self::getChannelsFromConfig();
        $panels = [];
        foreach ($channels as $channel) {
            $panels[$channel] = [
                'class' => QueueDebugPanel::class,
                'queueName' => $channel,
            ];
        }

        return $panels;
    }

    public static function getQueuesFromConfig(): array
    {
        return self::getConfigFile()[self::$configurationKey];
    }

    /**
     * @throws InvalidConfigException
     */
    public function getActiveChannelList(): array
    {
        $channelList = [];
        foreach ($this->channels as $channel) {
            $this->container->setSingleton(Queue::class, function () use ($channel) {
                return $this->app->get($channel);
            });
            $channelList[$channel] = $this->app->get($channel);
        }

        return $channelList;
    }

    protected function remove(string $channel): void
    {
        //todo: implement exception if not found if necessary
        foreach ($this->channels as $index => $value) {
            if ($channel === $value) {
                unset($this->channels[$index]);
            }
        }
    }
}
