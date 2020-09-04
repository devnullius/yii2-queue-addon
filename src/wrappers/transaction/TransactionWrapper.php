<?php
declare(strict_types=1);

namespace devnullius\queue\addon\wrappers\transaction;

use devnullius\queue\addon\dispatchers\DeferredEventDispatcherInterface;
use Exception;

final class TransactionWrapper
{
    private DeferredEventDispatcherInterface $dispatcher;
    private Transaction $transaction;

    public function __construct(DeferredEventDispatcherInterface $dispatcher, Transaction $transaction)
    {
        $this->dispatcher = $dispatcher;
        $this->transaction = $transaction;
    }

    /**
     * @param callable $function
     *
     * @throws Exception
     */
    public function wrap(callable $function): void
    {
        $this->transaction->beginTransaction();
        try {
            $this->dispatcher->defer();
            $function();
            $this->transaction->commit();
            $this->dispatcher->release();
        } catch (Exception $e) {
            $this->transaction->rollBack();
            $this->dispatcher->clean();
            throw $e;
        }
    }
}
