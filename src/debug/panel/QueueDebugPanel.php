<?php
declare(strict_types=1);

namespace devnullius\queue\addon\debug\panel;

use yii\queue\debug\Panel;

final class QueueDebugPanel extends Panel
{
    public string $queueName = 'Queue';

    public function getName(): string
    {
        return $this->queueName;
    }
}
