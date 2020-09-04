<?php
declare(strict_types=1);

namespace devnullius\queue\addon\wrappers\transaction;

interface Transaction
{
    public function beginTransaction();

    public function commit(): void;

    public function rollBack(): void;
}
