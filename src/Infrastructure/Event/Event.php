<?php
declare(strict_types=1);

namespace MyBudget\Infrastructure\Event;

interface Event
{
    public function toArray() : array;

    public static function fromArray(array $eventData) : Event;
}