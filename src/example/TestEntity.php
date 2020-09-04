<?php
declare(strict_types=1);

namespace devnullius\queue\addon\example;

use devnullius\queue\addon\events\AggregateRoot;
use devnullius\queue\addon\events\EventTrait;

final class TestEntity implements AggregateRoot
{
    use EventTrait;

    private string $message;

    /**
     * TestEntity constructor.
     *
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * @param string $message
     *
     * @return static
     */
    public static function create(string $message): self
    {
        $static = new self($message);
        $static->setMessage($message);
        $static->recordEvent(new TestExampleEvent($static->getMessage()));

        return $static;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
