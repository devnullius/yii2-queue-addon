<?php
declare(strict_types=1);

namespace devnullius\queue\addon\example;

use devnullius\queue\addon\dispatchers\EventDispatcher;
use devnullius\queue\addon\wrappers\transaction\TransactionWrapper;
use Yii;
use yii\helpers\Json;

final class TestEventService
{
    private EventDispatcher $dispatcher;
    private TransactionWrapper $transactionWrapper;

    public function __construct(EventDispatcher $dispatcher, TransactionWrapper $transactionWrapper)
    {
        $this->dispatcher = $dispatcher;
        $this->transactionWrapper = $transactionWrapper;
    }

    public function testEvent(string $message): void
    {
        Yii::debug(Json::encode(['message' => $message]), 'test_event');


        $this->transactionWrapper->wrap(function () use ($message) {
            Yii::debug(Json::encode(['message' => $message]), 'test_event_in_wrapper');
            $testEntity = TestEntity::create($message);
            $this->dispatcher->dispatchAll($testEntity->releaseEvents());
            $testEntity = TestEntity::create($message);
            $this->dispatcher->dispatchAll($testEntity->releaseEvents());
            $testEntity = TestEntity::create($message);
            $this->dispatcher->dispatchAll($testEntity->releaseEvents());
            $testEntity = TestEntity::create($message);
            $this->dispatcher->dispatchAll($testEntity->releaseEvents());
            $testEntity = TestEntity::create($message);
            $this->dispatcher->dispatchAll($testEntity->releaseEvents());
            $testEntity = TestEntity::create($message);
            $this->dispatcher->dispatchAll($testEntity->releaseEvents());
            $testEntity = TestEntity::create($message);
            $this->dispatcher->dispatchAll($testEntity->releaseEvents());
        });
    }
}
