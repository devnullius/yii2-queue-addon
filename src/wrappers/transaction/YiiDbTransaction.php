<?php
declare(strict_types=1);

namespace devnullius\queue\addon\wrappers\transaction;

use yii\base\InvalidConfigException;
use yii\db\Connection;
use yii\db\Exception;
use yii\db\Transaction as TransactionAlias;

final class YiiDbTransaction implements Transaction
{
    private TransactionAlias $transaction;
    private Connection $db;

    /**
     * YiiDbTransaction constructor.
     *
     * Should be Yii::$app->db
     *
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @return TransactionAlias
     * @throws InvalidConfigException
     */
    public function beginTransaction(): TransactionAlias
    {
        if ($this->db !== null) {
            $transaction = $this->db->beginTransaction();
            if ($transaction === null) {
                throw new InvalidConfigException('Begin transaction failed.');
            }
            $this->transaction = $transaction;

            return $this->transaction;
        }
        throw new InvalidConfigException('Empty db component.');
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function commit(): void
    {
        if ($this->transaction !== null) {
            $this->transaction->commit();
            return;
        }
        throw new InvalidConfigException('Empty transaction component.');
    }

    /**
     * @throws InvalidConfigException
     */
    public function rollBack(): void
    {
        if ($this->transaction !== null) {
            $this->transaction->rollBack();

            return;
        }
        throw new InvalidConfigException('Empty transaction component.');
    }
}
