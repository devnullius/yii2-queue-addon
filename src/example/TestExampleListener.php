<?php
declare(strict_types=1);

namespace devnullius\queue\addon\example;

use devnullius\queue\addon\events\QueueEvent;
use devnullius\queue\addon\listeners\Listener;
use Yii;
use yii\helpers\Json;

final class TestExampleListener implements Listener
{
    /**
     * @param QueueEvent $queueEvent
     */
    public function handle(QueueEvent $queueEvent): void
    {
        echo 'start handling ...' . "\n";
        echo 'showing message: ' . $queueEvent->getMessage() . "\n";
        echo 'handled ...' . "\n";

        Yii::debug(Json::encode(['message' => $queueEvent->getMessage()]), 'event_message_handled');
    }
}
