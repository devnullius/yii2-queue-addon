<?php
declare(strict_types=1);

namespace devnullius\queue\addon\example;

use devnullius\queue\addon\dispatchers\EventDispatcher;
use devnullius\queue\addon\wrappers\transaction\TransactionWrapper;
use Yii;
use yii\helpers\Json;
use function random_int;

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
            Yii::debug(Json::encode(['message' => $message]), 'test_events_in_wrapper');
            $message .= $this->generateNextNumber() . ', done.';
            $testEntity = TestEntity::create($message);
            $this->dispatcher->dispatchAll($testEntity->releaseEvents());
            $message .= $this->generateNextNumber() . ', done.';
            $testEntity = TestEntity::create($message);
            $this->dispatcher->dispatchAll($testEntity->releaseEvents());
            $message .= $this->generateNextNumber() . ', done.';
            $testEntity = TestEntity::create($message);
            $this->dispatcher->dispatchAll($testEntity->releaseEvents());
            $message .= $this->generateNextNumber() . ', done.';
            $testEntity = TestEntity::create($message);
            $this->dispatcher->dispatchAll($testEntity->releaseEvents());
            $message .= $this->generateNextNumber() . ', done.';
            $testEntity = TestEntity::create($message);
            $this->dispatcher->dispatchAll($testEntity->releaseEvents());
            $message .= $this->generateNextNumber() . ', done.';
            $testEntity = TestEntity::create($message);
            $this->dispatcher->dispatchAll($testEntity->releaseEvents());
            $message .= $this->generateNextNumber() . ', done.';
            $testEntity = TestEntity::create($message);
            $this->dispatcher->dispatchAll($testEntity->releaseEvents());
        });
    }

    private function generateNextNumber(): int
    {
        return random_int(1000, 100000);
    }
}
