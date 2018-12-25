<?php
declare(strict_types=1);

namespace MyBudget\Domain\Entity;

use Ramsey\Uuid\UuidInterface;

class ExpenseCategory
{
    /** @var UuidInterface */
    private $id;

    /** @var string */
    private $name;

    public function __construct(UuidInterface $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function id() : UuidInterface
    {
        return $this->id;
    }

    public function name() : string
    {
        return $this->name;
    }
}