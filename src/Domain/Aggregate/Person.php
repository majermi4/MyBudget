<?php
declare(strict_types=1);

namespace MyBudget\Domain\Aggregate;

use Ramsey\Uuid\UuidInterface;

class Person
{
    /** @var UuidInterface */
    private $id;

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public function id() : UuidInterface
    {
        return $this->id;
    }
}