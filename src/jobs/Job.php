<?php
declare(strict_types=1);

namespace devnullius\queue\addon\jobs;

use Yii;
use yii\base\InvalidConfigException;
use yii\queue\JobInterface;
use yii\queue\Queue;

abstract class Job implements JobInterface
{
    /**
     * @param Queue $queue
     *
     * @throws InvalidConfigException
     */
    public function execute($queue): void
    {
        $listener = $this->resolveHandler();
        $listener($this, $queue);
    }
    
    /**
     * @return callable
     * @throws InvalidConfigException
     */
    private function resolveHandler(): callable
    {
        return [Yii::createObject(static::class . 'Handler'), 'handle'];
    }
}
