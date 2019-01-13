<?php
declare(strict_types=1);

namespace MyBudget\Domain\Value;

class Money
{
    /** @var int */
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function value() : int
    {
        return $this->value;
    }

    public function toString() : string
    {
        return (string) $this->value;
    }

    public static function fromString(string $value) : self
    {
        return new self((int) $value);
    }
}