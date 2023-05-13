<?php
declare(strict_types=1);

namespace devnullius\queue\addon\example;

use devnullius\queue\addon\events\QueueEvent;
use Yii;
use yii\helpers\Json;

final class TestExampleEvent implements QueueEvent
{
    private string $message;

    /**
     * TestExampleEvent constructor.
     *
     * @param string $message
     */
    public function __construct(string $message)
    {
        Yii::debug(Json::encode(['message' => $message]), 'setting_event_message');
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    public function getQueueChannel(): string
    {
        return 'queue';
    }
}
