<?php

namespace App\Component\Profile;

class Notification
{
    const TYPE_NO_SIM = 1;
    const TYPE_NO_CHOICE = 2;

    const TYPES = [
        self::TYPE_NO_SIM,
        self::TYPE_NO_CHOICE,
    ];

    private ?string $message;
    private int $type;

    public function __construct(int $type, ?string $message)
    {
        $this->type = $type;
        $this->message = $message;
    }

    public static function noSim(string $message = null): self
    {
        return new static(self::TYPE_NO_SIM, $message);
    }

    public static function noEvents(string $message = null): self
    {
        return new static(self::TYPE_NO_CHOICE, $message);
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getType(): int
    {
        return $this->type;
    }
}
